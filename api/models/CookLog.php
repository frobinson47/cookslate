<?php

require_once __DIR__ . '/Database.php';

class CookLog {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function log(int $userId, int $recipeId, ?string $notes = null): array {
        $stmt = $this->db->prepare('INSERT INTO cook_log (user_id, recipe_id, notes) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $recipeId, $notes]);
        return [
            'id' => (int) $this->db->lastInsertId(),
            'recipe_id' => $recipeId,
            'cooked_at' => date('Y-m-d H:i:s'),
        ];
    }

    public function getByUser(int $userId, int $limit = 20): array {
        $stmt = $this->db->prepare('
            SELECT cl.id, cl.recipe_id, cl.cooked_at, cl.notes,
                   r.title, r.image_path
            FROM cook_log cl
            INNER JOIN recipes r ON cl.recipe_id = r.id
            WHERE cl.user_id = ?
            ORDER BY cl.cooked_at DESC
            LIMIT ?
        ');
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public function getCountForRecipe(int $userId, int $recipeId): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM cook_log WHERE user_id = ? AND recipe_id = ?');
        $stmt->execute([$userId, $recipeId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get cook history for a specific recipe by the current user.
     */
    public function getByRecipe(int $userId, int $recipeId): array {
        $stmt = $this->db->prepare('
            SELECT id, cooked_at, notes
            FROM cook_log
            WHERE user_id = ? AND recipe_id = ?
            ORDER BY cooked_at DESC
            LIMIT 500
        ');
        $stmt->execute([$userId, $recipeId]);
        return $stmt->fetchAll();
    }

    /**
     * "Year in Cooking" recap for a single calendar year: total meals cooked,
     * most active month, most-made recipe, new recipes tried, longest streak
     * of consecutive cooking days, and top tag ("top cuisine" proxy — recipes
     * don't have a dedicated cuisine field).
     */
    public function getYearInReview(int $userId, int $year): array {
        $stmt = $this->db->prepare('
            SELECT COUNT(*) AS total_meals, COUNT(DISTINCT recipe_id) AS unique_recipes
            FROM cook_log
            WHERE user_id = ? AND YEAR(cooked_at) = ?
        ');
        $stmt->execute([$userId, $year]);
        $totals = $stmt->fetch();

        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(cooked_at, '%Y-%m') AS month, COUNT(*) AS count
            FROM cook_log
            WHERE user_id = ? AND YEAR(cooked_at) = ?
            GROUP BY month
            ORDER BY count DESC
            LIMIT 1
        ");
        $stmt->execute([$userId, $year]);
        $mostActiveMonth = $stmt->fetch();

        $stmt = $this->db->prepare('
            SELECT r.id, r.title, r.image_path, COUNT(*) AS cook_count
            FROM cook_log cl
            INNER JOIN recipes r ON cl.recipe_id = r.id
            WHERE cl.user_id = ? AND YEAR(cl.cooked_at) = ?
            GROUP BY r.id, r.title, r.image_path
            ORDER BY cook_count DESC
            LIMIT 1
        ');
        $stmt->execute([$userId, $year]);
        $mostMade = $stmt->fetch();

        $stmt = $this->db->prepare('
            SELECT COUNT(DISTINCT recipe_id) AS new_recipes
            FROM cook_log
            WHERE user_id = ?
              AND YEAR(cooked_at) = ?
              AND recipe_id NOT IN (
                  SELECT recipe_id FROM cook_log
                  WHERE user_id = ? AND cooked_at < CONCAT(?, "-01-01")
              )
        ');
        $stmt->execute([$userId, $year, $userId, $year]);
        $newRecipesTried = (int) $stmt->fetchColumn();

        $stmt = $this->db->prepare('
            SELECT t.name, COUNT(*) AS count
            FROM cook_log cl
            INNER JOIN recipe_tags rt ON cl.recipe_id = rt.recipe_id
            INNER JOIN tags t ON rt.tag_id = t.id
            WHERE cl.user_id = ? AND YEAR(cl.cooked_at) = ?
            GROUP BY t.name
            ORDER BY count DESC
            LIMIT 1
        ');
        $stmt->execute([$userId, $year]);
        $topTag = $stmt->fetch();

        $stmt = $this->db->prepare('
            SELECT DISTINCT DATE(cooked_at) AS cook_date
            FROM cook_log
            WHERE user_id = ? AND YEAR(cooked_at) = ?
            ORDER BY cook_date ASC
        ');
        $stmt->execute([$userId, $year]);
        $dates = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $streakPeak = 0;
        $current = 0;
        $prevDate = null;
        foreach ($dates as $dateStr) {
            $date = new \DateTime($dateStr);
            if ($prevDate !== null && $prevDate->diff($date)->days === 1) {
                $current++;
            } else {
                $current = 1;
            }
            $streakPeak = max($streakPeak, $current);
            $prevDate = $date;
        }

        return [
            'year' => $year,
            'total_meals' => (int) $totals['total_meals'],
            'unique_recipes' => (int) $totals['unique_recipes'],
            'most_active_month' => $mostActiveMonth ? [
                'month' => $mostActiveMonth['month'],
                'count' => (int) $mostActiveMonth['count'],
            ] : null,
            'most_made_recipe' => $mostMade ? [
                'id' => (int) $mostMade['id'],
                'title' => $mostMade['title'],
                'image_path' => $mostMade['image_path'],
                'cook_count' => (int) $mostMade['cook_count'],
            ] : null,
            'new_recipes_tried' => $newRecipesTried,
            'streak_peak' => $streakPeak,
            'top_tag' => $topTag ? [
                'name' => $topTag['name'],
                'count' => (int) $topTag['count'],
            ] : null,
        ];
    }

    /**
     * Get recipes the user has cooked multiple times but not recently.
     * Surfaces "forgotten favorites" — recipes they clearly like but haven't made in a while.
     */
    public function getForgottenFavorites(int $userId, int $daysSince = 60, int $limit = 5): array {
        $stmt = $this->db->prepare('
            SELECT r.id, r.title, r.image_path, r.prep_time, r.cook_time,
                   COUNT(*) AS times_cooked,
                   MAX(cl.cooked_at) AS last_cooked,
                   DATEDIFF(CURDATE(), MAX(cl.cooked_at)) AS days_since
            FROM cook_log cl
            INNER JOIN recipes r ON cl.recipe_id = r.id
            WHERE cl.user_id = ?
            GROUP BY cl.recipe_id
            HAVING times_cooked >= 2
               AND days_since > ?
            ORDER BY times_cooked DESC, days_since DESC
            LIMIT ?
        ');
        $stmt->execute([$userId, $daysSince, $limit]);
        return $stmt->fetchAll();
    }
}
