#!/bin/bash

# Weak Authentication Challenge Reset Script

echo "🔄 Resetting Weak Authentication Challenge..."

# Database connection details
DB_USER="root"
DB_PASS=""
DB_NAME="ctf_lab"

# Clear login attempts and reset accounts
mysql -u $DB_USER -p$DB_PASS $DB_NAME << EOF
DELETE FROM login_attempts;
DELETE FROM weak_accounts;
INSERT INTO weak_accounts (username, password, email, security_question, security_answer, role) VALUES 
('admin', '123456', 'admin@company.com', 'What is your favorite color?', 'blue', 'admin'),
('manager', 'password', 'manager@company.com', 'What is your pet\'s name?', 'fluffy', 'manager'),
('employee1', 'qwerty', 'emp1@company.com', 'What city were you born in?', 'london', 'user'),
('support', 'admin123', 'support@company.com', 'What is your mother\'s maiden name?', 'smith', 'support'),
('guest', 'guest', 'guest@company.com', 'What is 2+2?', '4', 'guest'),
('john.doe', 'john123', 'john@company.com', 'What is your favorite food?', 'pizza', 'user'),
('ceo', 'CEO2023!', 'ceo@company.com', 'What is the company name?', 'acmecorp', 'admin');
EOF

if [ $? -eq 0 ]; then
    echo "✅ Weak Authentication challenge reset successfully!"
    echo "📊 All accounts and login attempts have been reset"
    echo "🎯 You can now attempt the challenge again"
    echo ""
    echo "Challenge URL: http://localhost/challenges/easy/weak-auth/login.php"
    echo "Flag: CTF{W34k_P4ssw0rds_4r3_D4ng3r0us}"
    echo ""
    echo "💡 Try these weak credentials:"
    echo "   admin:123456 or manager:password"
else
    echo "❌ Failed to reset challenge. Please check your MySQL configuration."
fi
