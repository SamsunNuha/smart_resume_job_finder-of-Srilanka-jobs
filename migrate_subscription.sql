-- Run this SQL to add subscription columns to users table
USE smart_resume_job_finder;

ALTER TABLE users 
ADD COLUMN account_type ENUM('free', 'pro') DEFAULT 'free' AFTER profile_pic,
ADD COLUMN subscription_end DATETIME NULL AFTER account_type;
