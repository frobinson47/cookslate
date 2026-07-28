-- 023_shopping_trips.sql
-- Shopping trip / spending history, populated by receipt scanning

CREATE TABLE IF NOT EXISTS shopping_trips (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
  store_name VARCHAR(255) NULL,
  trip_date DATE NULL,
  total_amount DECIMAL(10,2) NULL,
  receipt_image_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS shopping_trip_items (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  trip_id INTEGER NOT NULL,
  item_name VARCHAR(255) NOT NULL,
  quantity DECIMAL(10,2) NULL,
  unit VARCHAR(32) NULL,
  price DECIMAL(10,2) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (trip_id) REFERENCES shopping_trips(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_shopping_trip_items_trip_id ON shopping_trip_items(trip_id);
