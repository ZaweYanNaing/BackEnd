-- Add account lockout columns to users table
-- This migration adds the necessary columns for implementing account lockout functionality

ALTER TABLE users 
ADD COLUMN failed_login_attempts INT DEFAULT 0,
ADD COLUMN last_failed_login TIMESTAMP NULL,
ADD COLUMN account_locked_until TIMESTAMP NULL;

-- Add index for performance
CREATE INDEX idx_users_lockout ON users(email, account_locked_until);
