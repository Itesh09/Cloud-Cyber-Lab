<?php
/**
 * Weak Authentication Challenge - Easy Level
 * System with weak passwords and security questions
 * 
 * Objective: Break into admin account using weak credentials or password reset
 */

session_start();
error_reporting(0);

// Database connection
$host = 'localhost';
$dbname = 'ctf_lab';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
} catch(PDOException $e) {
    die("Connection failed. Please ensure the database is set up correctly.");
}

$message = '';
$show_flag = false;
$show_reset = false;
$accounts_visible = false;

// Handle login
if ($_POST['action'] === 'login' && $_POST['username'] && $_POST['password']) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
    // Log attempt
    $stmt = $pdo->prepare("INSERT INTO login_attempts (username, attempted_password, ip_address) VALUES (?, ?, ?)");
    $stmt->execute([$user, $pass, $ip]);
    
    // Check credentials
    $stmt = $pdo->prepare("SELECT * FROM weak_accounts WHERE username = ? AND password = ?");
    $stmt->execute([$user, $pass]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userData) {
        $_SESSION['user'] = $userData['username'];
        $_SESSION['role'] = $userData['role'];
        
        // Update successful login
        $stmt = $pdo->prepare("UPDATE login_attempts SET success = TRUE WHERE username = ? AND attempted_password = ? ORDER BY attempt_time DESC LIMIT 1");
        $stmt->execute([$user, $pass]);
        
        if ($userData['role'] === 'admin' || $userData['role'] === 'manager') {
            $show_flag = true;
            $message = "🎉 Successfully logged in as " . $userData['role'] . "! Here's your flag:";
        } else {
            $message = "✅ Login successful! Welcome " . $userData['username'];
            $accounts_visible = true; // Show other accounts for enumeration
        }
    } else {
        $message = "❌ Invalid credentials. Try again!";
    }
}

// Handle password reset
if ($_POST['action'] === 'reset' && $_POST['username'] && $_POST['security_answer']) {
    $user = $_POST['username'];
    $answer = strtolower(trim($_POST['security_answer']));
    
    $stmt = $pdo->prepare("SELECT * FROM weak_accounts WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userData && strtolower($userData['security_answer']) === $answer) {
        $show_reset = true;
        $message = "🔓 Security question correct! Password for " . $user . " is: " . $userData['password'];
        
        if ($userData['role'] === 'admin' || $userData['role'] === 'manager') {
            $show_flag = true;
        }
    } else {
        $message = "❌ Incorrect security answer. Try again!";
    }
}

// Get some user info for hints (simulating information disclosure)
if ($_POST['action'] === 'forgot') {
    $user = $_POST['username'];
    $stmt = $pdo->prepare("SELECT username, email, security_question FROM weak_accounts WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userData) {
        $message = "📧 Password reset for: " . $userData['email'] . "<br>";
        $message .= "🔐 Security Question: " . $userData['security_question'];
        $show_reset = true;
    } else {
        $message = "❌ Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF Lab - Weak Authentication Challenge</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #1a1a1a; 
            color: #00ff00; 
            margin: 0; 
            padding: 20px;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: #2d2d2d; 
            padding: 20px; 
            border-radius: 10px; 
            border: 2px solid #00ff00;
        }
        .challenge-info {
            background: #0d1117;
            padding: 15px;
            border-left: 4px solid #ffa500;
            margin-bottom: 20px;
        }
        .login-form, .reset-form {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            background: #0d1117; 
            border: 1px solid #00ff00; 
            color: #00ff00;
            border-radius: 5px;
        }
        button { 
            background: #ffa500; 
            color: #000; 
            padding: 12px 20px; 
            border: none; 
            cursor: pointer; 
            border-radius: 5px;
            font-weight: bold;
            margin: 5px;
        }
        .flag { 
            background: #ff6b6b; 
            color: white; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 20px 0;
            font-family: monospace;
            font-size: 18px;
            text-align: center;
        }
        .hint {
            background: #4a4a4a;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #ffeb3b;
        }
        .accounts-list {
            background: #0d1117;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .common-passwords {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin: 10px 0;
        }
        .password-btn {
            background: #333;
            color: #ffa500;
            padding: 8px;
            border: 1px solid #ffa500;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Corporate Login - Weak Auth Challenge</h1>
        
        <div class="challenge-info">
            <h3>Objective:</h3>
            <p>Gain admin access by exploiting weak authentication mechanisms.</p>
            <p><strong>Difficulty:</strong> Easy | <strong>Points:</strong> 125</p>
        </div>

        <div class="login-form">
            <h3>🏢 Company Portal Login</h3>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            
            <div style="margin-top: 15px;">
                <h4>Forgot Password?</h4>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="forgot">
                    <input type="text" name="username" placeholder="Enter username" style="width: 200px; display: inline;">
                    <button type="submit">Reset Password</button>
                </form>
            </div>
        </div>

        <?php if ($show_reset): ?>
            <div class="reset-form">
                <h3>🔐 Security Question</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="reset">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="text" name="security_answer" placeholder="Security Answer" required>
                    <button type="submit">Verify Answer</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div style="margin: 20px 0; padding: 15px; background: #333; border-radius: 5px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_flag): ?>
            <div class="flag">
                🚩 FLAG: CTF{W34k_P4ssw0rds_4r3_D4ng3r0us}
            </div>
        <?php endif; ?>

        <?php if ($accounts_visible): ?>
            <div class="accounts-list">
                <h4>👥 Company Directory (Visible after login)</h4>
                <ul>
                    <li><strong>admin</strong> - admin@company.com (Administrator)</li>
                    <li><strong>manager</strong> - manager@company.com (Manager)</li>
                    <li><strong>ceo</strong> - ceo@company.com (CEO)</li>
                    <li><strong>support</strong> - support@company.com (IT Support)</li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="hint">
            <h4>💡 Hints:</h4>
            <ul>
                <li>Try common weak passwords: admin, password, 123456, qwerty</li>
                <li>Many users use predictable username/password combinations</li>
                <li>Security questions often have obvious answers</li>
                <li>Company name might be useful: "AcmeCorp"</li>
            </ul>
            
            <h4>🔑 Common Passwords to Try:</h4>
            <div class="common-passwords">
                <div class="password-btn" onclick="document.querySelector('input[name=password]').value=this.textContent">123456</div>
                <div class="password-btn" onclick="document.querySelector('input[name=password]').value=this.textContent">password</div>
                <div class="password-btn" onclick="document.querySelector('input[name=password]').value=this.textContent">admin</div>
                <div class="password-btn" onclick="document.querySelector('input[name=password]').value=this.textContent">qwerty</div>
                <div class="password-btn" onclick="document.querySelector('input[name=password]').value=this.textContent">admin123</div>
                <div class="password-btn" onclick="document.querySelector('input[name=password]').value=this.textContent">guest</div>
                <div class="password-btn" onclick="document.querySelector('input[name=password]').value=this.textContent">CEO2023!</div>
            </div>
        </div>

        <div style="margin-top: 30px; padding: 10px; background: #1a1a1a; border-radius: 5px;">
            <h4>🎯 Learning Objectives:</h4>
            <ul>
                <li>Understand the risks of weak passwords</li>
                <li>Learn about password enumeration techniques</li>
                <li>Explore security question vulnerabilities</li>
                <li>Practice credential stuffing attacks</li>
            </ul>
        </div>
    </div>
</body>
</html>
