<?php

class License
{
    private bool $active = false;
    private string $tier = 'free';
    private string $email = '';
    private array $claims = [];

    public function __construct(?string $keyFilePath = null, ?string $publicKey = null)
    {
        $keyFile = $keyFilePath ?? __DIR__ . '/license.key';
        $pubKey = $publicKey ?? $this->bundledPublicKey();

        if (!file_exists($keyFile)) {
            return;
        }

        $jwt = trim(file_get_contents($keyFile));
        if (empty($jwt)) {
            return;
        }

        $this->claims = $this->validateJwt($jwt, $pubKey);
        if (!empty($this->claims)) {
            $this->active = true;
            $this->tier = $this->claims['tier'] ?? 'pro';
            $this->email = $this->claims['email'] ?? '';
        }
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function tier(): string
    {
        return $this->tier;
    }

    public function status(): array
    {
        return [
            'active' => $this->active,
            'tier' => $this->tier,
            'email' => $this->email,
        ];
    }

    private function validateJwt(string $jwt, string $publicKey): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return [];
        }

        [$base64Header, $base64Payload, $base64Sig] = $parts;

        // Verify signature
        $signingInput = "$base64Header.$base64Payload";
        $signature = base64_decode(strtr($base64Sig, '-_', '+/'));
        if ($signature === false) {
            return [];
        }

        $valid = openssl_verify($signingInput, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($valid !== 1) {
            return [];
        }

        // Decode payload
        $payload = json_decode(base64_decode(strtr($base64Payload, '-_', '+/')), true);
        if (!is_array($payload)) {
            return [];
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return [];
        }

        return $payload;
    }

    private function bundledPublicKey(): string
    {
        $keyFile = __DIR__ . '/license.pub';
        if (file_exists($keyFile)) {
            return file_get_contents($keyFile);
        }
        return '';
    }

    private static ?License $instance = null;

    public static function getInstance(): License
    {
        if (self::$instance === null) {
            self::$instance = new License();
        }
        return self::$instance;
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    public static function checkActive(): bool
    {
        return self::getInstance()->isActive();
    }
}
