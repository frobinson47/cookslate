ALTER TABLE grocery_lists
  ADD COLUMN week_start DATE NULL AFTER created_by,
  ADD UNIQUE KEY uniq_user_week (created_by, week_start);
