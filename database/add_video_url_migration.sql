-- Migration to add video_url column to recipes table
-- Run this if you have an existing database without the video_url column

ALTER TABLE recipes ADD COLUMN video_url VARCHAR(500) AFTER image_url;
