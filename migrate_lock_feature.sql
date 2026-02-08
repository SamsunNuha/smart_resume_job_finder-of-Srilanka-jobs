USE smart_resume_job_finder;

ALTER TABLE resumes 
ADD COLUMN IF NOT EXISTS is_locked TINYINT(1) DEFAULT 0 AFTER extra_details;
