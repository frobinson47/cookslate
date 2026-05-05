<?php

/**
 * EmailService — sends transactional email via Resend.
 *
 * Configured via env vars:
 *   RESEND_API_KEY  required
 *   EMAIL_FROM      e.g. 'Cookslate <noreply@cookslate.app>'
 *
 * If RESEND_API_KEY is not set (e.g. self-hosted installs without SMTP),
 * send() logs the message and returns false rather than throwing — the
 * caller decides whether that's a hard failure.
 */
class EmailService
{
    private ?string $apiKey;
    private string $from;

    public function __construct()
    {
        $this->apiKey = env('RESEND_API_KEY') ?: null;
        $this->from = env('EMAIL_FROM', 'Cookslate <noreply@cookslate.app>');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Send an email. Returns true on success, false on failure.
     * Does not throw — callers should treat email as best-effort.
     */
    public function send(string $to, string $subject, string $html, ?string $text = null): bool
    {
        if (!$this->apiKey) {
            LoggerService::channel('email')->warning('Email send skipped — RESEND_API_KEY not configured', [
                'to' => $to, 'subject' => $subject,
            ]);
            return false;
        }

        $payload = [
            'from' => $this->from,
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
        ];
        if ($text !== null) {
            $payload['text'] = $text;
        }

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            LoggerService::channel('email')->info('Email sent', [
                'to' => $to, 'subject' => $subject,
            ]);
            return true;
        }

        LoggerService::channel('email')->error('Email send failed', [
            'to' => $to,
            'subject' => $subject,
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $err,
        ]);
        return false;
    }
}
