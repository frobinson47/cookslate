-- 013_package_sizes.sql
-- Add package size info to ingredient_data for shoppable quantities

ALTER TABLE ingredient_data
  ADD COLUMN package_size DECIMAL(8,2) DEFAULT NULL,
  ADD COLUMN package_unit VARCHAR(50) DEFAULT NULL,
  ADD COLUMN package_description VARCHAR(100) DEFAULT NULL;

-- Seed package data for common ingredients
UPDATE ingredient_data SET package_size = 26, package_unit = 'oz', package_description = 'Container' WHERE name = 'salt';
UPDATE ingredient_data SET package_size = 16, package_unit = 'oz', package_description = '4-stick pack' WHERE name = 'butter';
UPDATE ingredient_data SET package_size = 5, package_unit = 'lb', package_description = 'Bag' WHERE name = 'flour';
UPDATE ingredient_data SET package_size = 4, package_unit = 'lb', package_description = 'Bag' WHERE name = 'sugar';
UPDATE ingredient_data SET package_size = 16, package_unit = 'oz', package_description = 'Bottle' WHERE name = 'olive oil';
UPDATE ingredient_data SET package_size = 48, package_unit = 'oz', package_description = 'Bottle' WHERE name = 'vegetable oil';
UPDATE ingredient_data SET package_size = 12, package_unit = 'count', package_description = 'Dozen' WHERE name = 'egg';
UPDATE ingredient_data SET package_size = 1, package_unit = 'gallon', package_description = 'Jug' WHERE name = 'milk';
UPDATE ingredient_data SET package_size = 1, package_unit = 'pint', package_description = 'Carton' WHERE name = 'heavy cream';
UPDATE ingredient_data SET package_size = 16, package_unit = 'oz', package_description = 'Tub' WHERE name = 'sour cream';
UPDATE ingredient_data SET package_size = 8, package_unit = 'oz', package_description = 'Package' WHERE name = 'cream cheese';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Block' WHERE name = 'cheddar cheese';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Ball/bag' WHERE name = 'mozzarella';
UPDATE ingredient_data SET package_size = 32, package_unit = 'oz', package_description = 'Container' WHERE name = 'yogurt';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Package' WHERE name = 'chicken breast';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Package' WHERE name = 'ground beef';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Package' WHERE name = 'bacon';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Fillet' WHERE name = 'salmon';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Bag' WHERE name = 'shrimp';
UPDATE ingredient_data SET package_size = 2, package_unit = 'lb', package_description = 'Bag' WHERE name = 'rice';
UPDATE ingredient_data SET package_size = 1, package_unit = 'lb', package_description = 'Box' WHERE name = 'pasta';
UPDATE ingredient_data SET package_size = 1, package_unit = 'loaf', package_description = 'Loaf' WHERE name = 'bread';
UPDATE ingredient_data SET package_size = 32, package_unit = 'oz', package_description = 'Carton' WHERE name = 'chicken broth';
UPDATE ingredient_data SET package_size = 14.5, package_unit = 'oz', package_description = 'Can' WHERE name = 'diced tomatoes';
UPDATE ingredient_data SET package_size = 6, package_unit = 'oz', package_description = 'Can' WHERE name = 'tomato paste';
UPDATE ingredient_data SET package_size = 13.5, package_unit = 'oz', package_description = 'Can' WHERE name = 'coconut milk';
UPDATE ingredient_data SET package_size = 15, package_unit = 'oz', package_description = 'Can' WHERE name = 'black beans';
UPDATE ingredient_data SET package_size = 15, package_unit = 'oz', package_description = 'Bottle' WHERE name = 'soy sauce';
