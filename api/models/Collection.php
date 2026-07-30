<?php

require_once __DIR__ . '/Database.php';

class Collection {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * All collections visible to the household (every user on this instance),
     * not just the caller's own. $userId is used to flag which ones the
     * caller owns (and can therefore edit/delete).
     */
    public function getAllForUser(int $userId): array {
        $stmt = $this->db->prepare('
            SELECT c.id, c.name, c.description, c.created_at, c.created_by,
                   u.username AS created_by_username,
                   (c.created_by = ?) AS is_owner,
                   (SELECT COUNT(*) FROM recipe_collections rc WHERE rc.collection_id = c.id) AS recipe_count
            FROM collections c
            INNER JOIN users u ON u.id = c.created_by
            ORDER BY c.name ASC
            LIMIT 200
        ');
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$row) {
            $row['is_owner'] = (bool) $row['is_owner'];
        }
        return $rows;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('
            SELECT c.*, u.username AS created_by_username
            FROM collections c
            INNER JOIN users u ON u.id = c.created_by
            WHERE c.id = ?
        ');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $name, int $userId, ?string $description = null): array {
        $stmt = $this->db->prepare('INSERT INTO collections (name, description, created_by) VALUES (?, ?, ?)');
        $stmt->execute([$name, $description, $userId]);
        $id = (int) $this->db->lastInsertId();
        return $this->findById($id);
    }

    public function update(int $id, string $name, ?string $description = null): array {
        $stmt = $this->db->prepare('UPDATE collections SET name = ?, description = ? WHERE id = ?');
        $stmt->execute([$name, $description, $id]);
        return $this->findById($id);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM collections WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function isOwner(int $id, int $userId): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM collections WHERE id = ? AND created_by = ?');
        $stmt->execute([$id, $userId]);
        return (bool) $stmt->fetch();
    }

    public function addRecipe(int $collectionId, int $recipeId): void {
        $stmt = $this->db->prepare('INSERT IGNORE INTO recipe_collections (recipe_id, collection_id) VALUES (?, ?)');
        $stmt->execute([$recipeId, $collectionId]);
    }

    /**
     * Add multiple recipes to a collection in one go. Idempotent per recipe.
     */
    public function addRecipesBulk(int $collectionId, array $recipeIds): void {
        $stmt = $this->db->prepare('INSERT IGNORE INTO recipe_collections (recipe_id, collection_id) VALUES (?, ?)');
        foreach ($recipeIds as $recipeId) {
            $stmt->execute([(int) $recipeId, $collectionId]);
        }
    }

    public function removeRecipe(int $collectionId, int $recipeId): void {
        $stmt = $this->db->prepare('DELETE FROM recipe_collections WHERE recipe_id = ? AND collection_id = ?');
        $stmt->execute([$recipeId, $collectionId]);
    }

    public function getRecipes(int $collectionId, int $limit = 500): array {
        $stmt = $this->db->prepare('
            SELECT r.id, r.title, r.description, r.image_path, r.servings, r.prep_time, r.cook_time,
                   r.created_at, r.calories
            FROM recipes r
            INNER JOIN recipe_collections rc ON r.id = rc.recipe_id
            WHERE rc.collection_id = ?
            ORDER BY rc.added_at DESC
            LIMIT ?
        ');
        $stmt->execute([$collectionId, $limit]);
        return $stmt->fetchAll();
    }

    public function getCollectionsForRecipe(int $recipeId): array {
        $stmt = $this->db->prepare('
            SELECT c.id, c.name
            FROM collections c
            INNER JOIN recipe_collections rc ON c.id = rc.collection_id
            WHERE rc.recipe_id = ?
        ');
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll();
    }
}
