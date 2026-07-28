<?php
// api/models/ShoppingTripItem.php

require_once __DIR__ . '/Database.php';

class ShoppingTripItem {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(int $tripId, string $name, ?float $quantity, ?string $unit, ?float $price): array {
        $stmt = $this->db->prepare('INSERT INTO shopping_trip_items (trip_id, item_name, quantity, unit, price) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$tripId, $name, $quantity, $unit, $price]);
        $id = (int) $this->db->lastInsertId();

        $stmt = $this->db->prepare('SELECT id, trip_id, item_name, quantity, unit, price, created_at FROM shopping_trip_items WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAllForTrip(int $tripId): array {
        $stmt = $this->db->prepare('SELECT id, trip_id, item_name, quantity, unit, price, created_at FROM shopping_trip_items WHERE trip_id = ? ORDER BY id ASC');
        $stmt->execute([$tripId]);
        return $stmt->fetchAll();
    }
}
