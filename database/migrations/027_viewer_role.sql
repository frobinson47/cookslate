ALTER TABLE users
  MODIFY COLUMN role ENUM('admin', 'member', 'viewer') NOT NULL DEFAULT 'member';
