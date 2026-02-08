CREATE DATABASE IF NOT EXISTS smart_resume_job_finder;
USE smart_resume_job_finder;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) DEFAULT 'default.png',
    account_type ENUM('free', 'pro') DEFAULT 'free',
    subscription_end DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Resumes table
CREATE TABLE resumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    website VARCHAR(255),
    address TEXT,
    summary TEXT,
    photo VARCHAR(255), -- Profile photo filename
    education JSON, -- Store as JSON array [{school, degree, year}, ...]
    experience JSON, -- Store as JSON array [{company, role, duration, description}, ...]
    skills TEXT, -- Comma separated or JSON
    projects JSON,
    certifications JSON,
    template_id INT DEFAULT 1,
    bank_name VARCHAR(255) NULL,
    branch_name VARCHAR(255) NULL,
    acc_no VARCHAR(255) NULL,
    acc_name VARCHAR(255) NULL,
    extra_details JSON NULL, -- Store template-specific info as JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Job Categories table
CREATE TABLE job_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- Jobs table
CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    salary_range VARCHAR(100),
    description TEXT,
    requirements TEXT, -- Skills needed
    category_id INT, -- Foreign key to job_categories
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE SET NULL
);

-- Applications table
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    resume_id INT NOT NULL,
    status ENUM('Applied', 'Viewed', 'Shortlisted', 'Rejected') DEFAULT 'Applied',
    cover_letter TEXT,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
);

-- Favorites table
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

-- Initial Skills table (for auto-suggest)
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(100) NOT NULL UNIQUE
);

-- Insert some default admin
INSERT INTO admins (username, password) VALUES ('admin@gmail.com', '$2y$10$Wuj88WAmTbr.z.71KFLRweHU6P/uzLfgHDWW7Cem1BqvnCEaKQzH'); -- password: sams

-- Insert some default skills
INSERT INTO skills (skill_name) VALUES ('PHP'), ('JavaScript'), ('MySQL'), ('HTML'), ('CSS'), ('React'), ('Python'), ('Java'), ('Project Management');

-- Insert Job Categories
INSERT INTO job_categories (name) VALUES 
('IT-Sware/DB/QA/Web/Graphics/GIS'), ('IT-HWare/Networks/Systems'), ('Accounting/Auditing/Finance'), 
('Banking & Finance/Insurance'), ('Sales/Marketing/Merchandising'), ('HR/Training'), 
('Corporate Management/Analysts'), ('Office Admin/Secretary/Receptionist'), 
('Civil Eng/Interior Design/Architecture'), ('IT-Telecoms'), ('Customer Relations/Public Relations'), 
('Logistics/Warehouse/Transport'), ('Eng-Mech/Auto/Elec'), ('Manufacturing/Operations'), 
('Media/Advert/Communication'), ('Hotel/Restaurant/Hospitality'), ('Travel/Tourism'),
('Sports/Fitness/Recreation'), ('Hospital/Nursing/Healthcare'), ('Legal/Law'), 
('Supervision/Quality Control'), ('Apparel/Clothing'), ('Ticketing/Airline/Marine'), 
('Education'), ('R&D/Science/Research'), ('Agriculture/Dairy/Environment'), 
('Security'), ('Fashion/Design/Beauty'), ('International Development'), 
('KPO/BPO'), ('Imports/Exports'), ('Automotive/Supply Chain'), 
('Real Estate/Property Management'), ('Public Sector/Government'), ('Energy/Utilities'), 
('Content Writing/Journalism'), ('NGO/Social Services'), ('Data Science/Analytics'), 
('E-commerce/Digital Marketing'), ('Event Management/Entertainment'), ('Construction/Heavy Equipment');

-- Insert some dummy jobs
INSERT INTO jobs (title, company, location, salary_range, category_id, requirements, description) VALUES 
('Full Stack Developer', 'TechNova Solutions', 'Remote', '$60k - $90k', 1, 'PHP, JavaScript, MySQL, HTML, CSS', 'Looking for a passionate developer to join our growing team.'),
('Frontend Engineer', 'Creative Studio', 'New York, NY', '$80k - $110k', 1, 'React, JavaScript, HTML, CSS', 'Help us build beautiful and performant user interfaces.'),
('Database Administrator', 'DataSystems Inc', 'Austin, TX', '$70k - $100k', 1, 'MySQL, SQL, Database Design', 'Maintain and optimize our large scale databases.'),
('Project Manager', 'BuildIt Corp', 'Chicago, IL', '$90k - $120k', 7, 'Project Management, Communication, Java', 'Lead cross-functional teams to deliver high-quality projects.');
