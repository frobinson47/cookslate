<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/license.php';

class LicenseTest extends TestCase
{
    private string $testKeyFile;
    private string $testPublicKey;
    private string $testPrivateKey;
    /** @var array<string,mixed> */
    private array $opensslConfig;

    protected function setUp(): void
    {
        $this->testKeyFile = sys_get_temp_dir() . '/cookslate_test_license_' . uniqid() . '.key';

        // Generate a test RSA key pair.
        // On Windows, the openssl.cnf may not be at the default system path, so we
        // pass it explicitly via the config array to avoid "No such process" errors.
        $this->opensslConfig = ['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA];
        if (PHP_OS_FAMILY === 'Windows' && file_exists('C:/php/extras/ssl/openssl.cnf')) {
            $this->opensslConfig['config'] = 'C:/php/extras/ssl/openssl.cnf';
        }
        $res = openssl_pkey_new($this->opensslConfig);
        $this->assertNotFalse($res, 'openssl_pkey_new() failed: ' . openssl_error_string());
        openssl_pkey_export($res, $privateKey, null, $this->opensslConfig);
        $details = openssl_pkey_get_details($res);
        $this->testPrivateKey = $privateKey;
        $this->testPublicKey = $details['key'];
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testKeyFile)) {
            unlink($this->testKeyFile);
        }
    }

    public function testNoKeyFileReturnsInactive(): void
    {
        $license = new License('/nonexistent/path.key', $this->testPublicKey);
        $this->assertFalse($license->isActive());
        $this->assertEquals('free', $license->tier());
    }

    public function testEmptyKeyFileReturnsInactive(): void
    {
        file_put_contents($this->testKeyFile, '');
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertFalse($license->isActive());
    }

    public function testValidJwtReturnsActive(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro', 'email' => 'test@example.com']);
        file_put_contents($this->testKeyFile, $jwt);
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertTrue($license->isActive());
        $this->assertEquals('pro', $license->tier());
    }

    public function testExpiredJwtReturnsInactive(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro', 'exp' => time() - 3600]);
        file_put_contents($this->testKeyFile, $jwt);
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertFalse($license->isActive());
    }

    public function testTamperedJwtReturnsInactive(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro']);
        file_put_contents($this->testKeyFile, $jwt . 'tampered');
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $this->assertFalse($license->isActive());
    }

    public function testStatusReturnsStructuredArray(): void
    {
        $jwt = $this->createTestJwt(['tier' => 'pro', 'email' => 'user@test.com']);
        file_put_contents($this->testKeyFile, $jwt);
        $license = new License($this->testKeyFile, $this->testPublicKey);
        $status = $license->status();
        $this->assertTrue($status['active']);
        $this->assertEquals('pro', $status['tier']);
        $this->assertEquals('user@test.com', $status['email']);
    }

    private function createTestJwt(array $claims): string
    {
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $defaults = ['tier' => 'pro', 'email' => 'test@example.com', 'iat' => time(), 'exp' => time() + 86400 * 365];
        $payload = json_encode(array_merge($defaults, $claims));

        $base64Header = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64Payload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

        $signingInput = "$base64Header.$base64Payload";
        openssl_sign($signingInput, $signature, $this->testPrivateKey, OPENSSL_ALGO_SHA256);
        $base64Sig = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "$base64Header.$base64Payload.$base64Sig";
    }
}
