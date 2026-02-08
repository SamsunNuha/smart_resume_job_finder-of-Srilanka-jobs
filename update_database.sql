-- SQL to add missing columns to the resumes table
USE smart_resume_job_finder;

ALTER TABLE resumes 
ADD COLUMN IF NOT EXISTS photo VARCHAR(255) AFTER summary,
ADD COLUMN IF NOT EXISTS bank_name VARCHAR(255) NULL AFTER template_id,
ADD COLUMN IF NOT EXISTS branch_name VARCHAR(255) NULL AFTER bank_name,
ADD COLUMN IF NOT EXISTS acc_no VARCHAR(255) NULL AFTER branch_name,
ADD COLUMN IF NOT EXISTS acc_name VARCHAR(255) NULL AFTER acc_no,
ADD COLUMN IF NOT EXISTS extra_details JSON NULL AFTER acc_name;

ALTER TABLE applications
ADD COLUMN IF NOT EXISTS cover_letter TEXT AFTER status,
ADD COLUMN IF NOT EXISTS form_responses JSON NULL AFTER cover_letter;

ALTER TABLE jobs
ADD COLUMN IF NOT EXISTS application_form JSON NULL AFTER requirements;

