<?php

/**
 * Encrypts/decrypts secrets at rest using libsodium secretbox.
 * Used to store user-provided API keys (e.g. OpenAI) in the database.
 */
class Encryption
{
    /**
     * Encrypt a plaintext string. Returns a base64 string of nonce + ciphertext.
     * Throws RuntimeException if sodium is unavailable or APP_ENCRYPTION_KEY is misconfigured.
     */
    public static function encrypt(string $plaintext): string
    {
        $key = self::getMasterKey();
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($plaintext, $nonce, $key);
        return base64_encode($nonce . $ciphertext);
    }

    /**
     * Decrypt a value produced by encrypt(). Returns null on any failure
     * (corrupt data, wrong key, tampering) so callers treat it as
     * "key unavailable" rather than crashing the request.
     */
    public static function decrypt(string $encoded): ?string
    {
        try {
            $key = self::getMasterKey();
        } catch (RuntimeException $e) {
            return null;
        }

        $raw = base64_decode($encoded, true);
        if ($raw === false || strlen($raw) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
            return null;
        }

        $nonce = substr($raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = substr($raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);

        return $plaintext === false ? null : $plaintext;
    }

    /**
     * Generate a new base64-encoded 32-byte master key (for setup/docs/CLI use).
     */
    public static function generateKey(): string
    {
        return base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
    }

    private static function getMasterKey(): string
    {
        if (!extension_loaded('sodium')) {
            throw new RuntimeException('sodium extension is not enabled');
        }

        $b64 = env('APP_ENCRYPTION_KEY', '');
        if ($b64 === '') {
            throw new RuntimeException('APP_ENCRYPTION_KEY is not configured');
        }

        $key = base64_decode($b64, true);
        if ($key === false || strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new RuntimeException('APP_ENCRYPTION_KEY is invalid — expected a base64-encoded 32-byte key');
        }

        return $key;
    }
}
