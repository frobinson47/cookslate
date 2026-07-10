<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class EncryptionTest extends TestCase
{
    private ?string $originalKey = null;

    protected function setUp(): void
    {
        if (!extension_loaded('sodium')) {
            $this->markTestSkipped('sodium extension not available');
        }

        require_once __DIR__ . '/../../config/env.php';
        require_once __DIR__ . '/../../services/Encryption.php';

        $this->originalKey = $_ENV['APP_ENCRYPTION_KEY'] ?? null;
        $_ENV['APP_ENCRYPTION_KEY'] = \Encryption::generateKey();
    }

    protected function tearDown(): void
    {
        if ($this->originalKey === null) {
            unset($_ENV['APP_ENCRYPTION_KEY']);
        } else {
            $_ENV['APP_ENCRYPTION_KEY'] = $this->originalKey;
        }
    }

    public function testEncryptDecryptRoundTrip(): void
    {
        $plaintext = 'sk-test-1234567890';
        $encrypted = \Encryption::encrypt($plaintext);

        $this->assertNotSame($plaintext, $encrypted);
        $this->assertSame($plaintext, \Encryption::decrypt($encrypted));
    }

    public function testDecryptTamperedCiphertextReturnsNull(): void
    {
        $encrypted = \Encryption::encrypt('sk-test-1234567890');
        $tampered = substr($encrypted, 0, -4) . 'AAAA';

        $this->assertNull(\Encryption::decrypt($tampered));
    }

    public function testDecryptGarbageReturnsNull(): void
    {
        $this->assertNull(\Encryption::decrypt('not-valid-base64-or-ciphertext'));
    }

    public function testEncryptThrowsWhenMasterKeyMissing(): void
    {
        unset($_ENV['APP_ENCRYPTION_KEY']);

        $this->expectException(\RuntimeException::class);
        \Encryption::encrypt('sk-test-1234567890');
    }

    public function testEncryptThrowsWhenMasterKeyMalformed(): void
    {
        $_ENV['APP_ENCRYPTION_KEY'] = 'not-base64-32-bytes';

        $this->expectException(\RuntimeException::class);
        \Encryption::encrypt('sk-test-1234567890');
    }
}
