-- Stored XSS Challenge Setup
-- Creates a vulnerable guestbook/comment system

USE ctf_lab;

-- Comments table for storing XSS payloads
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample comments
INSERT INTO comments (username, email, comment) VALUES 
('Alice', 'alice@example.com', 'Great website! Keep up the good work.'),
('Bob', 'bob@example.com', 'I love the design. Very professional!'),
('Charlie', 'charlie@example.com', 'Thanks for sharing this resource.'),
('Admin', 'admin@ctflab.local', 'Welcome to our guestbook! Please be respectful in your comments.');

-- Add flag for this challenge
INSERT INTO flags (challenge_name, flag_value, points) VALUES 
('stored-xss-easy', 'CTF{St0r3d_XSS_C00k13_St34l3r}', 150)
ON DUPLICATE KEY UPDATE flag_value = 'CTF{St0r3d_XSS_C00k13_St34l3r}', points = 150;

-- Admin session table (simulates admin visiting the page)
CREATE TABLE IF NOT EXISTS admin_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_token VARCHAR(255) NOT NULL,
    admin_cookie VARCHAR(255) DEFAULT 'admin_secret_cookie=flag_here_CTF{St0r3d_XSS_C00k13_St34l3r}',
    last_visit TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin session
INSERT INTO admin_sessions (session_token, admin_cookie) VALUES 
('admin_session_12345', 'admin_secret_cookie=CTF{St0r3d_XSS_C00k13_St34l3r}; HttpOnly=false');
