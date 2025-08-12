<?php
/**
 * Local File Inclusion (LFI) Challenge - Easy Level
 * Vulnerable Document Viewer System
 * 
 * Objective: Use LFI to read sensitive files and find the flag
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
$file_content = '';
$show_flag = false;
$requested_file = '';

// Handle file viewing request
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $requested_file = $file;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
    // Log file access attempt
    $stmt = $pdo->prepare("INSERT INTO file_access_logs (requested_file, ip_address) VALUES (?, ?)");
    $stmt->execute([$file, $ip]);
    
    // VULNERABLE: Direct file inclusion without proper validation
    $file_path = "docs/" . $file;
    
    // Check if flag-containing file is accessed
    if (strpos($file, 'secret') !== false || strpos($file, 'config') !== false) {
        $show_flag = true;
    }
    
    // Try to read the file
    if (file_exists($file_path)) {
        $file_content = file_get_contents($file_path);
        $message = "📄 Successfully loaded: " . htmlspecialchars($file);
    } else {
        // VULNERABLE: This allows directory traversal
        if (file_exists($file)) {
            $file_content = file_get_contents($file);
            $message = "📄 File loaded from: " . htmlspecialchars($file);
            
            // Check if sensitive content was accessed
            if (strpos($file_content, 'CTF{') !== false) {
                $show_flag = true;
            }
        } else {
            $message = "❌ File not found: " . htmlspecialchars($file);
            $file_content = "The requested file could not be located.";
        }
    }
}

// Available documents in docs directory
$docs_directory = './docs/';
$available_docs = [];
if (is_dir($docs_directory)) {
    $files = scandir($docs_directory);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
            $available_docs[] = $file;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTF Lab - Document Viewer (LFI Challenge)</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #1a1a1a; 
            color: #00ff00; 
            margin: 0; 
            padding: 20px;
        }
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: #2d2d2d; 
            padding: 20px; 
            border-radius: 10px; 
            border: 2px solid #00ff00;
        }
        .challenge-info {
            background: #0d1117;
            padding: 15px;
            border-left: 4px solid #9c27b0;
            margin-bottom: 20px;
        }
        .file-viewer {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin: 20px 0;
        }
        .file-list {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 8px;
        }
        .file-content {
            background: #0d1117;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            max-height: 500px;
            overflow-y: auto;
        }
        input[type="text"] { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            background: #1a1a1a; 
            border: 1px solid #00ff00; 
            color: #00ff00;
            border-radius: 5px;
        }
        button { 
            background: #9c27b0; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            cursor: pointer; 
            border-radius: 5px;
            font-weight: bold;
        }
        .file-link {
            display: block;
            color: #ffd700;
            text-decoration: none;
            padding: 5px 0;
            border-bottom: 1px solid #333;
        }
        .file-link:hover {
            color: #ffeb3b;
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
        .payload-examples {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }
        .payload {
            background: #333;
            color: #ffd700;
            padding: 5px 8px;
            margin: 3px 0;
            border-radius: 3px;
            font-family: monospace;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📄 Document Viewer - LFI Challenge</h1>
        
        <div class="challenge-info">
            <h3>Objective:</h3>
            <p>Use Local File Inclusion (LFI) to read sensitive system files and retrieve the flag.</p>
            <p><strong>Difficulty:</strong> Easy | <strong>Points:</strong> 175</p>
        </div>

        <div style="background: #333; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <h4>📁 File Viewer</h4>
            <form method="GET">
                <input type="text" name="file" value="<?php echo htmlspecialchars($requested_file); ?>" 
                       placeholder="Enter filename (e.g., welcome.txt)">
                <button type="submit">View File</button>
            </form>
        </div>

        <?php if ($message): ?>
            <div style="margin: 20px 0; padding: 10px; background: #333; border-radius: 5px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_flag): ?>
            <div class="flag">
                🚩 FLAG: CTF{L0c4l_F1l3_1nclus10n_M4st3r}
            </div>
        <?php endif; ?>

        <div class="file-viewer">
            <div class="file-list">
                <h4>📚 Available Documents</h4>
                <?php foreach ($available_docs as $doc): ?>
                    <a href="?file=<?php echo urlencode($doc); ?>" class="file-link">
                        📄 <?php echo htmlspecialchars($doc); ?>
                    </a>
                <?php endforeach; ?>
                
                <div style="margin-top: 20px; color: #888; font-size: 0.9em;">
                    <em>💡 Hint: Try exploring beyond the docs/ directory...</em>
                </div>
            </div>
            
            <div class="file-content">
                <?php if ($file_content): ?>
                    <?php echo htmlspecialchars($file_content); ?>
                <?php else: ?>
                    <em style="color: #888;">Select a file to view its contents</em>
                <?php endif; ?>
            </div>
        </div>

        <div class="hint">
            <h4>💡 LFI Hints:</h4>
            <ul>
                <li>Try accessing files outside the docs/ directory using path traversal</li>
                <li>Look for configuration files that might contain sensitive information</li>
                <li>Common paths: ../config/secret.txt, ../../etc/passwd</li>
                <li>The application doesn't properly validate file paths</li>
            </ul>
            
            <div class="payload-examples">
                <h4>🔧 LFI Payloads to Try:</h4>
                <div class="payload" onclick="document.querySelector('input[name=file]').value=this.textContent">../config/secret.txt</div>
                <div class="payload" onclick="document.querySelector('input[name=file]').value=this.textContent">../../etc/passwd</div>
                <div class="payload" onclick="document.querySelector('input[name=file]').value=this.textContent">../../../var/log/apache2/access.log</div>
                <div class="payload" onclick="document.querySelector('input[name=file]').value=this.textContent">config/secret.txt</div>
                <div class="payload" onclick="document.querySelector('input[name=file]').value=this.textContent">./config/secret.txt</div>
            </div>
        </div>

        <div style="margin-top: 30px; padding: 10px; background: #1a1a1a; border-radius: 5px;">
            <h4>🎯 Learning Objectives:</h4>
            <ul>
                <li>Understand Local File Inclusion vulnerabilities</li>
                <li>Practice directory traversal techniques</li>
                <li>Learn about path validation bypass methods</li>
                <li>Explore file system enumeration</li>
            </ul>
        </div>
    </div>

    <script>
        // Make payload examples clickable
        document.querySelectorAll('.payload').forEach(payload => {
            payload.addEventListener('click', function() {
                document.querySelector('input[name="file"]').value = this.textContent;
            });
        });
    </script>
</body>
</html>
