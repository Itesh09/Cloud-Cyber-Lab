-- Weak Authentication Challenge Setup
-- Creates accounts with weak passwords and security questions

USE ctf_lab;

-- Weak accounts table
CREATE TABLE IF NOT EXISTS weak_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    security_question VARCHAR(255) NOT NULL,
    security_answer VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert accounts with predictably weak credentials
INSERT INTO weak_accounts (username, password, email, security_question, security_answer, role) VALUES 
('admin', '123456', 'admin@company.com', 'What is your favorite color?', 'blue', 'admin'),
('manager', 'password', 'manager@company.com', 'What is your pet\'s name?', 'fluffy', 'manager'),
('employee1', 'qwerty', 'emp1@company.com', 'What city were you born in?', 'london', 'user'),
('support', 'admin123', 'support@company.com', 'What is your mother\'s maiden name?', 'smith', 'support'),
('guest', 'guest', 'guest@company.com', 'What is 2+2?', '4', 'guest'),
('john.doe', 'john123', 'john@company.com', 'What is your favorite food?', 'pizza', 'user'),
('ceo', 'CEO2023!', 'ceo@company.com', 'What is the company name?', 'acmecorp', 'admin');

-- Add flag for this challenge
INSERT INTO flags (challenge_name, flag_value, points) VALUES 
('weak-auth-easy', 'CTF{W34k_P4ssw0rds_4r3_D4ng3r0us}', 125)
ON DUPLICATE KEY UPDATE flag_value = 'CTF{W34k_P4ssw0rds_4r3_D4ng3r0us}', points = 125;

-- Failed login attempts tracking
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    attempted_password VARCHAR(100),
    ip_address VARCHAR(45),
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT FALSE
);
