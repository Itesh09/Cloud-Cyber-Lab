# 🔒 Cloud Cybersecurity Learning Platform

**A comprehensive cloud-hosted laboratory for cybersecurity education - Safe practice environment for students and beginners.**

![CTF Lab](https://img.shields.io/badge/CTF-Lab-brightgreen) ![Security](https://img.shields.io/badge/Security-Education-blue) ![PHP](https://img.shields.io/badge/PHP-Challenges-purple) ![MySQL](https://img.shields.io/badge/MySQL-Database-orange)

> 💡 **Note**: This is my first cloud project! If you see any flaws or mistakes, please let me know so I can learn and solve them. Feedback is always welcome! 🙏

## 🎯 Overview

This project provides a complete cybersecurity learning environment with vulnerable web applications and custom CTF challenges. Perfect for students, educators, and security professionals looking to practice ethical hacking techniques in a safe, controlled environment.

### ⚡ Features

- **🎯 4 Custom CTF Challenges** - SQL Injection, XSS, Weak Auth, LFI
- **🧃 OWASP Juice Shop Integration** - Modern vulnerable web application for beginners
- **📊 Integrated Flag Portal** - Real-time scoring and progress tracking  
- **🎨 Professional UI** - Hacker-themed interfaces with hints and tutorials
- **☁️ Cloud-Ready Deployment** - Optimized for AWS/Azure/GCP hosting
- **🚀 Automated Setup** - One-command deployment with Docker support
- **📚 Educational Focus** - Learning objectives and detailed documentation
- **🔄 Reset Scripts** - Quick challenge resets for multiple attempts
- **👥 Multi-Student Ready** - Isolated environments for class deployment

## 🏆 Available Challenges

| Challenge | Difficulty | Points | Vulnerability Type |
|-----------|------------|--------|-----------------|
| SQL Injection | Easy | 100 | Authentication Bypass |
| Stored XSS | Easy | 150 | Cookie Theft |
| Weak Authentication | Easy | 125 | Password Cracking |
| Local File Inclusion | Easy | 175 | Directory Traversal |

**Total Points:** 550

## 🚀 Quick Start

### Prerequisites
- Linux environment (tested on Kali Linux)
- Apache/Nginx web server
- MySQL/MariaDB
- PHP 7.4+

### Installation

```bash
# Clone the repository
git clone https://github.com/Itesh09/Cloud-Cyber-Lab.git
cd Cloud-Cyber-Lab

# Run automated setup
sudo ./scripts/setup-lab.sh

# Access the lab
open http://localhost/ctf-lab/portal/submit.php
```

### Manual Setup

```bash
# 1. Setup databases
mysql -u root -p < challenges/easy/sql-injection/setup.sql
mysql -u root -p < challenges/easy/stored-xss/setup.sql
mysql -u root -p < challenges/easy/weak-auth/setup.sql
mysql -u root -p < challenges/easy/lfi/setup.sql

# 2. Copy to web directory
sudo cp -r challenges /var/www/html/ctf-lab/
sudo cp -r portal /var/www/html/ctf-lab/

# 3. Set permissions
sudo chown -R www-data:www-data /var/www/html/ctf-lab
```

## 🎮 Application Access

### Custom CTF Challenges
- **SQL Injection:** `http://localhost/ctf-lab/challenges/easy/sql-injection/login.php`
- **Stored XSS:** `http://localhost/ctf-lab/challenges/easy/stored-xss/guestbook.php`  
- **Weak Auth:** `http://localhost/ctf-lab/challenges/easy/weak-auth/login.php`
- **LFI:** `http://localhost/ctf-lab/challenges/easy/lfi/viewer.php`
- **Flag Portal:** `http://localhost/ctf-lab/portal/submit.php`

### OWASP Juice Shop (Perfect for Beginners)
- **Main Application:** `http://localhost:3000`
- **Score Board:** `http://localhost:3000/#/score-board`
- **Admin Panel:** Hidden - students need to discover!

> 🌱 **For Students**: Juice Shop is the perfect starting point with 100+ challenges ranging from trivial to expert level. It's designed specifically for learning and includes helpful hints!

### DVWA (Advanced Practice)
- **Application:** `http://localhost/dvwa`
- **Default Login:** `admin:password`

## 📚 Documentation

- [Challenge Guide](challenges/README.md) - Detailed challenge documentation
- [Setup Instructions](docs/) - Comprehensive setup guides
- [Learning Resources](docs/) - Security concepts and techniques

☁️ ## Cloud Deployment

### For Students & Educators
This platform is designed to be deployed on cloud infrastructure for easy access:

#### AWS Deployment
```bash
# Launch EC2 instance (Ubuntu/Amazon Linux)
# Open ports: 22, 80, 443, 3000
# Run setup script
sudo ./scripts/setup-lab.sh
```

#### Azure Deployment
```bash
# Create VM with public IP
# Configure NSG for ports 80, 443, 3000
# Deploy using cloud-init script
```

#### Google Cloud Deployment
```bash
# Use Compute Engine instance
# Configure firewall rules
# Deploy with startup script
```

### Docker Deployment (Coming Soon)
```bash
docker-compose up -d
# All services will be available instantly
```

## 🔧 Challenge Management

### Reset Individual Challenges
```bash
# Reset SQL Injection
./challenges/easy/sql-injection/reset.sh

# Reset Stored XSS
./challenges/easy/stored-xss/reset.sh

# Reset Weak Auth
./challenges/easy/weak-auth/reset.sh

# Reset LFI
./challenges/easy/lfi/reset.sh
```

### Juice Shop Management
```bash
# Start Juice Shop
cd apps/juice-shop && npm start

# Reset Juice Shop progress
rm data/juiceshop.db
```

## 🎓 Learning Objectives

- **Web Application Security** - Common vulnerabilities and exploitation
- **SQL Injection** - Authentication bypass and data extraction
- **Cross-Site Scripting** - Persistent XSS and cookie theft
- **Authentication Flaws** - Weak passwords and security questions
- **File Inclusion** - Directory traversal and information disclosure

## ⚠️ Security Notice

**This lab contains intentionally vulnerable applications for educational purposes only.**

- 🚫 **Never deploy in production environments**
- 🔒 **Use only in isolated lab networks**
- 🎯 **Intended for authorized security training only**
- ⚖️ **Users responsible for ethical and legal compliance**

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md] for guidelines.

### Roadmap
- [ ] Medium difficulty challenges
- [ ] Hard difficulty challenges  
- [ ] Multi-user leaderboard
- [ ] Docker containerization
- [ ] API challenges



## 👨‍💻 Author

Created for cybersecurity education and ethical hacking training.

---

**⭐ Star this repository if it helped your security learning journey!**
