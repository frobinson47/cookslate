<?php

/**
 * Resolves a Pinterest pin URL to its outbound recipe source URL.
 * Single-shot/user-triggered only — never bulk, never caches pin content.
 */
class PinterestResolver {

    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    // Domains a pin's own JSON blob references about itself — never the actual source.
    private const SELF_REFERENTIAL_HOSTS = ['pinterest.', 'pin.it', 'pinimg.com'];

    /**
     * True if the URL points at a Pinterest pin or short link.
     */
    public function isPinterestUrl(string $url): bool {
        $parsed = parse_url($url);
        $host = strtolower($parsed['host'] ?? '');
        if ($host === '') {
            return false;
        }

        if ($host === 'pin.it' || str_ends_with($host, '.pin.it')) {
            return true;
        }

        // Matches pinterest.com, www.pinterest.com, pinterest.co.uk, pinterest.de, etc.
        if (!preg_match('/(^|\.)pinterest\.[a-z.]+$/i', $host)) {
            return false;
        }

        // Require an actual pin path — avoid false-positives on the Pinterest homepage/search.
        $path = $parsed['path'] ?? '';
        return (bool) preg_match('#^/pin/#i', $path);
    }

    /**
     * Resolve a Pinterest URL to its outbound source URL.
     * Returns null if resolution fails or the pin has no outbound link
     * (e.g. Idea Pins, native image uploads).
     */
    public function resolve(string $url): ?string {
        $parsed = parse_url($url);
        $host = strtolower($parsed['host'] ?? '');

        if ($host === 'pin.it' || str_ends_with($host, '.pin.it')) {
            $canonical = $this->followRedirect($url);
            if ($canonical === null) {
                return null;
            }
            $url = $canonical;
        }

        $html = $this->fetchPinPage($url);
        if ($html === null) {
            return null;
        }

        return $this->extractSourceLink($html);
    }

    /**
     * Follow a pin.it short link redirect to its canonical pinterest.com URL.
     */
    private function followRedirect(string $url): ?string {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_USERAGENT => self::USER_AGENT,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $caBundle = function_exists('getCaBundlePath') ? getCaBundlePath() : null;
        if ($caBundle) {
            curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
        }

        $success = curl_exec($ch);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $curlError = curl_errno($ch);
        curl_close($ch);

        if ($success === false || $curlError !== 0) {
            return null;
        }

        return $effectiveUrl ?: null;
    }

    /**
     * Fetch the canonical pin page HTML.
     */
    private function fetchPinPage(string $url): ?string {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_USERAGENT => self::USER_AGENT,
            CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml'],
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $caBundle = function_exists('getCaBundlePath') ? getCaBundlePath() : null;
        if ($caBundle) {
            curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
        }

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_errno($ch);
        curl_close($ch);

        if ($html === false || $curlError !== 0 || $httpCode !== 200) {
            return null;
        }

        return $html;
    }

    /**
     * Defensively extract the pin's outbound "link" field from its embedded
     * JSON data. Not tied to a specific script-tag id, since Pinterest's
     * markup has changed before — scans for the JSON field pattern directly
     * and filters out links that just point back at Pinterest itself.
     */
    public function extractSourceLink(string $html): ?string {
        if (!preg_match_all('/"link"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/', $html, $matches)) {
            return null;
        }

        foreach ($matches[1] as $rawLink) {
            $link = json_decode('"' . $rawLink . '"');
            if (!is_string($link) || $link === '') {
                continue;
            }

            $linkHost = strtolower(parse_url($link, PHP_URL_HOST) ?? '');
            if ($linkHost === '') {
                continue;
            }

            if ($this->isSelfReferential($linkHost)) {
                continue;
            }

            if (!preg_match('#^https?://#i', $link)) {
                continue;
            }

            return $link;
        }

        return null;
    }

    private function isSelfReferential(string $host): bool {
        foreach (self::SELF_REFERENTIAL_HOSTS as $needle) {
            if (str_contains($host, $needle)) {
                return true;
            }
        }
        return false;
    }
}
