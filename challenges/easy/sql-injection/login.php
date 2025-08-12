<?php
/**
 * SQL Injection Challenge - Easy Level
 * Vulnerable Login System
 * 
 * Objective: Find a way to bypass authentication and retrieve the flag
 * Flag location: Check the admin_panel view or flags table
 */

session_start();
error_reporting(0); // Hide errors to make it more realistic

// Database connection
$host = 'localhost';
$dbname = 'ctf_lab';
$username = 'root';
$password = ''; // Adjust as needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
} catch(PDOException $e) {
    die("Connection failed. Please ensure the database is set up correctly.");
}

$message = '';
$show_flag = false;

if ($_POST['username'] && $_POST['password']) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    // VULNERABLE: Direct string concatenation - SQL injection possible!
    $query = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    
    $result = $pdo->query($query);
    
    if ($result && $result->rowCount() > 0) {
        $userData = $result->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user'] = $userData['username'];
        $_SESSION['role'] = $userData['role'];
        
        if ($userData['role'] === 'admin') {
            $message = "Welcome Admin! Here's your flag:";
            
            // Fetch the flag
            $flagQuery = "SELECT flag_value FROM flags WHERE challenge_name = 'sql-injection-easy'";
            $flagResult = $pdo->query($flagQuery);
            if ($flagResult) {
                $flag = $flagResult->fetch(PDO::FETCH_ASSOC);
                $show_flag = $flag['flag_value'];
            }
        } else {
            $message = "Welcome " . htmlspecialchars($userData['username']) . "! You're logged in as a regular user.";
        }
    } else {
        $message = "Invalid credentials. Try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF Lab - SQL Injection Challenge</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #1a1a1a; 
            color: #00ff00; 
            margin: 0; 
            padding: 20px;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #2d2d2d; 
            padding: 20px; 
            border-radius: 10px; 
            border: 2px solid #00ff00;
        }
        .challenge-info {
            background: #0d1117;
            padding: 15px;
            border-left: 4px solid #58a6ff;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            background: #1a1a1a; 
            border: 1px solid #00ff00; 
            color: #00ff00;
            border-radius: 5px;
        }
        button { 
            background: #00ff00; 
            color: #000; 
            padding: 10px 20px; 
            border: none; 
            cursor: pointer; 
            border-radius: 5px;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🔒 SQL Injection Challenge - Easy</h1>
        
        <div class="challenge-info">
            <h3>Objective:</h3>
            <p>Bypass the login authentication to access the admin panel and retrieve the flag.</p>
            <p><strong>Difficulty:</strong> Easy | <strong>Points:</strong> 100</p>
        </div>

        <form method="POST">
            <h3>Login Portal</h3>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <?php if ($message): ?>
            <div style="margin: 20px 0; padding: 10px; background: #333; border-radius: 5px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_flag): ?>
            <div class="flag">
                🚩 FLAG: <?php echo $show_flag; ?>
            </div>
        <?php endif; ?>

        <div class="hint">
            <h4>💡 Hints:</h4>
            <ul>
                <li>Try common SQL injection payloads in the username field</li>
                <li>The goal is to bypass authentication, not find the actual credentials</li>
                <li>Think about how you can make the WHERE clause always return true</li>
                <li>Admin access is required to see the flag</li>
            </ul>
        </div>

        <div style="margin-top: 30px; padding: 10px; background: #1a1a1a; border-radius: 5px;">
            <h4>🎯 Learning Objectives:</h4>
            <ul>
                <li>Understand SQL injection fundamentals</li>
                <li>Learn about authentication bypass techniques</li>
                <li>Practice identifying vulnerable input fields</li>
            </ul>
        </div>
    </div>
</body>
</html>
