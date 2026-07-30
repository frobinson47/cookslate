-- 024_pantry_expiration.sql
-- Optional expiration date on pantry items, for "Use It Soon" surfacing

ALTER TABLE pantry
  ADD COLUMN expiration_date DATE NULL AFTER unit;
