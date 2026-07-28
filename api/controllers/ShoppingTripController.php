<?php
// api/controllers/ShoppingTripController.php

require_once __DIR__ . '/../models/ShoppingTrip.php';
require_once __DIR__ . '/../models/Pantry.php';
require_once __DIR__ . '/../models/UserApiKey.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../services/ValidationHelper.php';
require_once __DIR__ . '/../services/OpenAiReceiptParser.php';

class ShoppingTripController {

    /**
     * POST /shopping-trips/import-receipt
     * Accepts a multipart upload (field: "image"). Uses the user's own OpenAI
     * API key to extract line items from the receipt photo. Returns the
     * parsed-but-unsaved shape (including error_code/error on failure) for
     * the frontend's review/edit screen — never persisted until /shopping-trips.
     */
    public function importReceipt(): array {
        $userId = Auth::requireAuth();

        $keyModel = new UserApiKey();
        $apiKey = $keyModel->getDecryptedKey($userId, 'openai');
        if (!$apiKey) {
            return [
                'store_name' => null, 'trip_date' => null, 'total_amount' => null, 'items' => [],
                'error_code' => 'no_api_key',
                'error' => 'Add your OpenAI API key in Settings to use this feature.',
            ];
        }

        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            return ['error' => 'An image file is required', 'code' => 400];
        }

        $file = $_FILES['image'];
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            http_response_code(400);
            return ['error' => 'Image exceeds the size limit', 'code' => 400];
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($mimeType, $allowedTypes, true)) {
            http_response_code(400);
            return ['error' => 'Unsupported image type. Use JPEG, PNG, WEBP, or GIF.', 'code' => 400];
        }

        $imageBase64 = base64_encode(file_get_contents($file['tmp_name']));

        $parser = new OpenAiReceiptParser();
        return $parser->parseReceipt($imageBase64, $mimeType, $apiKey);
    }

    /**
     * POST /shopping-trips
     * Confirm a (possibly user-edited) parsed receipt: persist the trip and its
     * line items, and prefill matched quantities into the pantry. Expects JSON:
     * { store_name, trip_date, total_amount, items: [{ name, quantity, unit, price }] }
     */
    public function create(): array {
        $userId = Auth::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true) ?? [];

        $v = new ValidationHelper();
        $v->maxLength($input['store_name'] ?? null, 'store_name', 255);
        $response = $v->responseIfFailed();
        if ($response) return $response;

        $items = is_array($input['items'] ?? null) ? $input['items'] : [];
        if (empty($items)) {
            http_response_code(400);
            return ['error' => 'At least one item is required', 'code' => 400];
        }

        $storeName = !empty($input['store_name']) ? ValidationHelper::sanitize($input['store_name'], 255) : null;
        $tripDate = $this->normalizeDate($input['trip_date'] ?? null);
        $totalAmount = isset($input['total_amount']) && is_numeric($input['total_amount']) ? (float) $input['total_amount'] : null;

        $normalizedItems = [];
        foreach ($items as $item) {
            if (empty($item['name'])) continue;
            $normalizedItems[] = [
                'name' => ValidationHelper::sanitize((string) $item['name'], 255),
                'quantity' => isset($item['quantity']) && is_numeric($item['quantity']) ? (float) $item['quantity'] : null,
                'unit' => !empty($item['unit']) ? ValidationHelper::sanitize((string) $item['unit'], 32) : null,
                'price' => isset($item['price']) && is_numeric($item['price']) ? (float) $item['price'] : null,
            ];
        }

        $tripModel = new ShoppingTrip();
        $trip = $tripModel->create($userId, $storeName, $tripDate, $totalAmount, null, $normalizedItems);

        $pantry = new Pantry();
        foreach ($normalizedItems as $item) {
            $pantry->add($userId, $item['name'], $item['quantity'], $item['unit']);
        }

        http_response_code(201);
        return $trip;
    }

    /**
     * GET /shopping-trips
     */
    public function list(): array {
        $userId = Auth::requireAuth();
        $tripModel = new ShoppingTrip();
        return ['trips' => $tripModel->getAllForUser($userId)];
    }

    /**
     * GET /shopping-trips/{id}
     */
    public function get(int $id): array {
        $userId = Auth::requireAuth();
        $tripModel = new ShoppingTrip();
        $trip = $tripModel->getById($id, $userId);

        if (!$trip) {
            http_response_code(404);
            return ['error' => 'Shopping trip not found', 'code' => 404];
        }

        return $trip;
    }

    /**
     * DELETE /shopping-trips/{id}
     */
    public function delete(int $id): array {
        $userId = Auth::requireAuth();
        $tripModel = new ShoppingTrip();

        if (!$tripModel->delete($id, $userId)) {
            http_response_code(404);
            return ['error' => 'Shopping trip not found', 'code' => 404];
        }

        return ['message' => 'Shopping trip deleted'];
    }

    private function normalizeDate(mixed $value): ?string {
        if (!is_string($value) || $value === '') return null;
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : null;
    }
}
