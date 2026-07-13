<?php

/**
 * Static read-only API key for trusted server-to-server callers (e.g. WordPress
 * pulling recipe teasers via wp_remote_get()). Configured via COOKSLATE_API_KEY.
 */
class ApiKeyAuth
{
    public static function isValid(): bool
    {
        $configured = env('COOKSLATE_API_KEY', '');
        if ($configured === '') {
            return false;
        }

        $provided = $_GET['apikey'] ?? '';
        if ($provided === '') {
            return false;
        }

        return hash_equals($configured, $provided);
    }
}
