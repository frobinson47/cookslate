<?php

class DemoGuard
{
    /**
     * Check if the current request is allowed for demo users.
     * Returns ['allowed' => bool, 'error' => string|null]
     */
    public static function check(?string $resource = null, ?string $action = null): array
    {
        // Not a demo user — always allowed
        if (empty($_SESSION['is_demo'])) {
            return ['allowed' => true, 'error' => null];
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // GET/HEAD/OPTIONS always allowed
        if (in_array($method, ['GET', 'HEAD', 'OPTIONS'])) {
            return ['allowed' => true, 'error' => null];
        }

        // Block account-destructive operations only
        if ($resource === 'auth' && $action === 'password') {
            return ['allowed' => false, 'error' => 'Cannot change password on demo account'];
        }
        if ($resource === 'admin') {
            return ['allowed' => false, 'error' => 'Admin operations are disabled in demo'];
        }

        // Allow all other operations so demo users can try every feature
        return ['allowed' => true, 'error' => null];
    }
}
