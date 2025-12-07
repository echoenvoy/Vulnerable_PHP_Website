<?php
// Simulated login check
$logged_in = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple login
    if ($username === 'admin' && $password === 'password') {
        $logged_in = true;
        $_SESSION['user'] = 'admin';
    }
}

// Get redirect parameter
$redirect = $_GET['redirect'] ?? 'dashboard.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Open Redirect Vulnerability</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        .login-box { max-width: 400px; margin: 0 auto; background: #f8f9fa; padding: 30px; border-radius: 10px; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        .warning { background: #f8d7da; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .redirect-list { background: #e9ecef; padding: 15px; margin: 20px 0; }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
    </style>
</head>
<body>
    <h2>🔗 Open Redirect Vulnerability Test</h2>
    
    <?php if (!$logged_in): ?>
    <div class="login-box">
        <h3>Please Login</h3>
        <form method="POST">
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
            
            <p>Username: <input type="text" name="username" placeholder="admin"></p>
            <p>Password: <input type="password" name="password" placeholder="password"></p>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="warning">
            <strong>⚠️ Current Redirect URL:</strong><br>
            <?php echo htmlspecialchars($redirect); ?>
        </div>
    </div>
    <?php else: ?>
    <div style="background: #d4edda; padding: 20px; margin: 20px 0;">
        <h3>✅ Login Successful!</h3>
        <p>Welcome, admin!</p>
        
        <div style="margin: 20px 0;">
            <strong>Redirecting to:</strong> <?php echo htmlspecialchars($redirect); ?>
        </div>
        
        <!-- VULNERABLE: Direct redirect without validation -->
        <script>
            setTimeout(function() {
                window.location.href = "<?php echo $redirect; ?>";
            }, 2000);
        </script>
        
        <p><a href="<?php echo htmlspecialchars($redirect); ?>">Click here if not redirected</a></p>
    </div>
    <?php endif; ?>
    
    <div class="redirect-list">
        <h3>🎯 Open Redirect Test Links:</h3>
        <ul>
            <li><a href="?redirect=http://evil.com">External Site (http://evil.com)</a></li>
            <li><a href="?redirect=//evil.com">Protocol-relative (//evil.com)</a></li>
            <li><a href="?redirect=javascript:alert('XSS')">JavaScript pseudo-protocol</a></li>
            <li><a href="?redirect=data:text/html,<h1>Hacked</h1>">Data URI</a></li>
            <li><a href="?redirect=/../uploads/shell.php">Path traversal</a></li>
            <li><a href="?redirect=https://google.com">Legitimate external site</a></li>
        </ul>
        
        <h4>Phishing Example URLs:</h4>
        <code>
        http://<?php echo $_SERVER['HTTP_HOST']; ?>/open_redirect.php?redirect=https://evil.com/login.html<br>
        http://<?php echo $_SERVER['HTTP_HOST']; ?>/open_redirect.php?redirect=//evil-phishing.com
        </code>
    </div>
    
    <div style="background: #fff3cd; padding: 20px; margin: 20px 0;">
        <h3>🛡️ Open Redirect Prevention:</h3>
        <ul>
            <li><strong>Whitelist URLs:</strong> Only allow specific, trusted URLs</li>
            <li><strong>Relative URLs only:</strong> Don't allow full URLs</li>
            <li><strong>Token validation:</strong> Sign redirect URLs with HMAC</li>
            <li><strong>Domain checking:</strong> Ensure redirect stays on same domain</li>
            <li><strong>User confirmation:</strong> Show warning before external redirects</li>
        </ul>
        
        <h4>Safe Redirect Example:</h4>
        <pre>
// Whitelist approach
$allowed_redirects = [
    'dashboard.php',
    'profile.php', 
    'settings.php'
];

if (in_array($redirect, $allowed_redirects)) {
    header("Location: $redirect");
} else {
    header("Location: dashboard.php"); // Default
}
        </pre>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="?logout=1" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none;">
            Logout
        </a>
    </div>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
    <?php 
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: open_redirect.php');
    }
    ?>
</body>
</html>
