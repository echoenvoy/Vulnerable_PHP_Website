<!DOCTYPE html>
<html>
<head>
    <title>Security Training Lab</title>
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
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #3498db, #2ecc71, #e74c3c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1000px;
            width: 100%;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-10px);
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }
        
        .card.sql::before {
            background: linear-gradient(90deg, #3498db, #2980b9);
        }
        
        .card.xss::before {
            background: linear-gradient(90deg, #9b59b6, #8e44ad);
        }
        
        .card.upload::before {
            background: linear-gradient(90deg, #e74c3c, #c0392b);
        }
        
        .icon {
            font-size: 50px;
            margin-bottom: 20px;
        }
        
        .card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .card p {
            color: #7f8c8d;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .btn-xss {
            background: linear-gradient(45deg, #9b59b6, #8e44ad);
        }
        
        .btn-upload {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }
        .btn-traversal {
            background: linear-gradient(45deg, #3c5ee7ff, #2ba0c0ff);
        }
        .card.traversal::before {
            background: linear-gradient(90deg, #f39c12, #e67e22);
        }

        .card.command::before {
            background: linear-gradient(90deg, #e74c3c, #c0392b);
        }

        .card.idor::before {
            background: linear-gradient(90deg, #17a2b8, #138496);
        }

        .card.ssrf::before {
            background: linear-gradient(90deg, #20c997, #17a589);
        }

        .card.xxe::before {
            background: linear-gradient(90deg, #6f42c1, #5a3d8a);
        }

        .card.deserialize::before {
            background: linear-gradient(90deg, #fd7e14, #e6640d);
        }

        .card.ssti::before {
            background: linear-gradient(90deg, #dc3545, #c82333);
        }

        .card.race::before {
            background: linear-gradient(90deg, #6610f2, #520dc2);
        }

        .card.csrf::before {
            background: linear-gradient(90deg, #ffc107, #e0a800);
        }

        .card.redirect::before {
            background: linear-gradient(90deg, #28a745, #1e7e34);
        }

        .card.jwt::before {
            background: linear-gradient(90deg, #343a40, #23272b);
        }

        .warning {
            max-width: 800px;
            margin-top: 40px;
            padding: 20px;
            background: rgba(231, 76, 60, 0.1);
            border: 2px solid #e74c3c;
            border-radius: 10px;
            color: white;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔒 Security Training Lab</h1>
        <p class="subtitle">Intentionally Vulnerable Web Application for Cybersecurity Practice</p>
    </div>
    
    <div class="grid-container">
        <div class="card sql">
            <div class="icon">💉</div>
            <h3>SQL Injection</h3>
            <p>Practice SQL injection attacks on a vulnerable login form. Bypass authentication and extract data from the database.</p>
            <a href="index.php" class="btn">Test SQL Injection</a>
        </div>
        
        <div class="card xss">
            <div class="icon">🕷️</div>
            <h3>XSS Attacks</h3>
            <p>Test Cross-Site Scripting vulnerabilities. Execute JavaScript in the context of the vulnerable page.</p>
            <p>    </p>
            <a href="xss.php" class="btn btn-xss">Test XSS</a>
        </div>
        
        <div class="card upload">
            <div class="icon">📤</div>
            <h3>File Upload</h3>
            <p>Upload malicious files including PHP web shells. Exploit unrestricted  <br> file upload vulnerabilities of the page .</p>
            <a href="upload.php" class="btn btn-upload">Test File Upload</a>
        </div>

        <div class="card traversal">
            <div class="icon">📂</div>
            <h3>Path Traversal</h3>
            <p>Test Directory Traversal vulnerabilities. Read arbitrary files from the server filesystem.</p>
            <a href="path_traversal.php" class="btn btn-traversal" style="background: linear-gradient(45deg, #f39c12, #e67e22);">Test Path Traversal</a>
        </div>

        <div class="card command">
            <div class="icon">💻</div>
            <h3>Command Injection</h3>
            <p>Execute system commands through vulnerable input fields. Gain shell access through web forms.</p>
            <a href="command_injection.php" class="btn" style="background: linear-gradient(45deg, #e74c3c, #c0392b);">Test Command Injection</a>
        </div>

        <div class="card idor">
            <div class="icon">👤</div>
            <h3>IDOR</h3>
            <p>Insecure Direct Object Reference. Access unauthorized data by manipulating object references.</p>
            <a href="idor.php" class="btn" style="background: linear-gradient(45deg, #17a2b8, #138496);">Test IDOR</a>
        </div>

        <div class="card ssrf">
            <div class="icon">🌐</div>
            <h3>SSRF</h3>
            <p>Server-Side Request Forgery. Make the server request internal resources or external sites.</p>
            <a href="ssrf.php" class="btn" style="background: linear-gradient(45deg, #20c997, #17a589);">Test SSRF</a>
        </div>

        <div class="card xxe">
            <div class="icon">📄</div>
            <h3>XXE</h3>
            <p>XML External Entity. Exploit XML parsers to read files, perform SSRF, or cause DoS.</p>
            <a href="xxe.php" class="btn" style="background: linear-gradient(45deg, #6f42c1, #5a3d8a);">Test XXE</a>
        </div>

        <div class="card deserialize">
            <div class="icon">🔄</div>
            <h3>Deserialization</h3>
            <p>Insecure Deserialization. Execute code through malicious serialized objects.</p>
            <a href="deserialization.php" class="btn" style="background: linear-gradient(45deg, #fd7e14, #e6640d);">Test Deserialization</a>
        </div>

        <div class="card ssti">
            <div class="icon">📝</div>
            <h3>SSTI</h3>
            <p>Server-Side Template Injection. Inject code into template engines for RCE.</p>
            <a href="ssti.php" class="btn" style="background: linear-gradient(45deg, #dc3545, #c82333);">Test SSTI</a>
        </div>

        <div class="card race">
            <div class="icon">⚡</div>
            <h3>Race Condition</h3>
            <p>Exploit timing vulnerabilities in concurrent operations. Test double spending, TOCTOU.</p>
            <a href="race_condition.php" class="btn" style="background: linear-gradient(45deg, #6610f2, #520dc2);">Test Race Condition</a>
        </div>

        <div class="card csrf">
            <div class="icon">🔄</div>
            <h3>CSRF</h3>
            <p>Cross-Site Request Forgery. Execute unauthorized actions on behalf of authenticated users.</p>
            <a href="csrf.php" class="btn" style="background: linear-gradient(45deg, #ffc107, #e0a800);">Test CSRF</a>
        </div>

        <div class="card redirect">
            <div class="icon">🔗</div>
            <h3>Open Redirect</h3>
            <p>Redirect users to malicious sites. Used in phishing attacks and SSO bypass.</p>
            <a href="open_redirect.php" class="btn" style="background: linear-gradient(45deg, #28a745, #1e7e34);">Test Open Redirect</a>
        </div>

        <div class="card jwt">
            <div class="icon">🔑</div>
            <h3>JWT Vulnerabilities</h3>
            <p>JSON Web Token attacks. Test alg:none, weak secrets, KID injection, and more.</p>
            <a href="jwt.php" class="btn" style="background: linear-gradient(45deg, #343a40, #23272b);">Test JWT</a>
        </div>
    
    </div>

    
    <div class="warning">
        <strong>⚠️ IMPORTANT DISCLAIMER:</strong>
        <p>This website contains intentional vulnerabilities for educational purposes only.</p>
        <p>Do not use these techniques on websites you do not own or have permission to test.</p>
    </div>
</body>
</html>