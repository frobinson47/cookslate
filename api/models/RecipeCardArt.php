<?php

require_once __DIR__ . '/Database.php';

class RecipeCardArt {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find(int $recipeId, string $template): ?array {
        $stmt = $this->db->prepare('SELECT * FROM recipe_card_art WHERE recipe_id = ? AND template = ?');
        $stmt->execute([$recipeId, $template]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * All card art generated so far for a recipe (up to one per template).
     */
    public function findAllForRecipe(int $recipeId): array {
        $stmt = $this->db->prepare('SELECT template, image_path, created_at FROM recipe_card_art WHERE recipe_id = ? ORDER BY created_at ASC');
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll();
    }

    public function save(int $recipeId, string $template, string $imagePath): void {
        $stmt = $this->db->prepare(
            'INSERT INTO recipe_card_art (recipe_id, template, image_path) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE image_path = VALUES(image_path), created_at = CURRENT_TIMESTAMP'
        );
        $stmt->execute([$recipeId, $template, $imagePath]);
    }
}
