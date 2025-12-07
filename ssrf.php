<?php
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    
    // VULNERABLE: Direct file_get_contents with user input
    echo "<h3>Fetching: " . htmlspecialchars($url) . "</h3>";
    echo "<pre>";
    
    // Try multiple methods
    $content = @file_get_contents($url);
    if ($content !== false) {
        echo htmlspecialchars($content);
    } else {
        // Try cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $content = curl_exec($ch);
        curl_close($ch);
        
        if ($content) {
            echo htmlspecialchars($content);
        } else {
            echo "Failed to fetch URL\n";
            echo "Tried: " . htmlspecialchars($url);
        }
    }
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SSRF Test</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        input { width: 400px; padding: 10px; }
        button { padding: 10px 20px; }
        .warning { background: #f8d7da; color: #721c24; padding: 15px; margin: 20px 0; border: 1px solid #f5c6cb; }
        .example { background: #d1ecf1; padding: 10px; margin: 10px 0; }
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
    <h2>SSRF (Server-Side Request Forgery) Test</h2>
    
    <form method="GET">
        <input type="text" name="url" placeholder="Enter URL to fetch" value="http://example.com">
        <button type="submit">Fetch</button>
    </form>
    
    <div class="warning">
        <strong>⚠️ SSRF Payloads to Try:</strong>
        <ul>
            <li><code>file:///etc/passwd</code></li>
            <li><code>http://localhost/admin</code></li>
            <li><code>http://127.0.0.1:22</code> (SSH banner grab)</li>
            <li><code>http://169.254.169.254/latest/meta-data/</code> (AWS metadata)</li>
            <li><code>http://[::1]:3306</code> (MySQL)</li>
            <li><code>dict://localhost:11211/stat</code> (Memcached)</li>
        </ul>
    </div>
    
    <div class="example">
        <strong>Impact:</strong> Access internal services, read local files, port scan internal network
    </div>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
</body>
</html>
