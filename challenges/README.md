# CTF Lab - Challenges

This directory contains all the custom CTF challenges for the cybersecurity lab.

## Challenge Structure

```
challenges/
├── easy/
│   └── sql-injection/
│       ├── setup.sql      # Database setup
│       ├── login.php      # Vulnerable application
│       └── reset.sh       # Reset script
├── medium/               # Medium difficulty challenges
├── hard/                 # Hard difficulty challenges
└── README.md            # This file
```

## Available Challenges

### Easy Level

#### 1. SQL Injection - Basic Authentication Bypass (100 points)
- **File**: `easy/sql-injection/login.php`
- **Flag**: `CTF{SQL_1nj3ct10n_B4s1cs_M4st3r3d}`
- **Objective**: Bypass login authentication using SQL injection
- **Learning Goals**: 
  - Basic SQL injection techniques
  - Authentication bypass
  - Understanding vulnerable queries

**Setup Instructions**:
```bash
# Set up the database
mysql -u root -p < challenges/easy/sql-injection/setup.sql

# Access the challenge
http://localhost/challenges/easy/sql-injection/login.php
```

**Solution Hint**: Try payloads like `admin' OR '1'='1' --` in the username field.

#### 2. Stored XSS - Cookie Stealer (150 points)
- **File**: `easy/stored-xss/guestbook.php`
- **Flag**: `CTF{St0r3d_XSS_C00k13_St34l3r}`
- **Objective**: Execute stored XSS to steal admin cookies
- **Learning Goals**:
  - Understanding persistent XSS vulnerabilities
  - Cookie theft techniques
  - XSS payload crafting

**Setup Instructions**:
```bash
# Set up the database
mysql -u root -p < challenges/easy/stored-xss/setup.sql

# Access the challenge
http://localhost/challenges/easy/stored-xss/guestbook.php
```

**Solution Hint**: Try `<script>alert(document.cookie)</script>` in the comment field.

#### 3. Weak Authentication - Password Cracking (125 points)
- **File**: `easy/weak-auth/login.php`
- **Flag**: `CTF{W34k_P4ssw0rds_4r3_D4ng3r0us}`
- **Objective**: Break into admin account using weak credentials
- **Learning Goals**:
  - Password enumeration techniques
  - Security question vulnerabilities
  - Credential stuffing attacks

**Setup Instructions**:
```bash
# Set up the database
mysql -u root -p < challenges/easy/weak-auth/setup.sql

# Access the challenge
http://localhost/challenges/easy/weak-auth/login.php
```

**Solution Hint**: Try common passwords like `admin:123456` or use password reset with obvious answers.

#### 4. Local File Inclusion - Configuration Disclosure (175 points)
- **File**: `easy/lfi/viewer.php`
- **Flag**: `CTF{L0c4l_F1l3_1nclus10n_M4st3r}`
- **Objective**: Use LFI to read sensitive system files
- **Learning Goals**:
  - Directory traversal techniques
  - Path validation bypass methods
  - File system enumeration

**Setup Instructions**:
```bash
# Set up the database
mysql -u root -p < challenges/easy/lfi/setup.sql

# Access the challenge
http://localhost/challenges/easy/lfi/viewer.php
```

**Solution Hint**: Try `../config/secret.txt` to access configuration files.

---

## Coming Soon

### Easy Level
- [x] Stored XSS Challenge ✅
- [x] Weak Authentication Challenge ✅ 
- [x] Local File Inclusion (LFI) ✅
- [ ] Command Injection - Basic

### Medium Level
- [ ] File Upload Bypass
- [ ] CSRF Attack
- [ ] SSRF (Server-Side Request Forgery)
- [ ] SQL Injection - Blind

### Hard Level
- [ ] Advanced Command Injection
- [ ] Multi-step Attack Chain
- [ ] Privilege Escalation
- [ ] Advanced XSS with CSP Bypass

## Usage

1. **Setup**: Run the setup script for each challenge
2. **Access**: Navigate to the challenge URL in your browser
3. **Submit**: Use the flag portal (`/portal/submit.php`) to submit flags
4. **Reset**: Use the reset script to restore challenge state

## Development Notes

- All challenges follow the flag format: `CTF{challenge_description}`
- Each challenge includes hints and learning objectives
- Reset scripts allow multiple attempts
- Challenges are designed to be educational and safe for lab environments
