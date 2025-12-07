<!DOCTYPE html>
<html>
<head>
    <title>Path Traversal Test | Security Lab</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
         /* body {
            background: linear-gradient(135deg, #1a2a3a 0%, #2c3e50 100%);
            min-height: 100vh;
            justify-content: center;
            padding: 20px;
            display: block; }
         */
        body {
            background: linear-gradient(135deg, #1a2a3a 0%, #2c3e50 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Changed from center to flex-start */
            padding: 40px 20px; /* Increased top padding */
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 700px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
            margin-top: 20px; /* Add this */
        }
                
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #f39c12, #e67e22, #d35400);
        }
        
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: #f39c12;
            border-radius: 2px;
        }
        
        .file-form {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 16px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            color: #333;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #f39c12;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.2);
        }
        
        button[type="submit"] {
            background: linear-gradient(to right, #f39c12, #e67e22);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }
        
        button[type="submit"]:hover {
            background: linear-gradient(to right, #e67e22, #d35400);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
        }
        
        .output-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            border: 2px solid #e1e8ed;
            min-height: 150px;
            word-wrap: break-word;
            overflow-x: auto;
        }
        
        .output-box h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .warning-box {
            padding: 20px;
            background-color: #fff9e6;
            border-left: 4px solid #f1c40f;
            border-radius: 10px;
            color: #7d6608;
            margin-top: 20px;
        }
        
        .warning-box strong {
            color: #d35400;
        }
        
        .examples-box {
            background: #fef5e7;
            border-radius: 10px;
            padding: 20px;
            margin-top: 25px;
            border: 1px solid #f39c12;
        }
        
        .examples-box h4 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .example-code {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            margin: 10px 0;
            overflow-x: auto;
            font-size: 14px;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 14px;
            margin-top: 10px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 25px;
            }
            
            .file-form {
                flex-direction: column;
            }
            
            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Path Traversal Vulnerability Test</h2>
        
        <form method="GET" class="file-form">
            <input type="text" name="file" 
                   placeholder="Enter filename to read (e.g., test.txt)" 
                   value="<?php echo isset($_GET['file']) ? htmlspecialchars($_GET['file']) : 'test.txt'; ?>">
            <button type="submit">Read File</button>
        </form>
        
        <?php
        // Create a test file if it doesn't exist
        $test_file = "test.txt";
        if (!file_exists($test_file)) {
            file_put_contents($test_file, "This is a test file for path traversal vulnerability.\n\nTry reading:\n- /etc/passwd (Linux)\n- C:\\Windows\\win.ini (Windows)\n- ../../../../etc/passwd\n- ..\\..\\..\\windows\\win.ini\n\nSECRET FLAG: PATH_TRAVERSAL_EXPLOIT_SUCCESS_{" . bin2hex(random_bytes(8)) . "}");
        }
        
        // Create a secrets file
        $secret_file = "secret_data.txt";
        if (!file_exists($secret_file)) {
            file_put_contents($secret_file, "CONFIDENTIAL INFORMATION\n=======================\n\nDatabase Credentials:\n- Host: localhost\n- User: root\n- Password: SuperSecret123!\n\nAPI Keys:\n- Stripe: sk_live_xyz123\n- AWS: AKIAIOSFODNN7EXAMPLE\n\nSSH Private Key:\n-----BEGIN RSA PRIVATE KEY-----\nMIIEogIBAAKCAQEArX7r8u3m...\n-----END RSA PRIVATE KEY-----\n\nFLAG: SECRET_DATA_LEAKED_{" . bin2hex(random_bytes(8)) . "}");
        }
        
        if (isset($_GET['file'])) {
            echo '<div class="output-box">';
            echo '<h3>File Content:</h3>';
            
            // VULNERABLE CODE - Direct file inclusion without validation!
            $filename = $_GET['file'];
            
            echo '<p><strong>Requested file:</strong> ' . htmlspecialchars($filename) . '</p>';
            echo '<p><strong>Full path:</strong> ' . htmlspecialchars(realpath(dirname(__FILE__) . '/' . $filename)) . '</p>';
            echo '<hr style="margin: 15px 0; border-color: #e1e8ed;">';
            
            // Try to read the file
            if (file_exists($filename)) {
                $content = file_get_contents($filename);
                if ($content !== false) {
                    echo '<pre>' . htmlspecialchars($content) . '</pre>';
                } else {
                    echo '<p style="color: #e74c3c;">Could not read the file.</p>';
                }
            } else {
                // Try with relative path traversal
                echo '<p style="color: #e74c3c;">File not found in current directory.</p>';
                echo '<p>Trying to read: ' . htmlspecialchars($filename) . '</p>';
                
                // Still vulnerable - we're showing the attempt
                $attempted_path = dirname(__FILE__) . '/' . $filename;
                if (file_exists($attempted_path)) {
                    $content = file_get_contents($attempted_path);
                    echo '<p style="color: #27ae60;">File found via path traversal!</p>';
                    echo '<pre>' . htmlspecialchars($content) . '</pre>';
                } else {
                    echo '<p style="color: #e74c3c;">File does not exist at: ' . htmlspecialchars($attempted_path) . '</p>';
                }
            }
            
            echo '</div>';
        } else {
            echo '<div class="output-box">';
            echo '<h3>Ready for testing!</h3>';
            echo '<p>Enter a filename to read. Try path traversal payloads like:</p>';
            echo '<ul style="margin: 10px 0 10px 20px; color: #2c3e50;">';
            echo '<li><code>../../../../etc/passwd</code> (Linux)</li>';
            echo '<li><code>..\\..\\..\\windows\\win.ini</code> (Windows)</li>';
            echo '<li><code>secret_data.txt</code> (Local secret file)</li>';
            echo '<li><code>/etc/shadow</code> (Linux password hashes)</li>';
            echo '</ul>';
            echo '</div>';
        }
        ?>
        
        <div class="examples-box">
            <h4>💡 Try These Path Traversal Payloads:</h4>
            
            <div class="example-code">
                ../../../../etc/passwd
            </div>
            
            <div class="example-code">
                ..\..\..\windows\win.ini
            </div>
            
            <div class="example-code">
                secret_data.txt
            </div>
            
            <div class="example-code">
                ../../../etc/shadow
            </div>
            
            <div class="example-code">
                ../../../../../../../../etc/hosts
            </div>
            
            <div class="example-code">
                %2e%2e%2f%2e%2e%2fetc%2fpasswd (URL encoded)
            </div>
        </div>
        
        <div class="warning-box">
            <p><strong>⚠️ SECURITY WARNING:</strong></p>
            <p>This page is intentionally vulnerable to Path Traversal (Directory Traversal) attacks.</p>
            <p><strong>Vulnerability:</strong> User input is directly used in file operations without validation.</p>
            <p><strong>Impact:</strong> Attackers can read arbitrary files on the server.</p>
            <p><em>Perfect for practicing file path traversal attacks!</em></p>
        </div>
        
        <a href="nav.php" class="back-link">← Back to Main Menu</a>
    </div>
</body>
</html>
