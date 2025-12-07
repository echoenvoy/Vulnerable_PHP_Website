<?php
// Vulnerable class
class User {
    public $username;
    public $isAdmin = false;
    
    public function __toString() {
        return "User: " . $this->username . 
               " | Admin: " . ($this->isAdmin ? "YES" : "NO");
    }
    
    public function __destruct() {
        // Dangerous destructor - simulates a vulnerability
        if ($this->isAdmin) {
            echo "<div style='background:#d4edda;padding:10px;margin:10px 0;'>";
            echo "⚠️ ADMIN DESTRUCTOR CALLED!<br>";
            echo "If this were real, admin privileges would be granted.";
            echo "</div>";
        }
    }
    
    public function __wakeup() {
        // Magic method called during unserialize
        echo "<div style='background:#fff3cd;padding:10px;margin:10px 0;'>";
        echo "__wakeup() called for user: " . $this->username;
        echo "</div>";
    }
}

if (isset($_POST['data'])) {
    $data = $_POST['data'];
    
    echo "<h3>Deserialization Result:</h3>";
    echo "<pre>";
    
    // VULNERABLE: Direct unserialize of user input
    $obj = unserialize($data);
    
    if ($obj) {
        echo "Object created: " . htmlspecialchars(print_r($obj, true));
        
        // Check if it's a User object
        if ($obj instanceof User) {
            echo "\n\nObject string representation:\n";
            echo $obj;
        }
    } else {
        echo "Failed to deserialize data.";
    }
    
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Insecure Deserialization</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        textarea { width: 100%; height: 150px; font-family: monospace; }
        button { padding: 10px 20px; margin: 10px 0; }
        .payload { background: #333; color: #0f0; padding: 15px; margin: 10px 0; font-family: monospace; }
        .warning { background: #f8d7da; padding: 15px; margin: 20px 0; }
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
    <h2>⚠️ Insecure Deserialization Test</h2>
    
    <form method="POST">
        <textarea name="data" placeholder="Enter serialized data">
O:4:"User":2:{s:8:"username";s:5:"guest";s:7:"isAdmin";b:0;}
        </textarea><br>
        <button type="submit">Deserialize</button>
    </form>
    
    <div class="payload">
<strong>Malicious Payload (Set isAdmin to true):</strong><br>
O:4:"User":2:{s:8:"username";s:5:"admin";s:7:"isAdmin";b:1;}
    </div>
    
    <div class="payload">
<strong>Real-world PHP Object Injection Payload:</strong><br>
O:11:"ExampleClass":1:{s:4:"data";s:15:"malicious_code";}
    </div>
    
    <div class="warning">
        <strong>🎯 Attack Vectors:</strong>
        <ul>
            <li>PHP object injection via __wakeup() or __destruct()</li>
            <li>Remote code execution via gadget chains</li>
            <li>Authentication bypass</li>
            <li>Privilege escalation</li>
        </ul>
        
        <strong>💡 Prevention:</strong>
        <ul>
            <li>Use JSON instead of serialize()</li>
            <li>Implement digital signatures</li>
            <li>Use PHP's allowed_classes option</li>
            <li>Validate serialized data structure</li>
        </ul>
    </div>
    
    <div style="background: #d1ecf1; padding: 15px; margin: 20px 0;">
        <h3>Generate Serialized Objects:</h3>
        <?php
        $normal_user = new User();
        $normal_user->username = "user123";
        $normal_user->isAdmin = false;
        
        $admin_user = new User();
        $admin_user->username = "admin";
        $admin_user->isAdmin = true;
        
        echo "<strong>Normal User:</strong><br>";
        echo "<code>" . htmlspecialchars(serialize($normal_user)) . "</code><br><br>";
        
        echo "<strong>Admin User:</strong><br>";
        echo "<code>" . htmlspecialchars(serialize($admin_user)) . "</code>";
        ?>
    </div>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
</body>
</html>
