<?php
/**
 * CTF Lab - Flag Submission Portal
 * Basic flag validation system
 */

session_start();

// Initialize session variables if not set
if (!isset($_SESSION['solved_challenges'])) {
    $_SESSION['solved_challenges'] = [];
}
if (!isset($_SESSION['total_points'])) {
    $_SESSION['total_points'] = 0;
}

// Known flags and their point values
$valid_flags = [
    'CTF{SQL_1nj3ct10n_B4s1cs_M4st3r3d}' => [
        'challenge' => 'SQL Injection - Easy',
        'points' => 100,
        'difficulty' => 'Easy'
    ],
    'CTF{St0r3d_XSS_C00k13_St34l3r}' => [
        'challenge' => 'Stored XSS - Easy',
        'points' => 150,
        'difficulty' => 'Easy'
    ],
    'CTF{W34k_P4ssw0rds_4r3_D4ng3r0us}' => [
        'challenge' => 'Weak Authentication - Easy',
        'points' => 125,
        'difficulty' => 'Easy'
    ],
    'CTF{L0c4l_F1l3_1nclus10n_M4st3r}' => [
        'challenge' => 'Local File Inclusion - Easy',
        'points' => 175,
        'difficulty' => 'Easy'
    ]
];

$message = '';
$message_type = '';

if ($_POST['flag']) {
    $submitted_flag = trim($_POST['flag']);
    
    if (isset($valid_flags[$submitted_flag])) {
        $flag_info = $valid_flags[$submitted_flag];
        
        // Check if already solved
        if (in_array($submitted_flag, $_SESSION['solved_challenges'])) {
            $message = "🔄 You've already solved this challenge!";
            $message_type = 'info';
        } else {
            // Add to solved challenges
            $_SESSION['solved_challenges'][] = $submitted_flag;
            $_SESSION['total_points'] += $flag_info['points'];
            
            $message = "🎉 Correct! You solved: " . $flag_info['challenge'] . " (+" . $flag_info['points'] . " points)";
            $message_type = 'success';
        }
    } else {
        $message = "❌ Invalid flag. Try again!";
        $message_type = 'error';
    }
}

// Get solved challenges with details
$solved_details = [];
foreach ($_SESSION['solved_challenges'] as $solved_flag) {
    if (isset($valid_flags[$solved_flag])) {
        $solved_details[] = $valid_flags[$solved_flag];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF Lab - Flag Submission Portal</title>
    <style>
        body { 
            font-family: 'Courier New', monospace; 
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a1a 100%); 
            color: #00ff00; 
            margin: 0; 
            padding: 20px;
            min-height: 100vh;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: rgba(45, 45, 45, 0.9); 
            padding: 30px; 
            border-radius: 15px; 
            border: 2px solid #00ff00;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #00ff00;
            padding-bottom: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #00ff00;
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #00ff00;
        }
        input[type="text"] { 
            width: 100%; 
            padding: 15px; 
            margin: 10px 0; 
            background: #0d1117; 
            border: 2px solid #00ff00; 
            color: #00ff00;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 16px;
        }
        button { 
            background: linear-gradient(45deg, #00ff00, #00cc00);
            color: #000; 
            padding: 15px 30px; 
            border: none; 
            cursor: pointer; 
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s;
        }
        button:hover {
            background: linear-gradient(45deg, #00cc00, #009900);
            transform: translateY(-2px);
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
        }
        .success { background: #4caf50; color: white; }
        .error { background: #f44336; color: white; }
        .info { background: #2196f3; color: white; }
        
        .challenges-solved {
            background: #0d1117;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #30363d;
            margin-top: 20px;
        }
        .challenge-item {
            background: #21262d;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .difficulty-easy { border-left: 4px solid #4caf50; }
        .difficulty-medium { border-left: 4px solid #ff9800; }
        .difficulty-hard { border-left: 4px solid #f44336; }
        
        .available-challenges {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚩 CTF Lab - Flag Submission Portal</h1>
            <p>Submit your captured flags here to earn points!</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($_SESSION['solved_challenges']); ?></div>
                <div>Challenges Solved</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $_SESSION['total_points']; ?></div>
                <div>Total Points</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($valid_flags); ?></div>
                <div>Available Challenges</div>
            </div>
        </div>

        <form method="POST">
            <h3>🎯 Submit Flag</h3>
            <input type="text" name="flag" placeholder="CTF{your_flag_here}" required>
            <button type="submit">Submit Flag</button>
        </form>

        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($solved_details)): ?>
            <div class="challenges-solved">
                <h3>✅ Solved Challenges</h3>
                <?php foreach ($solved_details as $challenge): ?>
                    <div class="challenge-item difficulty-<?php echo strtolower($challenge['difficulty']); ?>">
                        <span><?php echo $challenge['challenge']; ?></span>
                        <span style="color: #ffd700;"><?php echo $challenge['points']; ?> pts</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="available-challenges">
            <h3>🎮 Available Challenges</h3>
            <div class="challenge-item difficulty-easy">
                <span>SQL Injection - Easy</span>
                <span style="color: #4caf50;">100 pts</span>
            </div>
            <div class="challenge-item difficulty-easy">
                <span>Stored XSS - Easy</span>
                <span style="color: #4caf50;">150 pts</span>
            </div>
            <div class="challenge-item difficulty-easy">
                <span>Weak Authentication - Easy</span>
                <span style="color: #4caf50;">125 pts</span>
            </div>
            <div class="challenge-item difficulty-easy">
                <span>Local File Inclusion - Easy</span>
                <span style="color: #4caf50;">175 pts</span>
            </div>
            <div style="color: #666; font-style: italic; margin-top: 15px; padding: 10px; background: #333; border-radius: 5px;">
                <strong>💡 Challenge URLs:</strong><br>
                • SQL Injection: <code>/challenges/easy/sql-injection/login.php</code><br>
                • Stored XSS: <code>/challenges/easy/stored-xss/guestbook.php</code><br>
                • Weak Auth: <code>/challenges/easy/weak-auth/login.php</code><br>
                • LFI: <code>/challenges/easy/lfi/viewer.php</code>
            </div>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #0d1117; border-radius: 10px; text-align: center;">
            <h4>🏆 Leaderboard</h4>
            <p>Your current rank: #1 with <?php echo $_SESSION['total_points']; ?> points</p>
            <small>(Multi-user leaderboard coming in future updates)</small>
        </div>
    </div>
</body>
</html>
