<?php
// Simple JWT implementation (vulnerable)
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function createJWT($payload, $secret = 'weaksecret', $alg = 'HS256') {
    $header = [
        'alg' => $alg,
        'typ' => 'JWT'
    ];
    
    $encodedHeader = base64url_encode(json_encode($header));
    $encodedPayload = base64url_encode(json_encode($payload));
    $signature = base64url_encode(hash_hmac('sha256', "$encodedHeader.$encodedPayload", $secret, true));
    
    return "$encodedHeader.$encodedPayload.$signature";
}

function verifyJWT($token, $secret = 'weaksecret') {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;
    
    list($header, $payload, $signature) = $parts;
    
    $decodedHeader = json_decode(base64url_decode($header), true);
    
    // VULNERABILITY 1: alg:none attack
    if (isset($decodedHeader['alg']) && $decodedHeader['alg'] === 'none') {
        return true; // Accepts tokens with no algorithm!
    }
    
    // VULNERABILITY 2: No signature verification if weak
    $expectedSignature = base64url_encode(hash_hmac('sha256', "$header.$payload", $secret, true));
    
    // VULNERABILITY 3: Timing attack (not really, but showing concept)
    return hash_equals($expectedSignature, $signature);
}

// Simulated users
$users = [
    'user1' => ['id' => 1, 'role' => 'user'],
    'admin' => ['id' => 2, 'role' => 'admin']
];

// Generate default token
if (!isset($_COOKIE['jwt_token'])) {
    $payload = ['user' => 'user1', 'role' => 'user', 'exp' => time() + 3600];
    $token = createJWT($payload);
    setcookie('jwt_token', $token, time() + 3600, '/');
} else {
    $token = $_COOKIE['jwt_token'];
}

// Verify token
$valid = verifyJWT($token);
$decoded = $valid ? json_decode(base64url_decode(explode('.', $token)[1]), true) : null;

// Check for attacks
if (isset($_GET['attack'])) {
    echo "<h3>JWT Attack Results:</h3>";
    
    switch ($_GET['attack']) {
        case 'none':
            // alg:none attack
            $header = base64url_encode(json_encode(['alg' => 'none', 'typ' => 'JWT']));
            $payload = base64url_encode(json_encode(['user' => 'admin', 'role' => 'admin', 'exp' => time() + 999999]));
            $fakeToken = "$header.$payload.";
            
            if (verifyJWT($fakeToken)) {
                echo "<div style='background:#dc3545;color:white;padding:15px;'>";
                echo "💥 SUCCESS: alg:none attack worked!<br>";
                echo "Token accepted: $fakeToken";
                echo "</div>";
            }
            break;
            
        case 'weak':
            // Weak secret brute force simulation
            echo "Testing weak secrets...<br>";
            $weakSecrets = ['secret', 'password', '123456', 'admin', 'jwtsecret', 'weaksecret'];
            
            foreach ($weakSecrets as $secret) {
                if (verifyJWT($token, $secret)) {
                    echo "<div style='background:#dc3545;color:white;padding:15px;margin:10px 0;'>";
                    echo "💥 SECRET CRACKED: '$secret'<br>";
                    echo "Token can now be forged!";
                    echo "</div>";
                    break;
                }
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>JWT Vulnerabilities</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        .token { background: #333; color: #0f0; padding: 15px; margin: 10px 0; font-family: monospace; word-break: break-all; }
        .attack { background: #dc3545; color: white; padding: 10px; margin: 10px 0; text-decoration: none; display: inline-block; }
        .success { background: #28a745; color: white; padding: 15px; margin: 10px 0; }
        .warning { background: #ffc107; padding: 15px; margin: 10px 0; }
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
    <h2>🔑 JWT (JSON Web Token) Vulnerabilities</h2>
    
    <div class="token">
        <strong>Your Current JWT Token:</strong><br>
        <?php echo htmlspecialchars($token); ?>
    </div>
    
    <?php if ($decoded): ?>
    <div class="success">
        <strong>✅ Token Valid!</strong><br>
        User: <?php echo htmlspecialchars($decoded['user'] ?? 'unknown'); ?><br>
        Role: <?php echo htmlspecialchars($decoded['role'] ?? 'none'); ?><br>
        Expires: <?php echo date('Y-m-d H:i:s', $decoded['exp'] ?? time()); ?>
    </div>
    <?php endif; ?>
    
    <h3>🎯 JWT Attacks to Try:</h3>
    
    <a href="?attack=none" class="attack">1. alg:none Attack</a><br>
    <a href="?attack=weak" class="attack">2. Weak Secret Brute Force</a><br>
    <a href="?attack=kid" class="attack">3. KID Injection (Simulated)</a><br>
    
    <div class="warning">
        <h4>Common JWT Vulnerabilities:</h4>
        <ul>
            <li><strong>alg:none</strong> - Accept tokens without signature</li>
            <li><strong>Weak secrets</strong> - Brute-forceable HMAC keys</li>
            <li><strong>KID injection</strong> - Path traversal in key ID</li>
            <li><strong>JKU/JWK</strong> - External key specification</li>
            <li><strong>Signature stripping</strong> - Remove signature part</li>
        </ul>
        
        <h4>🛡️ JWT Security Best Practices:</h4>
        <ul>
            <li>Use strong, random secrets (256-bit+)</li>
            <li>Reject tokens with alg:none</li>
            <li>Validate all token claims</li>
            <li>Use short expiration times</li>
            <li>Implement proper key rotation</li>
        </ul>
    </div>
    
    <div class="token">
        <strong>Example Vulnerable Token Creation:</strong><br>
        <?php
        $vulnPayload = ['user' => 'admin', 'role' => 'admin', 'exp' => time() + 999999];
        $vulnToken = createJWT($vulnPayload, 'weak123');
        echo htmlspecialchars($vulnToken);
        ?>
    </div>
    
    <div style="margin-top: 20px;">
        <form method="POST">
            <input type="text" name="custom_token" placeholder="Enter JWT token to test" style="width: 500px;">
            <button type="submit">Test Token</button>
        </form>
    </div>
    
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_token'])): ?>
    <div style="margin-top: 20px; padding: 15px; background: #e9ecef;">
        <strong>Custom Token Test:</strong><br>
        Token: <?php echo htmlspecialchars($_POST['custom_token']); ?><br>
        Valid: <?php echo verifyJWT($_POST['custom_token']) ? '✅ Yes' : '❌ No'; ?>
    </div>
    
    <?php endif; ?>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
</body>
</html>
