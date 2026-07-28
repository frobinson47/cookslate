<?php
// api/models/Pantry.php

require_once __DIR__ . '/Database.php';

class Pantry {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllForUser(int $userId): array {
        $stmt = $this->db->prepare('SELECT id, user_id, ingredient_name, quantity, unit, always_stocked, created_at FROM pantry WHERE user_id = ? ORDER BY ingredient_name ASC LIMIT 1000');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, string $ingredientName, ?float $quantity = null, ?string $unit = null): array {
        $normalized = strtolower(trim($ingredientName));

        // Upsert — return existing if duplicate
        $stmt = $this->db->prepare('SELECT * FROM pantry WHERE user_id = ? AND LOWER(ingredient_name) = ?');
        $stmt->execute([$userId, $normalized]);
        $existing = $stmt->fetch();
        if ($existing) {
            if ($quantity === null) {
                return $existing;
            }
            // Same unit (or no prior unit on record) — accumulate. Otherwise the
            // old quantity is in units we can't convert, so just take the latest.
            $sameUnit = $existing['unit'] === null || $unit === null || strtolower($existing['unit']) === strtolower($unit);
            $newQuantity = ($sameUnit && $existing['quantity'] !== null) ? (float) $existing['quantity'] + $quantity : $quantity;
            $newUnit = $unit ?? $existing['unit'];

            $stmt = $this->db->prepare('UPDATE pantry SET quantity = ?, unit = ? WHERE id = ?');
            $stmt->execute([$newQuantity, $newUnit, $existing['id']]);

            $stmt = $this->db->prepare('SELECT * FROM pantry WHERE id = ?');
            $stmt->execute([$existing['id']]);
            return $stmt->fetch();
        }

        $stmt = $this->db->prepare('INSERT INTO pantry (user_id, ingredient_name, quantity, unit) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $normalized, $quantity, $unit]);
        $id = (int) $this->db->lastInsertId();

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
