<?php
// api/models/ShoppingTrip.php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/ShoppingTripItem.php';

class ShoppingTrip {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Create a trip and its line items in one transaction.
     * $items is a list of ['name' => ..., 'quantity' => ?float, 'unit' => ?string, 'price' => ?float].
     */
    public function create(int $userId, ?string $storeName, ?string $tripDate, ?float $totalAmount, ?string $receiptImagePath, array $items): array {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('INSERT INTO shopping_trips (user_id, store_name, trip_date, total_amount, receipt_image_path) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$userId, $storeName, $tripDate, $totalAmount, $receiptImagePath]);
            $tripId = (int) $this->db->lastInsertId();

            $itemModel = new ShoppingTripItem();
            foreach ($items as $item) {
                if (empty($item['name'])) continue;
                $itemModel->create($tripId, $item['name'], $item['quantity'] ?? null, $item['unit'] ?? null, $item['price'] ?? null);
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $this->getById($tripId, $userId);
    }

    public function getAllForUser(int $userId): array {
        $stmt = $this->db->prepare('SELECT id, user_id, store_name, trip_date, total_amount, receipt_image_path, created_at FROM shopping_trips WHERE user_id = ? ORDER BY trip_date DESC, created_at DESC LIMIT 500');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id, int $userId): ?array {
        $stmt = $this->db->prepare('SELECT id, user_id, store_name, trip_date, total_amount, receipt_image_path, created_at FROM shopping_trips WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        $trip = $stmt->fetch();
        if (!$trip) return null;

        $itemModel = new ShoppingTripItem();
        $trip['items'] = $itemModel->getAllForTrip($id);
        return $trip;
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare('DELETE FROM shopping_trips WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }
}
