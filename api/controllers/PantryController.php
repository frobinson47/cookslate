<?php
// api/controllers/PantryController.php

require_once __DIR__ . '/../models/Pantry.php';
require_once __DIR__ . '/../models/UserApiKey.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../services/ValidationHelper.php';

class PantryController {

    /**
     * GET /pantry
     * List all pantry items for the current user.
     */
    public function list(): array {
        $userId = Auth::requireAuth();
        $pantry = new Pantry();
        return ['items' => $pantry->getAllForUser($userId)];
    }

    /**
     * GET /pantry/expiring?days=3
     * Pantry items expiring within N days (default 3).
     */
    public function expiring(): array {
        $userId = Auth::requireAuth();
        $days = isset($_GET['days']) && ctype_digit((string) $_GET['days']) ? (int) $_GET['days'] : 3;
        $pantry = new Pantry();
        return ['items' => $pantry->getExpiringSoon($userId, $days)];
    }

    /**
     * POST /pantry
     * Add an item to the pantry. Expects JSON: { ingredient_name }
     */
    public function add(): array {
        $userId = Auth::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);

        $v = new ValidationHelper();
        $v->required($input['ingredient_name'] ?? null, 'ingredient_name')
          ->maxLength($input['ingredient_name'] ?? null, 'ingredient_name', 255);
        $response = $v->responseIfFailed();
        if ($response) return $response;

        $pantry = new Pantry();
        $item = $pantry->add($userId, ValidationHelper::sanitize($input['ingredient_name'], 255));

        http_response_code(201);
        return $item;
    }

    /**
     * POST /pantry/scan
     * Accepts a multipart upload (field: "image") — a photo of a fridge,
     * pantry, or freezer shelf. Uses the user's own OpenAI API key to
     * identify visible food items. Returns a parsed-but-unsaved list for
     * the frontend's review/edit screen — never persisted until /pantry/bulk.
     */
    public function scan(): array {
        $userId = Auth::requireAuth();

        $keyModel = new UserApiKey();
        $apiKey = $keyModel->getDecryptedKey($userId, 'openai');
        if (!$apiKey) {
            return [
                'items' => [],
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

        require_once __DIR__ . '/../services/OpenAiPantryScanParser.php';
        $parser = new \OpenAiPantryScanParser();
        return $parser->parsePantryScan($imageBase64, $mimeType, $apiKey);
    }

    /**
     * POST /pantry/bulk
     * Confirm a (possibly user-edited) parsed pantry scan. Expects JSON:
     * { items: [{ name, quantity, unit, expiration_date }] }
     */
    public function bulkAdd(): array {
        $userId = Auth::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $items = is_array($input['items'] ?? null) ? $input['items'] : [];

        if (empty($items)) {
            http_response_code(400);
            return ['error' => 'At least one item is required', 'code' => 400];
        }

        $pantry = new Pantry();
        $added = [];

        foreach ($items as $item) {
            if (empty($item['name'])) continue;

            $name = ValidationHelper::sanitize((string) $item['name'], 255);
            $quantity = isset($item['quantity']) && is_numeric($item['quantity']) ? (float) $item['quantity'] : null;
            $unit = !empty($item['unit']) ? ValidationHelper::sanitize((string) $item['unit'], 32) : null;
            $expirationDate = null;
            if (!empty($item['expiration_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $item['expiration_date'])) {
                $expirationDate = $item['expiration_date'];
            }

            $added[] = $pantry->add($userId, $name, $quantity, $unit, $expirationDate);
        }

        try {
            require_once __DIR__ . '/../services/IngredientDataEnricher.php';
            (new \IngredientDataEnricher())->enrichFromScan(array_column($added, 'ingredient_name'));
        } catch (\Throwable $e) {
            // Best-effort enrichment — never block the pantry add from succeeding.
        }

        http_response_code(201);
        return ['items' => $added];
    }

    /**
     * PUT /pantry/{id}
     * Set (or clear) an item's expiration date. Expects JSON: { expiration_date }
     * (YYYY-MM-DD string, or null to clear).
     */
    public function update(int $id): array {
        $userId = Auth::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true) ?? [];

        $expirationDate = null;
        if (!empty($input['expiration_date'])) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input['expiration_date'])) {
                http_response_code(400);
                return ['error' => 'expiration_date must be YYYY-MM-DD', 'code' => 400];
            }
            $expirationDate = $input['expiration_date'];
        }

        $pantry = new Pantry();
        $item = $pantry->setExpiration($id, $userId, $expirationDate);

        if (!$item) {
            http_response_code(404);
            return ['error' => 'Pantry item not found', 'code' => 404];
        }

        return $item;
    }

    /**
     * DELETE /pantry/{id}
     * Remove an item from the pantry.
     */
    public function remove(int $id): array {
        $userId = Auth::requireAuth();
        $pantry = new Pantry();

        if (!$pantry->remove($id, $userId)) {
            http_response_code(404);
            return ['error' => 'Pantry item not found', 'code' => 404];
        }

        return ['message' => 'Removed from pantry'];
    }
}
