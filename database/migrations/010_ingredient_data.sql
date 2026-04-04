-- Ingredient reference data: prices, nutrition, and store info
CREATE TABLE IF NOT EXISTS ingredient_data (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL UNIQUE,
  category VARCHAR(100) DEFAULT NULL,
  avg_price DECIMAL(8,2) DEFAULT NULL,
  price_unit VARCHAR(50) DEFAULT NULL,
  calories_per_100g DECIMAL(8,1) DEFAULT NULL,
  protein_per_100g DECIMAL(8,1) DEFAULT NULL,
  carbs_per_100g DECIMAL(8,1) DEFAULT NULL,
  fat_per_100g DECIMAL(8,1) DEFAULT NULL,
  fiber_per_100g DECIMAL(8,1) DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE INDEX idx_ingredient_data_name ON ingredient_data(name);

-- Seed with common ingredients (US average prices, USDA nutrition data)
INSERT IGNORE INTO ingredient_data (name, category, avg_price, price_unit, calories_per_100g, protein_per_100g, carbs_per_100g, fat_per_100g, fiber_per_100g) VALUES
-- Produce
('onion', 'Produce', 1.25, 'lb', 40, 1.1, 9.3, 0.1, 1.7),
('garlic', 'Produce', 0.50, 'head', 149, 6.4, 33.1, 0.5, 2.1),
('potato', 'Produce', 1.00, 'lb', 77, 2.0, 17.5, 0.1, 2.2),
('tomato', 'Produce', 2.00, 'lb', 18, 0.9, 3.9, 0.2, 1.2),
('carrot', 'Produce', 1.25, 'lb', 41, 0.9, 9.6, 0.2, 2.8),
('celery', 'Produce', 1.99, 'bunch', 16, 0.7, 3.0, 0.2, 1.6),
('bell pepper', 'Produce', 1.50, 'each', 31, 1.0, 6.0, 0.3, 2.1),
('broccoli', 'Produce', 2.00, 'lb', 34, 2.8, 6.6, 0.4, 2.6),
('spinach', 'Produce', 2.50, 'bag', 23, 2.9, 3.6, 0.4, 2.2),
('lemon', 'Produce', 0.50, 'each', 29, 1.1, 9.3, 0.3, 2.8),
('lime', 'Produce', 0.33, 'each', 30, 0.7, 10.5, 0.2, 2.8),
('avocado', 'Produce', 1.50, 'each', 160, 2.0, 8.5, 14.7, 6.7),
('mushroom', 'Produce', 3.00, 'lb', 22, 3.1, 3.3, 0.3, 1.0),
('cucumber', 'Produce', 1.00, 'each', 15, 0.7, 3.6, 0.1, 0.5),
('zucchini', 'Produce', 1.50, 'lb', 17, 1.2, 3.1, 0.3, 1.0),
('cilantro', 'Produce', 0.99, 'bunch', 23, 2.1, 3.7, 0.5, 2.8),
('parsley', 'Produce', 0.99, 'bunch', 36, 3.0, 6.3, 0.8, 3.3),
('ginger', 'Produce', 4.00, 'lb', 80, 1.8, 17.8, 0.8, 2.0),
('jalapeño', 'Produce', 1.50, 'lb', 29, 0.9, 6.5, 0.4, 2.8),

-- Meat & Seafood
('chicken breast', 'Meat & Seafood', 3.99, 'lb', 165, 31.0, 0.0, 3.6, 0.0),
('chicken thigh', 'Meat & Seafood', 2.99, 'lb', 209, 26.0, 0.0, 10.9, 0.0),
('ground beef', 'Meat & Seafood', 5.99, 'lb', 254, 17.2, 0.0, 20.0, 0.0),
('bacon', 'Meat & Seafood', 6.99, 'lb', 541, 37.0, 1.4, 42.0, 0.0),
('sausage', 'Meat & Seafood', 4.99, 'lb', 301, 12.0, 2.0, 27.0, 0.0),
('salmon', 'Meat & Seafood', 9.99, 'lb', 208, 20.4, 0.0, 13.4, 0.0),
('shrimp', 'Meat & Seafood', 8.99, 'lb', 99, 24.0, 0.2, 0.3, 0.0),
('ground turkey', 'Meat & Seafood', 4.99, 'lb', 170, 21.0, 0.0, 9.4, 0.0),
('pork chop', 'Meat & Seafood', 4.49, 'lb', 231, 25.7, 0.0, 13.5, 0.0),
('steak', 'Meat & Seafood', 12.99, 'lb', 271, 26.0, 0.0, 18.0, 0.0),

-- Dairy & Eggs
('egg', 'Dairy & Eggs', 3.50, 'dozen', 155, 13.0, 1.1, 11.0, 0.0),
('butter', 'Dairy & Eggs', 4.99, 'lb', 717, 0.9, 0.1, 81.0, 0.0),
('milk', 'Dairy & Eggs', 3.99, 'gallon', 61, 3.2, 4.8, 3.3, 0.0),
('heavy cream', 'Dairy & Eggs', 4.99, 'pint', 340, 2.1, 2.8, 36.1, 0.0),
('sour cream', 'Dairy & Eggs', 2.49, '16oz', 198, 2.4, 4.6, 19.4, 0.0),
('cream cheese', 'Dairy & Eggs', 2.99, '8oz', 342, 6.2, 5.5, 34.2, 0.0),
('cheddar cheese', 'Dairy & Eggs', 5.99, 'lb', 403, 24.9, 1.3, 33.1, 0.0),
('mozzarella', 'Dairy & Eggs', 4.99, 'lb', 280, 28.0, 3.1, 17.1, 0.0),
('parmesan', 'Dairy & Eggs', 8.99, 'lb', 431, 38.5, 4.1, 28.6, 0.0),
('yogurt', 'Dairy & Eggs', 3.99, '32oz', 59, 10.0, 3.6, 0.4, 0.0),

-- Pantry & Baking
('flour', 'Baking', 3.49, '5lb', 364, 10.3, 76.3, 1.0, 2.7),
('sugar', 'Baking', 3.99, '4lb', 387, 0.0, 100.0, 0.0, 0.0),
('brown sugar', 'Baking', 3.49, '2lb', 380, 0.1, 98.1, 0.0, 0.0),
('baking powder', 'Baking', 2.99, '8oz', 53, 0.0, 27.7, 0.0, 0.0),
('baking soda', 'Baking', 1.29, '16oz', 0, 0.0, 0.0, 0.0, 0.0),
('vanilla extract', 'Baking', 7.99, '4oz', 288, 0.1, 12.7, 0.1, 0.0),
('cocoa powder', 'Baking', 4.99, '8oz', 228, 19.6, 57.9, 13.7, 33.2),
('chocolate chips', 'Baking', 3.49, '12oz', 502, 5.5, 60.5, 30.2, 5.9),

-- Oils & Vinegars
('olive oil', 'Oils & Vinegars', 6.99, '16oz', 884, 0.0, 0.0, 100.0, 0.0),
('vegetable oil', 'Oils & Vinegars', 3.99, '48oz', 884, 0.0, 0.0, 100.0, 0.0),
('apple cider vinegar', 'Oils & Vinegars', 3.99, '16oz', 21, 0.0, 0.9, 0.0, 0.0),

-- Condiments
('soy sauce', 'Condiments & Sauces', 2.99, '15oz', 53, 8.1, 4.9, 0.0, 0.8),
('hot sauce', 'Condiments & Sauces', 2.49, '5oz', 11, 0.5, 1.8, 0.4, 0.7),
('ketchup', 'Condiments & Sauces', 2.99, '20oz', 112, 1.7, 29.3, 0.1, 0.3),
('mustard', 'Condiments & Sauces', 1.99, '8oz', 66, 4.4, 5.3, 3.3, 3.3),
('mayo', 'Condiments & Sauces', 3.99, '30oz', 680, 1.0, 0.6, 75.0, 0.0),
('worcestershire sauce', 'Condiments & Sauces', 3.49, '10oz', 78, 0.0, 19.5, 0.0, 0.0),

-- Pasta & Grains
('rice', 'Pasta & Grains', 2.99, '2lb', 360, 7.1, 79.3, 0.7, 1.3),
('pasta', 'Pasta & Grains', 1.49, 'lb', 371, 13.0, 74.7, 1.5, 3.2),
('bread', 'Bakery', 2.99, 'loaf', 265, 9.4, 49.0, 3.2, 2.7),

-- Canned
('chicken broth', 'Canned Goods', 2.49, '32oz', 5, 0.5, 0.4, 0.1, 0.0),
('diced tomatoes', 'Canned Goods', 1.29, '14.5oz', 17, 0.8, 3.5, 0.1, 1.0),
('tomato paste', 'Canned Goods', 1.29, '6oz', 82, 4.3, 18.9, 0.5, 4.1),
('coconut milk', 'Canned Goods', 2.49, '13.5oz', 230, 2.3, 5.5, 23.8, 0.0),
('black beans', 'Canned Goods', 1.09, '15oz', 132, 8.9, 23.7, 0.5, 8.7),

-- Spices
('salt', 'Spices & Seasonings', 1.49, '26oz', 0, 0.0, 0.0, 0.0, 0.0),
('black pepper', 'Spices & Seasonings', 4.99, '4oz', 251, 10.4, 63.9, 3.3, 25.3),
('cumin', 'Spices & Seasonings', 3.99, '2oz', 375, 17.8, 44.2, 22.3, 10.5),
('paprika', 'Spices & Seasonings', 3.49, '2oz', 282, 14.1, 53.9, 13.0, 34.9),
('cinnamon', 'Spices & Seasonings', 3.49, '2oz', 247, 4.0, 80.6, 1.2, 53.1),
('chili powder', 'Spices & Seasonings', 3.49, '2oz', 282, 12.3, 49.7, 14.3, 34.8),
('garlic powder', 'Spices & Seasonings', 3.49, '3oz', 331, 16.6, 72.7, 0.7, 9.0),
('onion powder', 'Spices & Seasonings', 3.49, '3oz', 341, 10.4, 79.1, 1.0, 15.2),
('italian seasoning', 'Spices & Seasonings', 3.49, '1oz', 250, 9.0, 47.0, 6.0, 18.0),
('red pepper flakes', 'Spices & Seasonings', 3.49, '2oz', 318, 12.0, 56.6, 17.3, 27.2);
