-- 025_recipe_cost_cache.sql
-- Cache the estimated per-serving cost on the recipe row itself, recomputed
-- on create/update, so the recipe grid can show a cost badge without
-- re-running the (expensive, per-ingredient-lookup) RecipeAnalyzer on every
-- list request.

ALTER TABLE recipes
  ADD COLUMN estimated_cost_per_serving DECIMAL(10,2) NULL;
