CREATE TABLE meal_plan_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE meal_plan_template_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    recipe_id INT NOT NULL,
    day_of_week TINYINT NOT NULL,
    meal_type VARCHAR(20) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    servings_override INT NULL,
    FOREIGN KEY (template_id) REFERENCES meal_plan_templates(id) ON DELETE CASCADE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);
