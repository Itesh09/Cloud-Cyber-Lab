-- LFI Challenge Setup
-- Creates file viewing system for LFI demonstration

USE ctf_lab;

-- Add flag for this challenge
INSERT INTO flags (challenge_name, flag_value, points) VALUES 
('lfi-easy', 'CTF{L0c4l_F1l3_1nclus10n_M4st3r}', 175)
ON DUPLICATE KEY UPDATE flag_value = 'CTF{L0c4l_F1l3_1nclus10n_M4st3r}', points = 175;

-- Document access logs
CREATE TABLE IF NOT EXISTS file_access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requested_file VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT TRUE
);
