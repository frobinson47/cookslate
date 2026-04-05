-- 012_pantry.sql
-- Pantry tracking: per-user "always stocked" ingredients + in_pantry flag on grocery items

CREATE TABLE IF NOT EXISTS pantry (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
  ingredient_name VARCHAR(255) NOT NULL,
  always_stocked BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_pantry (user_id, ingredient_name),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE grocery_items
  ADD COLUMN in_pantry BOOLEAN DEFAULT FALSE;
