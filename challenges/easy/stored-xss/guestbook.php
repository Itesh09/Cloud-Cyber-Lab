<?php
/**
 * Stored XSS Challenge - Easy Level
 * Vulnerable Guestbook System
 * 
 * Objective: Execute stored XSS to steal admin cookies or display flag
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

// Handle comment submission
if ($_POST['username'] && $_POST['email'] && $_POST['comment']) {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    
    // VULNERABLE: No input sanitization - XSS possible!
    $stmt = $pdo->prepare("INSERT INTO comments (username, email, comment) VALUES (?, ?, ?)");
    if ($stmt->execute([$user, $email, $comment])) {
        $message = "Comment posted successfully!";
        
        // Check if XSS payload contains flag-revealing trigger
        if (strpos(strtolower($comment), 'document.cookie') !== false || 
            strpos(strtolower($comment), 'alert') !== false ||
            strpos(strtolower($comment), '<script>') !== false) {
            // Simulate admin visiting and XSS execution
            $show_flag = true;
            $message = "🎯 XSS Detected! Admin visited the page and your payload executed!";
        }
    } else {
        $message = "Error posting comment. Please try again.";
    }
}

// Get all comments
$stmt = $pdo->query("SELECT * FROM comments ORDER BY posted_at DESC");
$comments = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF Lab - Guestbook (XSS Challenge)</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #1a1a1a; 
            color: #00ff00; 
            margin: 0; 
            padding: 20px;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: #2d2d2d; 
            padding: 20px; 
            border-radius: 10px; 
            border: 2px solid #00ff00;
        }
        .challenge-info {
            background: #0d1117;
            padding: 15px;
            border-left: 4px solid #ff6b6b;
            margin-bottom: 20px;
        }
        .form-group {
            margin: 15px 0;
        }
        input[type="text"], input[type="email"], textarea { 
            width: 100%; 
            padding: 10px; 
            margin: 5px 0; 
            background: #1a1a1a; 
            border: 1px solid #00ff00; 
            color: #00ff00;
            border-radius: 5px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        button { 
            background: #ff6b6b; 
            color: white; 
            padding: 12px 24px; 
            border: none; 
            cursor: pointer; 
            border-radius: 5px;
            font-weight: bold;
        }
        .comment {
            background: #1a1a1a;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #00ff00;
        }
        .comment-header {
            font-weight: bold;
            color: #ffd700;
            margin-bottom: 8px;
        }
        .comment-date {
            font-size: 0.8em;
            color: #888;
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
        .admin-note {
            background: #333;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #2196f3;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Guestbook - XSS Challenge</h1>
        
        <div class="challenge-info">
            <h3>Objective:</h3>
            <p>Execute a stored XSS attack to steal admin cookies or reveal sensitive information.</p>
            <p><strong>Difficulty:</strong> Easy | <strong>Points:</strong> 150</p>
        </div>

        <div class="admin-note">
            <strong>📢 Admin Notice:</strong> The admin regularly checks this guestbook. 
            All comments are reviewed within minutes of posting!
        </div>

        <h3>Post a Comment</h3>
        <form method="POST">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Comment:</label>
                <textarea name="comment" placeholder="Share your thoughts..." required></textarea>
            </div>
            <button type="submit">Post Comment</button>
        </form>

        <?php if ($message): ?>
            <div style="margin: 20px 0; padding: 10px; background: #333; border-radius: 5px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_flag): ?>
            <div class="flag">
                🚩 FLAG: CTF{St0r3d_XSS_C00k13_St34l3r}
            </div>
            <div style="color: #ffd700; padding: 10px; text-align: center;">
                <strong>🍪 Admin Cookie Stolen:</strong> admin_secret_cookie=CTF{St0r3d_XSS_C00k13_St34l3r}
            </div>
        <?php endif; ?>

        <h3>Recent Comments</h3>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <div class="comment-header">
                    <?php echo $comment['username']; ?> 
                    <span class="comment-date"><?php echo $comment['posted_at']; ?></span>
                </div>
                <!-- VULNERABLE: Direct output without sanitization -->
                <div><?php echo $comment['comment']; ?></div>
            </div>
        <?php endforeach; ?>

        <div class="hint">
            <h4>💡 Hints:</h4>
            <ul>
                <li>Try injecting JavaScript code in your comment</li>
                <li>Think about how to steal cookies: <code>document.cookie</code></li>
                <li>Common XSS payloads: <code>&lt;script&gt;alert(document.cookie)&lt;/script&gt;</code></li>
                <li>The admin visits this page regularly - your payload will execute in their browser</li>
            </ul>
        </div>

        <div style="margin-top: 30px; padding: 10px; background: #1a1a1a; border-radius: 5px;">
            <h4>🎯 Learning Objectives:</h4>
            <ul>
                <li>Understand stored XSS vulnerabilities</li>
                <li>Learn about cookie theft techniques</li>
                <li>Practice crafting XSS payloads</li>
                <li>Understand the impact of persistent XSS attacks</li>
            </ul>
        </div>
    </div>

    <!-- Simulate admin visiting periodically -->
    <script>
        // This simulates an admin session checking for malicious content
        setTimeout(() => {
            console.log("🔍 Admin session: Checking guestbook for inappropriate content...");
        }, 2000);
    </script>
</body>
</html>
