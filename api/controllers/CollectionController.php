<?php

require_once __DIR__ . '/../models/Collection.php';
require_once __DIR__ . '/../middleware/Auth.php';

class CollectionController {

    /**
     * GET /collections
     * All collections for the current user.
     */
    public function list(): array {
        $userId = Auth::requireAuth();
        $model = new Collection();
        return $model->getAllForUser($userId);
    }

    /**
     * GET /collections/{id}
     * Single collection with its recipes.
     */
    public function get(int $id): array {
        $userId = Auth::requireAuth();
        $model = new Collection();

        if (!$model->isOwner($id, $userId)) {
            http_response_code(403);
            return ['error' => 'Access denied', 'code' => 403];
        }

        $collection = $model->findById($id);
        if (!$collection) {
            http_response_code(404);
            return ['error' => 'Collection not found', 'code' => 404];
        }

        $collection['recipes'] = $model->getRecipes($id);
        return $collection;
    }

    /**
     * POST /collections
     * Create a new collection. Expects JSON: { name, description? }
     */
    public function create(): array {
        $userId = Auth::requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['name'])) {
            http_response_code(400);
            return ['error' => 'Collection name is required', 'code' => 400];
        }

        $model = new Collection();
        $collection = $model->create($input['name'], $userId, $input['description'] ?? null);
        http_response_code(201);
        return $collection;
    }

    /**
     * PUT /collections/{id}
     * Update a collection. Expects JSON: { name, description? }
     */
    public function update(int $id): array {
        $userId = Auth::requireAuth();
        $model = new Collection();

        if (!$model->isOwner($id, $userId)) {
            http_response_code(403);
            return ['error' => 'Access denied', 'code' => 403];
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['name'])) {
            http_response_code(400);
            return ['error' => 'Collection name is required', 'code' => 400];
        }

        return $model->update($id, $input['name'], $input['description'] ?? null);
    }

    /**
     * DELETE /collections/{id}
     */
    public function delete(int $id): array {
        $userId = Auth::requireAuth();
        $model = new Collection();

        if (!$model->isOwner($id, $userId)) {
            http_response_code(403);
            return ['error' => 'Access denied', 'code' => 403];
        }

        $model->delete($id);
        return ['message' => 'Collection deleted successfully'];
    }

    /**
     * POST /collections/{id}/recipes/{recipeId}
     * Add a recipe to a collection.
     */
    public function addRecipe(int $id, int $recipeId): array {
        $userId = Auth::requireAuth();
        $model = new Collection();

        if (!$model->isOwner($id, $userId)) {
            http_response_code(403);
            return ['error' => 'Access denied', 'code' => 403];
        }

        $model->addRecipe($id, $recipeId);
        http_response_code(201);
        return ['message' => 'Recipe added to collection'];
    }

    /**
     * DELETE /collections/{id}/recipes/{recipeId}
     * Remove a recipe from a collection.
     */
    public function removeRecipe(int $id, int $recipeId): array {
        $userId = Auth::requireAuth();
        $model = new Collection();

        if (!$model->isOwner($id, $userId)) {
            http_response_code(403);
            return ['error' => 'Access denied', 'code' => 403];
        }

        $model->removeRecipe($id, $recipeId);
        return ['message' => 'Recipe removed from collection'];
    }
}
