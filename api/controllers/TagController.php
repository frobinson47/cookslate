<?php

require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../middleware/Auth.php';

class TagController {

    /**
     * GET /tags
     * Returns all tags with recipe counts, ordered alphabetically.
     */
    public function list(): array {
        $tagModel = new Tag();
        return $tagModel->getAllWithCounts();
    }

    /**
     * DELETE /tags/{id}
     * Deletes a tag (requires login).
     */
    public function delete(int $id): array {
        Auth::requireWriteAccess();

        $tagModel = new Tag();
        $deleted = $tagModel->delete($id);

        if (!$deleted) {
            http_response_code(404);
            return ['error' => 'Tag not found', 'code' => 404];
        }

        return ['success' => true];
    }
}
