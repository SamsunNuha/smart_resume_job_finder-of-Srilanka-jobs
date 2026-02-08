-- Run this SQL to add bank detail columns to resumes table
USE smart_resume_job_finder;

ALTER TABLE resumes 
ADD COLUMN bank_name VARCHAR(255) NULL AFTER template_id,
ADD COLUMN branch_name VARCHAR(255) NULL AFTER bank_name,
ADD COLUMN acc_no VARCHAR(255) NULL AFTER branch_name,
ADD COLUMN acc_name VARCHAR(255) NULL AFTER acc_no;
