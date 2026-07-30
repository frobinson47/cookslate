<?php
// api/models/Pantry.php

require_once __DIR__ . '/Database.php';

class Pantry {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * All pantry items visible to the household (every user on this
     * instance). $userId flags which rows the caller owns (and can
     * therefore edit/delete) — adding a duplicate ingredient still only
     * updates the caller's own row, it doesn't merge across users.
     */
    public function getAllForUser(int $userId): array {
        $stmt = $this->db->prepare('
            SELECT p.id, p.user_id, u.username AS added_by_username, (p.user_id = ?) AS is_owner,
                   p.ingredient_name, p.quantity, p.unit, p.expiration_date, p.always_stocked, p.created_at
            FROM pantry p
            INNER JOIN users u ON u.id = p.user_id
            ORDER BY p.ingredient_name ASC
            LIMIT 1000
        ');
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['is_owner'] = (bool) $row['is_owner'];
        }
        return $rows;
    }

    /**
     * Household-wide pantry items expiring within $days (inclusive), soonest first.
     * Items with no expiration_date set are never included.
     */
    public function getExpiringSoon(int $userId, int $days = 3): array {
        $stmt = $this->db->prepare('
            SELECT p.id, p.user_id, u.username AS added_by_username, (p.user_id = ?) AS is_owner,
                   p.ingredient_name, p.quantity, p.unit, p.expiration_date, p.always_stocked, p.created_at
            FROM pantry p
            INNER JOIN users u ON u.id = p.user_id
            WHERE p.expiration_date IS NOT NULL
              AND p.expiration_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
            ORDER BY p.expiration_date ASC
            LIMIT 50
        ');
        $stmt->execute([$userId, $days]);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['is_owner'] = (bool) $row['is_owner'];
        }
        return $rows;
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

    public function setExpiration(int $id, int $userId, ?string $expirationDate, bool $isAdmin = false): ?array {
        if ($isAdmin) {
            $stmt = $this->db->prepare('UPDATE pantry SET expiration_date = ? WHERE id = ?');
            $stmt->execute([$expirationDate, $id]);
        } else {
            $stmt = $this->db->prepare('UPDATE pantry SET expiration_date = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([$expirationDate, $id, $userId]);
        }
        if ($stmt->rowCount() === 0) {
            return null;
        }
        $stmt = $this->db->prepare('SELECT * FROM pantry WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function remove(int $id, int $userId, bool $isAdmin = false): bool {
        if ($isAdmin) {
            $stmt = $this->db->prepare('DELETE FROM pantry WHERE id = ?');
            $stmt->execute([$id]);
        } else {
            $stmt = $this->db->prepare('DELETE FROM pantry WHERE id = ? AND user_id = ?');
            $stmt->execute([$id, $userId]);
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * Household-wide check — $userId is unused (kept for call-site
     * compatibility) since a shared pantry means any member's stocked
     * ingredient counts for the whole household.
     */
    public function isInPantry(int $userId, string $ingredientName): bool {
        $normalized = strtolower(trim($ingredientName));
        $stmt = $this->db->prepare('SELECT 1 FROM pantry WHERE LOWER(ingredient_name) = ? AND always_stocked = 1');
        $stmt->execute([$normalized]);
        return (bool) $stmt->fetch();
    }

    /**
     * Household-wide match — see isInPantry() note on $userId.
     */
    public function getPantryMatches(int $userId, array $ingredientNames): array {
        if (empty($ingredientNames)) return [];

        $placeholders = implode(',', array_fill(0, count($ingredientNames), '?'));
        $normalized = array_map(fn($n) => strtolower(trim($n)), $ingredientNames);

        $stmt = $this->db->prepare("SELECT DISTINCT LOWER(ingredient_name) AS name FROM pantry WHERE LOWER(ingredient_name) IN ($placeholders) AND always_stocked = 1");
        $stmt->execute($normalized);

        return array_column($stmt->fetchAll(), 'name');
    }
}
