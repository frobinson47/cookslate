-- 022_pantry_quantity.sql
-- Add quantity tracking to pantry, so receipt scanning can prefill real amounts

ALTER TABLE pantry
  ADD COLUMN quantity DECIMAL(10,2) NULL AFTER ingredient_name,
  ADD COLUMN unit VARCHAR(32) NULL AFTER quantity;
