#!/bin/bash

# CTF Lab Setup Script
# Sets up the entire cybersecurity lab environment

echo "🚀 Setting up CTF Lab Environment..."

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as root or with sudo for web server setup
if [[ $EUID -eq 0 ]]; then
    SUDO=""
else
    SUDO="sudo"
fi

print_status "Starting CTF Lab setup..."

# 1. Check dependencies
print_status "Checking dependencies..."

# Check for required services
command -v mysql >/dev/null 2>&1 || { print_error "MySQL is required but not installed. Please install mysql-server"; exit 1; }
command -v php >/dev/null 2>&1 || { print_error "PHP is required but not installed. Please install php"; exit 1; }

# Check web server (Apache or Nginx)
if command -v apache2 >/dev/null 2>&1; then
    WEB_SERVER="apache2"
    WEB_ROOT="/var/www/html"
elif command -v nginx >/dev/null 2>&1; then
    WEB_SERVER="nginx"
    WEB_ROOT="/var/www/html"
else
    print_warning "No web server detected. You may need to install apache2 or nginx"
fi

print_status "Dependencies check completed"

# 2. Setup database
print_status "Setting up databases..."

# Setup SQL Injection Challenge
mysql -u root -p -e "SOURCE challenges/easy/sql-injection/setup.sql" 2>/dev/null
if [ $? -eq 0 ]; then
    print_status "✅ SQL Injection challenge database setup completed"
else
    print_warning "Failed to setup SQL Injection database - you may need to run it manually"
fi

# Setup Stored XSS Challenge
mysql -u root -p -e "SOURCE challenges/easy/stored-xss/setup.sql" 2>/dev/null
if [ $? -eq 0 ]; then
    print_status "✅ Stored XSS challenge database setup completed"
else
    print_warning "Failed to setup Stored XSS database - you may need to run it manually"
fi

# Setup Weak Authentication Challenge
mysql -u root -p -e "SOURCE challenges/easy/weak-auth/setup.sql" 2>/dev/null
if [ $? -eq 0 ]; then
    print_status "✅ Weak Authentication challenge database setup completed"
else
    print_warning "Failed to setup Weak Auth database - you may need to run it manually"
fi

# Setup LFI Challenge
mysql -u root -p -e "SOURCE challenges/easy/lfi/setup.sql" 2>/dev/null
if [ $? -eq 0 ]; then
    print_status "✅ LFI challenge database setup completed"
else
    print_warning "Failed to setup LFI database - you may need to run it manually"
fi

# 3. Copy web files to web root
print_status "Setting up web files..."

if [ -n "$WEB_SERVER" ] && [ -d "$WEB_ROOT" ]; then
    # Create challenges directory in web root
    $SUDO mkdir -p "$WEB_ROOT/ctf-lab"
    
    # Copy challenge files
    $SUDO cp -r challenges "$WEB_ROOT/ctf-lab/"
    $SUDO cp -r portal "$WEB_ROOT/ctf-lab/"
    
    # Set proper permissions
    $SUDO chown -R www-data:www-data "$WEB_ROOT/ctf-lab" 2>/dev/null || true
    $SUDO chmod -R 755 "$WEB_ROOT/ctf-lab"
    
    print_status "✅ Web files copied to $WEB_ROOT/ctf-lab"
    
    # Start web server if not running
    if systemctl is-active --quiet $WEB_SERVER; then
        print_status "$WEB_SERVER is already running"
    else
        print_status "Starting $WEB_SERVER..."
        $SUDO systemctl start $WEB_SERVER
        $SUDO systemctl enable $WEB_SERVER
    fi
    
else
    print_warning "Web server not properly configured. You may need to manually copy files."
fi

# 4. Display access information
echo ""
echo "🎉 CTF Lab setup completed!"
echo "================================================="
echo ""
print_status "Available Challenges:"
echo "  • SQL Injection (Easy): http://localhost/ctf-lab/challenges/easy/sql-injection/login.php"
echo "  • Stored XSS (Easy): http://localhost/ctf-lab/challenges/easy/stored-xss/guestbook.php"
echo "  • Weak Auth (Easy): http://localhost/ctf-lab/challenges/easy/weak-auth/login.php"
echo "  • LFI (Easy): http://localhost/ctf-lab/challenges/easy/lfi/viewer.php"
echo ""
print_status "Flag Submission Portal:"
echo "  • Portal: http://localhost/ctf-lab/portal/submit.php"
echo ""
print_status "Documentation:"
echo "  • Challenge Guide: challenges/README.md"
echo ""
echo "================================================="
print_status "Next Steps:"
echo "1. Test the SQL injection challenge"
echo "2. Submit flags through the portal"
echo "3. Create additional challenges using the template"
echo ""

# 5. Quick test
print_status "Running quick connectivity test..."
if command -v curl >/dev/null 2>&1; then
    if curl -s http://localhost/ctf-lab/portal/submit.php > /dev/null; then
        print_status "✅ Web server is responding correctly"
    else
        print_warning "Web server may not be properly configured"
    fi
fi

print_status "Setup completed! Happy hacking! 🔒"
