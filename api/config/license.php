<?php

class License
{
    private bool $active = false;
    private string $tier = 'free';
    private string $email = '';
    private array $claims = [];

    public function __construct(?string $jwtOverride = null, ?string $publicKey = null)
    {
        $pubKey = $publicKey ?? $this->bundledPublicKey();

        // Get JWT from override, database, or file (in that order)
        $jwt = $jwtOverride;

        if ($jwt === null) {
            $jwt = $this->loadFromDatabase();
        }

        // Fallback to file for backwards compatibility
        if ($jwt === null) {
            $keyFile = __DIR__ . '/license.key';
            if (file_exists($keyFile)) {
                $jwt = trim(file_get_contents($keyFile));
            }
        }

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

    /**
     * Maximum number of user accounts allowed by the license tier.
     * free = 1, pro = 1, household = 5
     */
    public function maxUsers(): int
    {
        return match ($this->tier) {
            'household' => 5,
            'pro' => 1,
            default => 1,
        };
    }

    public function status(): array
    {
        return [
            'active' => $this->active,
            'tier' => $this->tier,
            'email' => $this->email,
            'max_users' => $this->maxUsers(),
        ];
    }

    /**
     * Save a license key to the database.
     */
    public static function saveToDatabase(string $key): void
    {
        try {
            require_once __DIR__ . '/../models/Database.php';
            $db = Database::getInstance();
            $db->exec("CREATE TABLE IF NOT EXISTS settings (id INTEGER PRIMARY KEY DEFAULT 1, license_key TEXT DEFAULT NULL, CHECK (id = 1)) ENGINE=InnoDB");
            $db->exec("INSERT IGNORE INTO settings (id) VALUES (1)");
            $stmt = $db->prepare('UPDATE settings SET license_key = ? WHERE id = 1');
            $stmt->execute([$key]);
        } catch (\Exception $e) {
            // Silently fail — DB might not be available during tests
        }
    }

    /**
     * Remove the license key from the database.
     */
    public static function removeFromDatabase(): void
    {
        try {
            require_once __DIR__ . '/../models/Database.php';
            $db = Database::getInstance();
            $db->exec('UPDATE settings SET license_key = NULL WHERE id = 1');
        } catch (\Exception $e) {
            // Silently fail
        }
    }

    /**
     * Load the license key from the database.
     */
    private function loadFromDatabase(): ?string
    {
        try {
            require_once __DIR__ . '/../models/Database.php';
            $db = Database::getInstance();
            $stmt = $db->query('SELECT license_key FROM settings WHERE id = 1');
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row && !empty($row['license_key'])) {
                return trim($row['license_key']);
            }
        } catch (\Exception $e) {
            // Table might not exist yet — fall back to file
        }
        return null;
    }

    private function validateJwt(string $jwt, string $publicKey): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return [];
        }

        [$base64Header, $base64Payload, $base64Sig] = $parts;

        $signingInput = "$base64Header.$base64Payload";
        $signature = base64_decode(strtr($base64Sig, '-_', '+/'));
        if ($signature === false) {
            return [];
        }

        $valid = openssl_verify($signingInput, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($valid !== 1) {
            return [];
        }

        $payload = json_decode(base64_decode(strtr($base64Payload, '-_', '+/')), true);
        if (!is_array($payload)) {
            return [];
        }

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
