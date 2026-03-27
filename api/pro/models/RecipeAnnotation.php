<?php

require_once __DIR__ . '/../../models/Database.php';

class RecipeAnnotation {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all annotations for a recipe by a specific user.
     */
    public function getForRecipe(int $recipeId, int $userId): array {
        $stmt = $this->db->prepare('
            SELECT id, target_type, target_index, note, created_at, updated_at
            FROM recipe_annotations
            WHERE recipe_id = ? AND user_id = ?
            ORDER BY target_type, target_index
        ');
        $stmt->execute([$recipeId, $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Create or update an annotation (upsert).
     */
    public function upsert(int $recipeId, int $userId, string $targetType, int $targetIndex, string $note): array {
        $stmt = $this->db->prepare('
            INSERT INTO recipe_annotations (recipe_id, user_id, target_type, target_index, note)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE note = VALUES(note), updated_at = CURRENT_TIMESTAMP
        ');
        $stmt->execute([$recipeId, $userId, $targetType, $targetIndex, $note]);

        // Fetch the upserted row
        $fetch = $this->db->prepare('
            SELECT id, target_type, target_index, note, created_at, updated_at
            FROM recipe_annotations
            WHERE recipe_id = ? AND user_id = ? AND target_type = ? AND target_index = ?
        ');
        $fetch->execute([$recipeId, $userId, $targetType, $targetIndex]);
        return $fetch->fetch();
    }

    /**
     * Delete an annotation.
     */
    public function delete(int $recipeId, int $userId, string $targetType, int $targetIndex): bool {
        $stmt = $this->db->prepare('
            DELETE FROM recipe_annotations
            WHERE recipe_id = ? AND user_id = ? AND target_type = ? AND target_index = ?
        ');
        $stmt->execute([$recipeId, $userId, $targetType, $targetIndex]);
        return $stmt->rowCount() > 0;
    }
}
