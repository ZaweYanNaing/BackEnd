-- Migration to add image_url column to events table
-- Run this if you already have an events table without the image_url column

ALTER TABLE events ADD COLUMN image_url VARCHAR(500) NULL AFTER current_participants;

-- Verify the change
DESCRIBE events;