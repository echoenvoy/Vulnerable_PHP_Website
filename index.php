<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable Login | Security Lab</title>
    <style>
        /* Keep the existing beautiful styles */
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
        
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #3498db, #2ecc71, #e74c3c);
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
            width: 60px;
            height: 3px;
            background: #3498db;
            border-radius: 2px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        input[type="text"] {
            width: 100%;
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
            border-color: #3498db;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        input[type="text"]::placeholder {
            color: #95a5a6;
        }
        
        button[type="submit"] {
            background: linear-gradient(to right, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        button[type="submit"]:hover {
            background: linear-gradient(to right, #2980b9, #1f6396);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        
        button[type="submit"]:active {
            transform: translateY(0);
        }
        
        .security-notice {
            margin-top: 25px;
            padding: 15px;
            background-color: #fff9e6;
            border-left: 4px solid #f1c40f;
            border-radius: 5px;
            font-size: 14px;
            color: #7d6608;
            text-align: center;
        }
        
        .security-notice strong {
            color: #d35400;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
            }
            
            h2 {
                font-size: 24px;
            }
            
            input[type="text"], button[type="submit"] {
                padding: 14px 18px;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-container {
            animation: fadeIn 0.6s ease-out;
        }
        
        input:focus, button:focus {
            outline: 2px solid #3498db;
            outline-offset: 2px;
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
        
    </style> 
</head>
<body>
    <div class="login-container">
        <h2>Vulnerable Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="javascript:history.back()" class="back-link">← Go Back</a>
        <div class="security-notice">
            <strong>⚠️ SECURITY WARNING:</strong> This login form is intentionally vulnerable for training purposes.
        </div>
    </div>
</body>
</html>