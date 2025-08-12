-- SQL Injection Challenge Setup
-- Creates a simple vulnerable login system

CREATE DATABASE IF NOT EXISTS ctf_lab;
USE ctf_lab;

-- Users table with admin credentials
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert test data
INSERT INTO users (username, password, role) VALUES 
('admin', 'sup3r_s3cur3_p@ssw0rd!', 'admin'),
('user1', 'password123', 'user'),
('guest', 'guest', 'user'),
('testuser', 'test123', 'user');

-- Flags table 
CREATE TABLE IF NOT EXISTS flags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    challenge_name VARCHAR(50) NOT NULL,
    flag_value VARCHAR(100) NOT NULL,
    points INT DEFAULT 100
);

-- Insert the flag for this challenge
INSERT INTO flags (challenge_name, flag_value, points) VALUES 
('sql-injection-easy', 'CTF{SQL_1nj3ct10n_B4s1cs_M4st3r3d}', 100);

-- Create a view that shows sensitive data (flag location hint)
CREATE VIEW admin_panel AS 
SELECT u.username, u.role, f.flag_value, f.points 
FROM users u 
CROSS JOIN flags f 
WHERE u.role = 'admin' AND f.challenge_name = 'sql-injection-easy';
