
<?php
include "config.php";

// Simulated user database
$users = [
    1 => ['id' => 1, 'name' => 'Admin', 'email' => 'admin@test.com', 'ssn' => '123-45-6789', 'salary' => '$100,000'],
    2 => ['id' => 2, 'name' => 'John Doe', 'email' => 'john@test.com', 'ssn' => '987-65-4321', 'salary' => '$50,000'],
    3 => ['id' => 3, 'name' => 'Jane Smith', 'email' => 'jane@test.com', 'ssn' => '456-78-9012', 'salary' => '$75,000'],
    4 => ['id' => 4, 'name' => 'Bob Wilson', 'email' => 'bob@test.com', 'ssn' => '321-54-9876', 'salary' => '$60,000']
];

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// VULNERABLE: No authorization check!
if (isset($users[$user_id])) {
    $user = $users[$user_id];
} else {
    $user = $users[1];
}

// Check if it's an AJAX request for sensitive data
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    header('Content-Type: application/json');
    echo json_encode($user);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>IDOR Vulnerability | User Profile</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .profile { background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto; }
        .sensitive { background: #ffe6e6; padding: 10px; border: 1px solid red; margin: 10px 0; }
        .controls { margin: 20px 0; }
        input { width: 50px; padding: 5px; }
        button { padding: 10px 20px; background: #3498db; color: white; border: none; cursor: pointer; }
        .hint { background: #fff3cd; padding: 10px; margin: 10px 0; border: 1px solid #ffeaa7; }
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
    <div class="profile">
        <h2>User Profile</h2>
        <div class="controls">
            <form method="GET">
                View User ID: <input type="number" name="id" value="<?php echo $user_id; ?>" min="1" max="10">
                <button type="submit">Load Profile</button>
            </form>
        </div>
        
        <div class="hint">
            <strong>💡 IDOR Test:</strong> Try changing the ID parameter to access other users' data
        </div>
        
        <h3>Public Information:</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        
        <h3>Sensitive Information (Should be protected):</h3>
        <div class="sensitive">
            <p><strong>Social Security Number:</strong> <?php echo htmlspecialchars($user['ssn']); ?></p>
            <p><strong>Salary:</strong> <?php echo htmlspecialchars($user['salary']); ?></p>
            <p><strong>Employee ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
        </div>
        
        <div style="margin-top: 20px;">
            <button onclick="loadSensitiveData()">Load Additional Sensitive Data (AJAX)</button>
            <div id="ajaxResult" style="margin-top: 10px; padding: 10px; background: #f8f9fa; display: none;"></div>
        </div>
        
        <div class="hint" style="margin-top: 20px;">
            <strong>⚠️ IDOR Vulnerability:</strong>
            <ul>
                <li>No session validation</li>
                <li>No authorization checks</li>
                <li>Direct object references exposed</li>
                <li>Sensitive data accessible via parameter manipulation</li>
            </ul>
        </div>
        <a href="nav.php" class="back-link">← Back to Main Menu</a>
    </div>
    
    <script>
    function loadSensitiveData() {
        const userId = <?php echo $user_id; ?>;
        fetch(`idor.php?id=${userId}&ajax=true`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('ajaxResult').innerHTML = 
                    `<strong>Full Profile Data (JSON):</strong><br>` +
                    `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                document.getElementById('ajaxResult').style.display = 'block';
            });
    }
    </script>
    
</body>
</html>
