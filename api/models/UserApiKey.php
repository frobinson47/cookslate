<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../services/Encryption.php';

class UserApiKey {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Returns the decrypted plaintext key, or null if none exists or decryption fails.
     */
    public function getDecryptedKey(int $userId, string $provider): ?string {
        $stmt = $this->db->prepare('SELECT encrypted_key FROM user_api_keys WHERE user_id = ? AND provider = ?');
        $stmt->execute([$userId, $provider]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        return Encryption::decrypt($row['encrypted_key']);
    }

    public function isConfigured(int $userId, string $provider): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM user_api_keys WHERE user_id = ? AND provider = ?');
        $stmt->execute([$userId, $provider]);
        return (bool) $stmt->fetchColumn();
    }

    public function upsert(int $userId, string $provider, string $plaintextKey): void {
        $encrypted = Encryption::encrypt($plaintextKey);
        $stmt = $this->db->prepare(
            'INSERT INTO user_api_keys (user_id, provider, encrypted_key) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE encrypted_key = VALUES(encrypted_key), updated_at = CURRENT_TIMESTAMP'
        );
        $stmt->execute([$userId, $provider, $encrypted]);
    }

    public function delete(int $userId, string $provider): void {
        $stmt = $this->db->prepare('DELETE FROM user_api_keys WHERE user_id = ? AND provider = ?');
        $stmt->execute([$userId, $provider]);
    }
}
