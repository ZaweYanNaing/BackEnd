-- Migration script to rename categories tables to dietary_preferences
-- Run this script to update the database schema

-- IMPORTANT: Run this migration script on your database before using the updated code
-- This script will rename the existing tables without losing any data

-- Step 1: Rename the main categories table
RENAME TABLE categories TO dietary_preferences;

-- Step 2: Rename the junction table
RENAME TABLE recipe_categories TO recipe_dietary_preferences;

-- Step 3: Verify the changes
SHOW TABLES LIKE '%dietary_preferences%';

-- Step 4: Check that foreign key constraints are still intact
SHOW CREATE TABLE recipe_dietary_preferences;

-- The migration is complete!
-- All existing data will be preserved and the application will now use the new table names.