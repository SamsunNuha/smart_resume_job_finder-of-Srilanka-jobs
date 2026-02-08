-- Run this SQL to add photo column to resumes table
-- Open phpMyAdmin or run this in your MySQL client

USE smart_resume_job_finder;

ALTER TABLE resumes ADD COLUMN photo VARCHAR(255) AFTER summary;
