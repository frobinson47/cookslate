<?php

require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/CookLog.php';
require_once __DIR__ . '/../services/ValidationHelper.php';

class CookLogController {
    public function log(int $recipeId): array {
        $userId = Auth::requireWriteAccess();
        $input = json_decode(file_get_contents('php://input'), true);
        $notes = ValidationHelper::sanitize($input['notes'] ?? null, 2000);

        $model = new CookLog();
        return $model->log($userId, $recipeId, $notes);
    }

    public function history(): array {
        $userId = Auth::requireAuth();
        $model = new CookLog();
        return ['history' => $model->getByUser($userId, 100)];
    }

    public function recipeHistory(int $recipeId): array {
        $userId = Auth::requireAuth();
        $model = new CookLog();
        return ['history' => $model->getByRecipe($userId, $recipeId)];
    }

    public function forgottenFavorites(): array {
        $userId = Auth::requireAuth();
        $model = new CookLog();
        return ['recipes' => $model->getForgottenFavorites($userId)];
    }

    /**
     * GET /cook-log/year-in-review?year=2026
     */
    public function yearInReview(): array {
        $userId = Auth::requireAuth();
        $year = isset($_GET['year']) && ctype_digit((string) $_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

        $model = new CookLog();
        return $model->getYearInReview($userId, $year);
    }
}
