<?php

/**
 * Sentry initialization. No-op when SENTRY_DSN is unset or the SDK
 * isn't installed, so dev environments don't need to wire anything up.
 */

require_once __DIR__ . '/env.php';

function initSentry(): void
{
    $dsn = env('SENTRY_DSN', '');
    if ($dsn === '') {
        return;
    }
    if (!class_exists(\Sentry\SentrySdk::class)) {
        return;
    }

    \Sentry\init([
        'dsn' => $dsn,
        'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),
        'release' => env('APP_VERSION', null),
        'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', '0.0'),
        'send_default_pii' => false,
    ]);
}

/**
 * Capture a Throwable if Sentry is initialized. Safe to call unconditionally.
 */
function sentryCapture(\Throwable $e): void
{
    if (!function_exists('\\Sentry\\captureException')) {
        return;
    }
    if (env('SENTRY_DSN', '') === '') {
        return;
    }
    \Sentry\captureException($e);
}
