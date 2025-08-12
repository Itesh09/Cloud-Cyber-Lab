#!/bin/bash

# Stored XSS Challenge Reset Script

echo "🔄 Resetting Stored XSS Challenge..."

# Database connection details
DB_USER="root"
DB_PASS=""
DB_NAME="ctf_lab"

# Clear existing comments and reset
mysql -u $DB_USER -p$DB_PASS $DB_NAME << EOF
DELETE FROM comments;
INSERT INTO comments (username, email, comment) VALUES 
('Alice', 'alice@example.com', 'Great website! Keep up the good work.'),
('Bob', 'bob@example.com', 'I love the design. Very professional!'),
('Charlie', 'charlie@example.com', 'Thanks for sharing this resource.'),
('Admin', 'admin@ctflab.local', 'Welcome to our guestbook! Please be respectful in your comments.');
EOF

if [ $? -eq 0 ]; then
    echo "✅ Stored XSS challenge reset successfully!"
    echo "📊 Guestbook has been cleared and restored to initial state"
    echo "🎯 You can now attempt the challenge again"
    echo ""
    echo "Challenge URL: http://localhost/challenges/easy/stored-xss/guestbook.php"
    echo "Flag: CTF{St0r3d_XSS_C00k13_St34l3r}"
else
    echo "❌ Failed to reset challenge. Please check your MySQL configuration."
fi
