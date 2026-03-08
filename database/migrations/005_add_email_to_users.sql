-- Add email column to users table
ALTER TABLE users ADD COLUMN email VARCHAR(255) DEFAULT NULL AFTER username;
