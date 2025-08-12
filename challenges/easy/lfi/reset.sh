#!/bin/bash

# LFI Challenge Reset Script

echo "🔄 Resetting LFI Challenge..."

# Database connection details
DB_USER="root"
DB_PASS=""
DB_NAME="ctf_lab"

# Clear file access logs
mysql -u $DB_USER -p$DB_PASS $DB_NAME << EOF
DELETE FROM file_access_logs;
EOF

# Ensure config directory and secret file exist
mkdir -p "$(dirname "$0")/config"
cat > "$(dirname "$0")/config/secret.txt" << 'CONFIG_EOF'
# SECRET CONFIGURATION FILE
# DO NOT EXPOSE TO PUBLIC

DATABASE_PASSWORD=super_secret_db_pass
API_KEY=sk-1234567890abcdef
ADMIN_EMAIL=admin@ctflab.local

# FLAG FOR LFI CHALLENGE
CTF_FLAG=CTF{L0c4l_F1l3_1nclus10n_M4st3r}

# Internal server configuration
DEBUG_MODE=enabled
LOG_LEVEL=verbose
BACKUP_LOCATION=/var/backups/

# Security tokens
JWT_SECRET=very_secret_jwt_key_12345
ENCRYPTION_KEY=aes256_encryption_key_here
CONFIG_EOF

if [ $? -eq 0 ]; then
    echo "✅ LFI challenge reset successfully!"
    echo "📊 File access logs cleared and secret files restored"
    echo "🎯 You can now attempt the challenge again"
    echo ""
    echo "Challenge URL: http://localhost/challenges/easy/lfi/viewer.php"
    echo "Flag: CTF{L0c4l_F1l3_1nclus10n_M4st3r}"
    echo ""
    echo "💡 Try accessing: ../config/secret.txt"
else
    echo "❌ Failed to reset challenge. Please check your MySQL configuration."
fi
