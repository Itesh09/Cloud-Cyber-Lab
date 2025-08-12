#!/bin/bash

# SQL Injection Challenge Reset Script
# This script resets the database to initial state

echo "🔄 Resetting SQL Injection Challenge..."

# Database connection details
DB_USER="root"
DB_PASS=""  # Adjust as needed
DB_NAME="ctf_lab"

# Run the setup SQL script
mysql -u $DB_USER -p$DB_PASS < setup.sql

if [ $? -eq 0 ]; then
    echo "✅ Challenge reset successfully!"
    echo "📊 Database '$DB_NAME' has been restored to initial state"
    echo "🎯 You can now attempt the challenge again"
    echo ""
    echo "Challenge URL: http://localhost/challenges/easy/sql-injection/login.php"
    echo "Flag: CTF{SQL_1nj3ct10n_B4s1cs_M4st3r3d}"
else
    echo "❌ Failed to reset challenge. Please check your MySQL configuration."
fi
