<?php
// api/models/Pantry.php

require_once __DIR__ . '/Database.php';

class Pantry {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllForUser(int $userId): array {
        $stmt = $this->db->prepare('SELECT id, user_id, ingredient_name, quantity, unit, expiration_date, always_stocked, created_at FROM pantry WHERE user_id = ? ORDER BY ingredient_name ASC LIMIT 1000');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Pantry items expiring within $days (inclusive), soonest first.
     * Items with no expiration_date set are never included.
     */
    public function getExpiringSoon(int $userId, int $days = 3): array {
        $stmt = $this->db->prepare('
            SELECT id, user_id, ingredient_name, quantity, unit, expiration_date, always_stocked, created_at
            FROM pantry
            WHERE user_id = ?
              AND expiration_date IS NOT NULL
              AND expiration_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
            ORDER BY expiration_date ASC
            LIMIT 50
        ');
        $stmt->execute([$userId, $days]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, string $ingredientName, ?float $quantity = null, ?string $unit = null, ?string $expirationDate = null): array {
        $normalized = strtolower(trim($ingredientName));

        // Upsert — return existing if duplicate
        $stmt = $this->db->prepare('SELECT * FROM pantry WHERE user_id = ? AND LOWER(ingredient_name) = ?');
        $stmt->execute([$userId, $normalized]);
        $existing = $stmt->fetch();
        if ($existing) {
            if ($quantity === null && $expirationDate === null) {
                return $existing;
            }
            // Same unit (or no prior unit on record) — accumulate. Otherwise the
            // old quantity is in units we can't convert, so just take the latest.
            $sameUnit = $existing['unit'] === null || $unit === null || strtolower($existing['unit']) === strtolower($unit);
            $newQuantity = ($quantity !== null && $sameUnit && $existing['quantity'] !== null) ? (float) $existing['quantity'] + $quantity : ($quantity ?? $existing['quantity']);
            $newUnit = $unit ?? $existing['unit'];
            // Only overwrite expiration if a new one was explicitly given — a
            // routine restock shouldn't silently clear a manually-set date.
            $newExpiration = $expirationDate ?? $existing['expiration_date'];

            $stmt = $this->db->prepare('UPDATE pantry SET quantity = ?, unit = ?, expiration_date = ? WHERE id = ?');
            $stmt->execute([$newQuantity, $newUnit, $newExpiration, $existing['id']]);

            $stmt = $this->db->prepare('SELECT * FROM pantry WHERE id = ?');
            $stmt->execute([$existing['id']]);
            return $stmt->fetch();
        }

        $stmt = $this->db->prepare('INSERT INTO pantry (user_id, ingredient_name, quantity, unit, expiration_date) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $normalized, $quantity, $unit, $expirationDate]);
        $id = (int) $this->db->lastInsertId();

        $stmt = $this->db->prepare('SELECT * FROM pantry WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function setExpiration(int $id, int $userId, ?string $expirationDate): ?array {
        $stmt = $this->db->prepare('UPDATE pantry SET expiration_date = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$expirationDate, $id, $userId]);
        if ($stmt->rowCount() === 0) {
            return null;
        }
        $stmt = $this->db->prepare('SELECT * FROM pantry WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function remove(int $id, int $userId): bool {
        $stmt = $this->db->prepare('DELETE FROM pantry WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }

    public function isInPantry(int $userId, string $ingredientName): bool {
        $normalized = strtolower(trim($ingredientName));
        $stmt = $this->db->prepare('SELECT 1 FROM pantry WHERE user_id = ? AND LOWER(ingredient_name) = ? AND always_stocked = 1');
        $stmt->execute([$userId, $normalized]);
        return (bool) $stmt->fetch();
    }

    public function getPantryMatches(int $userId, array $ingredientNames): array {
        if (empty($ingredientNames)) return [];

        $placeholders = implode(',', array_fill(0, count($ingredientNames), '?'));
        $normalized = array_map(fn($n) => strtolower(trim($n)), $ingredientNames);

        $stmt = $this->db->prepare("SELECT LOWER(ingredient_name) AS name FROM pantry WHERE user_id = ? AND LOWER(ingredient_name) IN ($placeholders) AND always_stocked = 1");
        $stmt->execute(array_merge([$userId], $normalized));

        return array_column($stmt->fetchAll(), 'name');
    }
}
