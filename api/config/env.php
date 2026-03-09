<?php

/**
 * Minimal .env loader. Loads KEY=VALUE pairs into $_ENV and putenv().
 */
function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

/**
 * Get an environment variable with optional default.
 */
function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

/**
 * Get session cookie parameters based on environment.
 */
function getSessionCookieParams(): array
{
    $isProduction = env('APP_ENV', 'production') === 'production';
    return [
        'samesite' => 'Lax',
        'httponly' => true,
        'secure' => $isProduction,
        'path' => '/',
    ];
}

/**
 * Session lifetime in seconds (2 hours).
 */
function getSessionLifetime(): int
{
    return 7200;
}

/**
 * Get the CA bundle path for cURL SSL verification.
 * Checks: curl.cainfo php.ini → CURL_CA_BUNDLE env → common OS paths → Laragon fallback.
 * Returns null if no CA bundle found (cURL will use its built-in default).
 */
function getCaBundlePath(): ?string
{
    // 1. PHP ini setting
    $caInfo = ini_get('curl.cainfo');
    if (!empty($caInfo) && file_exists($caInfo)) {
        return $caInfo;
    }

    // 2. Environment variable (standard, works on all platforms)
    $envCa = env('CURL_CA_BUNDLE');
    if ($envCa && file_exists($envCa)) {
        return $envCa;
    }

    // 3. Common locations
    $commonPaths = [
        '/etc/ssl/certs/ca-certificates.crt',     // Debian/Ubuntu
        '/etc/pki/tls/certs/ca-bundle.crt',        // RHEL/CentOS
        '/etc/ssl/ca-bundle.pem',                   // openSUSE
        '/usr/local/etc/openssl/cert.pem',          // macOS Homebrew
        'D:/laragon/etc/ssl/cacert.pem',            // Laragon on Windows
        'C:/laragon/etc/ssl/cacert.pem',            // Laragon alternate drive
    ];

    foreach ($commonPaths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    return null;
}
