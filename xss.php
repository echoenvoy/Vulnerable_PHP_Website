<!DOCTYPE html>
<html>
<head>
    <title>XSS Test Page | Security Lab</title>
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
        
        .xss-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 600px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }
        
        .xss-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #9b59b6, #e74c3c, #3498db);
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
            width: 80px;
            height: 3px;
            background: #9b59b6;
            border-radius: 2px;
        }
        
        .search-form {
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
            border-color: #9b59b6;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(155, 89, 182, 0.2);
        }
        
        button[type="submit"] {
            background: linear-gradient(to right, #9b59b6, #8e44ad);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
        }
        
        button[type="submit"]:hover {
            background: linear-gradient(to right, #8e44ad, #7d3c98);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(155, 89, 182, 0.4);
        }
        
        .output-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            border: 2px solid #e1e8ed;
            min-height: 100px;
            word-wrap: break-word;
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
        
        .xss-examples {
            background: #e8f4fc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 25px;
            border: 1px solid #3498db;
        }
        
        .xss-examples h4 {
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
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 480px) {
            .xss-container {
                padding: 30px 25px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="xss-container">
        <h2>XSS Vulnerability Test</h2>
        
        <form method="GET" class="search-form">
            <input type="text" name="q" placeholder="Enter text to search (try XSS payloads)" 
                   value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        
        <?php
        if (isset($_GET['q'])) {
            echo '<div class="output-box">';
            echo '<h3>Search Results:</h3>';
            echo '<div style="color: #2c3e50; font-size: 18px; padding: 10px;">';
            
            // VULNERABLE CODE - No output encoding!
            echo "You searched for: " . $_GET['q'];
            
            echo '</div>';
            
            // Show raw input for educational purposes
            echo '<div style="margin-top: 20px; padding: 15px; background: #e74c3c; color: white; border-radius: 5px;">';
            echo '<strong>Raw Input:</strong> ' . htmlspecialchars($_GET['q']);
            echo '</div>';
            
            echo '</div>';
        } else {
            echo '<div class="output-box">';
            echo '<h3>Ready for testing!</h3>';
            echo '<p>Enter a search query above. Try XSS payloads like:</p>';
            echo '<ul style="margin: 10px 0 10px 20px; color: #2c3e50;">';
            echo '<li><code>&lt;script&gt;alert("XSS")&lt;/script&gt;</code></li>';
            echo '<li><code>&lt;img src=x onerror=alert(1)&gt;</code></li>';
            echo '<li><code>&lt;svg onload=alert("Hacked")&gt;</code></li>';
            echo '</ul>';
            echo '</div>';
        }
        ?>
        
        <div class="xss-examples">
            <h4>💡 Try These XSS Payloads:</h4>
            
            <div class="example-code">
                &lt;script&gt;alert('XSS')&lt;/script&gt;
            </div>
            
            <div class="example-code">
                &lt;img src=x onerror=alert(document.cookie)&gt;
            </div>
            
            <div class="example-code">
                &lt;svg onload=alert("Hacked")&gt;&lt;/svg&gt;
            </div>
            
            <div class="example-code">
                &lt;body onload=alert("Reflected XSS")&gt;
            </div>
        </div>
        
        <div class="warning-box">
            <p><strong>⚠️ SECURITY WARNING:</strong></p>
            <p>This page is intentionally vulnerable to Cross-Site Scripting (XSS).</p>
            <p><strong>Vulnerability:</strong> User input is directly echoed without encoding.</p>
            <p><em>Perfect for practicing XSS attacks and payloads!</em></p>
        </div>
        
        <a href="javascript:history.back()" class="back-link">← Go Back</a>
    </div>
</body>
</html>