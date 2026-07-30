<?php
// api/controllers/ExternalController.php
// Read-only endpoints for trusted external integrations (e.g. Home Assistant
// REST sensors), authenticated via the instance-wide COOKSLATE_API_KEY
// (?apikey=...) rather than a user session.
//
// NOTE: the API key is instance-wide, not per-user, so these endpoints
// resolve to a single "primary" account (the lowest-id admin user) rather
// than any particular household member. This matches how the app is
// typically run — one Cookslate instance per household — but means a
// multi-admin household's external integration always reflects the first
// admin's data. Revisit if/when per-key user scoping is ever needed.

require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Pantry.php';

class ExternalController {

    private function primaryUserId(): ?int {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT id FROM users WHERE role = 'admin' ORDER BY id ASC LIMIT 1");
        $id = $stmt->fetchColumn();
        return $id !== false ? (int) $id : null;
    }

    /**
     * GET /external/today-meal?apikey=...
     * Today's planned meals. Requires an active Pro license, same as the
     * regular meal plan feature.
     */
    public function todayMeal(): array {
        require_once __DIR__ . '/../config/license.php';
        if (!\License::checkActive()) {
            http_response_code(403);
            return ['error' => 'Pro license required', 'code' => 403];
        }

        $userId = $this->primaryUserId();
        if (!$userId) {
            return ['meals' => []];
        }

        require_once __DIR__ . '/../pro/models/MealPlan.php';
        $model = new \MealPlan();
        return ['meals' => $model->getToday($userId)];
    }

    /**
     * GET /external/pantry-alerts?apikey=...&days=3
     * Pantry items expiring within N days (default 3).
     */
    public function pantryAlerts(): array {
        $userId = $this->primaryUserId();
        if (!$userId) {
            return ['items' => []];
        }

        $days = isset($_GET['days']) && ctype_digit((string) $_GET['days']) ? (int) $_GET['days'] : 3;
        $pantry = new Pantry();
        return ['items' => $pantry->getExpiringSoon($userId, $days)];
    }
}
