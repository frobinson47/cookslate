<?php

require_once __DIR__ . '/../../middleware/Auth.php';
require_once __DIR__ . '/../../models/Database.php';

class StatsController {
    /**
     * GET /stats
     * Personal cooking statistics for the authenticated user.
     */
    public function index(): array {
        $userId = Auth::requireAuth();
        $db = Database::getInstance();

        // Total cooks and unique recipes cooked
        $stmt = $db->prepare('
            SELECT COUNT(*) AS total_cooks,
                   COUNT(DISTINCT recipe_id) AS unique_recipes
            FROM cook_log
            WHERE user_id = ?
        ');
        $stmt->execute([$userId]);
        $cookStats = $stmt->fetch();

        // Top 5 most cooked recipes
        $stmt = $db->prepare('
            SELECT r.id, r.title, r.image_path, COUNT(*) AS cook_count
            FROM cook_log cl
            INNER JOIN recipes r ON cl.recipe_id = r.id
            WHERE cl.user_id = ?
            GROUP BY r.id, r.title, r.image_path
            ORDER BY cook_count DESC
            LIMIT 5
        ');
        $stmt->execute([$userId]);
        $topRecipes = $stmt->fetchAll();
        $mostCooked = !empty($topRecipes) ? $topRecipes[0] : null;

        // Total time spent cooking (estimated from recipe times)
        $stmt = $db->prepare('
            SELECT COALESCE(SUM(COALESCE(r.prep_time, 0) + COALESCE(r.cook_time, 0)), 0) AS total_minutes
            FROM cook_log cl
            INNER JOIN recipes r ON cl.recipe_id = r.id
            WHERE cl.user_id = ?
        ');
        $stmt->execute([$userId]);
        $totalMinutes = (int) $stmt->fetchColumn();

        // Top tags (most cooked categories)
        $stmt = $db->prepare('
            SELECT t.name, COUNT(*) AS count
            FROM cook_log cl
            INNER JOIN recipe_tags rt ON cl.recipe_id = rt.recipe_id
            INNER JOIN tags t ON rt.tag_id = t.id
            WHERE cl.user_id = ?
            GROUP BY t.name
            ORDER BY count DESC
            LIMIT 5
        ');
        $stmt->execute([$userId]);
        $topTags = $stmt->fetchAll();

        // Recipes in collection
        $stmt = $db->prepare('SELECT COUNT(*) FROM recipes WHERE created_by = ?');
        $stmt->execute([$userId]);
        $recipesOwned = (int) $stmt->fetchColumn();

        // Favorites count
        $stmt = $db->prepare('SELECT COUNT(*) FROM favorites WHERE user_id = ?');
        $stmt->execute([$userId]);
        $favoritesCount = (int) $stmt->fetchColumn();

        // Average rating given
        $stmt = $db->prepare('SELECT ROUND(AVG(score), 1) FROM ratings WHERE user_id = ?');
        $stmt->execute([$userId]);
        $avgRating = $stmt->fetchColumn();

        // Cooking streak (consecutive days ending today or yesterday)
        $stmt = $db->prepare('
            SELECT DISTINCT DATE(cooked_at) AS cook_date
            FROM cook_log
            WHERE user_id = ?
            ORDER BY cook_date DESC
            LIMIT 60
        ');
        $stmt->execute([$userId]);
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $streak = 0;
        if (!empty($dates)) {
            $today = new DateTime('today');
            $checkDate = new DateTime($dates[0]);
            $diff = $today->diff($checkDate)->days;
            if ($diff <= 1) {
                $streak = 1;
                for ($i = 1; $i < count($dates); $i++) {
                    $prev = new DateTime($dates[$i - 1]);
                    $curr = new DateTime($dates[$i]);
                    if ($prev->diff($curr)->days === 1) {
                        $streak++;
                    } else {
                        break;
                    }
                }
            }
        }

        // Monthly activity (last 12 months)
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(cooked_at, '%Y-%m') AS month, COUNT(*) AS count
            FROM cook_log
            WHERE user_id = ? AND cooked_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY month
            ORDER BY month ASC
        ");
        $stmt->execute([$userId]);
        $monthlyActivity = $stmt->fetchAll();

        // Busiest day of week
        $stmt = $db->prepare("
            SELECT DAYNAME(cooked_at) AS day_name, COUNT(*) AS count
            FROM cook_log
            WHERE user_id = ?
            GROUP BY day_name
            ORDER BY count DESC
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $busiestDay = $stmt->fetch();

        // New recipes tried this year (first cook of each recipe in current year)
        $stmt = $db->prepare("
            SELECT COUNT(DISTINCT recipe_id) AS new_recipes
            FROM cook_log
            WHERE user_id = ?
              AND YEAR(cooked_at) = YEAR(CURDATE())
              AND recipe_id NOT IN (
                  SELECT recipe_id FROM cook_log
                  WHERE user_id = ? AND YEAR(cooked_at) < YEAR(CURDATE())
              )
        ");
        $stmt->execute([$userId, $userId]);
        $newRecipesThisYear = (int) $stmt->fetchColumn();

        // Weekly heatmap: day-of-week breakdown with counts
        $stmt = $db->prepare("
            SELECT DAYOFWEEK(cooked_at) AS dow, COUNT(*) AS count
            FROM cook_log
            WHERE user_id = ?
            GROUP BY dow
            ORDER BY dow
        ");
        $stmt->execute([$userId]);
        $weekdayRows = $stmt->fetchAll();
        $weekdayCounts = array_fill(1, 7, 0); // 1=Sun..7=Sat
        foreach ($weekdayRows as $row) {
            $weekdayCounts[(int)$row['dow']] = (int)$row['count'];
        }

        return [
            'total_cooks' => (int) $cookStats['total_cooks'],
            'unique_recipes' => (int) $cookStats['unique_recipes'],
            'recipes_owned' => $recipesOwned,
            'favorites_count' => $favoritesCount,
            'total_minutes' => $totalMinutes,
            'avg_rating' => $avgRating !== null ? (float) $avgRating : null,
            'streak' => $streak,
            'most_cooked' => $mostCooked ? [
                'id' => (int) $mostCooked['id'],
                'title' => $mostCooked['title'],
                'image_path' => $mostCooked['image_path'],
                'cook_count' => (int) $mostCooked['cook_count'],
            ] : null,
            'top_recipes' => array_map(fn($r) => [
                'id' => (int) $r['id'],
                'title' => $r['title'],
                'image_path' => $r['image_path'],
                'cook_count' => (int) $r['cook_count'],
            ], $topRecipes),
            'top_tags' => array_map(fn($t) => [
                'name' => $t['name'],
                'count' => (int) $t['count'],
            ], $topTags),
            'monthly_activity' => array_map(fn($m) => [
                'month' => $m['month'],
                'count' => (int) $m['count'],
            ], $monthlyActivity),
            'busiest_day' => $busiestDay ? [
                'day' => $busiestDay['day_name'],
                'count' => (int) $busiestDay['count'],
            ] : null,
            'new_recipes_this_year' => $newRecipesThisYear,
            'weekday_counts' => $weekdayCounts,
        ];
    }
}
