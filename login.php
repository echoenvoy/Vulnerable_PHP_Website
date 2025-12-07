<?php
include "config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 500px;
        }
        
        .message-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .success-icon {
            color: #00ff88;
            text-shadow: 0 0 20px #00ff88;
        }
        
        .error-icon {
            color: #ff3366;
            text-shadow: 0 0 20px #ff3366;
        }
        
        h1 {
            color: white;
            margin-bottom: 20px;
            font-size: 2.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .success-message {
            color: #00ff88;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }
        
        .error-message {
            color: #ff3366;
            text-shadow: 0 0 10px rgba(255, 51, 102, 0.5);
        }
        
        .hacker-text {
            background: linear-gradient(90deg, #00ff88, #00ccff, #ff00ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 15px 0;
        }
        
        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 30px;
            background: linear-gradient(45deg, #00ff88, #00ccff);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 255, 136, 0.6);
        }
        
        .btn-error {
            background: linear-gradient(45deg, #ff3366, #ff9966);
            box-shadow: 0 5px 15px rgba(255, 51, 102, 0.4);
        }
        
        .btn-error:hover {
            box-shadow: 0 8px 20px rgba(255, 51, 102, 0.6);
        }
        
        .glitch {
            position: relative;
            animation: glitch 3s infinite;
        }
        
        @keyframes glitch {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
        }
        
        .typing-effect {
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #00ff88;
            animation: typing 3s steps(30, end), blink 0.8s infinite;
            margin: 20px auto;
            width: fit-content;
        }
        
        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }
        
        @keyframes blink {
            50% { border-color: transparent }
        }
        
        .matrix-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <!-- Matrix rain background effect -->
    <canvas class="matrix-bg" id="matrixCanvas"></canvas>
    
    <div class="container">
        <div class="message-card">
            <?php if (mysqli_num_rows($result) == 1): ?>
                <!-- SUCCESS MESSAGE -->
                <div class="icon success-icon">⚡</div>
                <h1 class="glitch">ACCESS GRANTED</h1>
                <div class="typing-effect">
                    <h4 class="success-message">Congrats! You Successfully Hacked The Site!</h4>
                </div>
                <div class="hacker-text">System Breach Detected • Root Access Granted</div>
                <a href="javascript:history.back()" class="btn">← Go Back</a>
                <p style="color: rgba(255, 255, 255, 0.7); margin-top: 20px; font-size: 0.9rem;">
                    Welcome back, <span style="color: #00ff88; font-weight: bold;"><?php echo htmlspecialchars($username); ?></span>!
                </p>
                
            <?php else: ?>
                <!-- ERROR MESSAGE -->
                <div class="icon error-icon">✗</div>
                <h1 class="glitch">ACCESS DENIED</h1>
                <div class="typing-effect">
                    <h2 class="error-message">Something Went Wrong! Try Again</h2>
                </div>
                <div class="hacker-text">Intrusion Attempt Failed • Security Alert</div>
                <a href="javascript:history.back()" class="btn btn-error">TRY AGAIN</a>
                <p style="color: rgba(255, 255, 255, 0.7); margin-top: 20px; font-size: 0.9rem;">
                    Invalid credentials detected
                </p>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Matrix rain background effect
        const canvas = document.getElementById('matrixCanvas');
        const ctx = canvas.getContext('2d');
        
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789$+-*/=%"#@&;';
        const fontSize = 14;
        const columns = canvas.width / fontSize;
        const drops = [];
        
        for(let i = 0; i < columns; i++) {
            drops[i] = Math.floor(Math.random() * canvas.height / fontSize) * fontSize;
        }
        
        function drawMatrix() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.fillStyle = '#0F0';
            ctx.font = fontSize + 'px monospace';
            
            for(let i = 0; i < drops.length; i++) {
                const text = letters[Math.floor(Math.random() * letters.length)];
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                
                if(drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                    drops[i] = 0;
                }
                drops[i]++;
            }
        }
        
        setInterval(drawMatrix, 50);
        
        // Window resize handler
        window.addEventListener('resize', function() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
        
        // Add glitch effect on hover
        document.querySelectorAll('.glitch').forEach(element => {
            element.addEventListener('mouseover', function() {
                this.style.animation = 'glitch 0.3s infinite';
            });
            element.addEventListener('mouseout', function() {
                this.style.animation = 'glitch 3s infinite';
            });
        });
    </script>
</body>
</html>