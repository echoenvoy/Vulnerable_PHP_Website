<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable File Upload | Security Lab</title>
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
            align-items: flex-start; /* Changed from center */
            padding: 40px 20px; /* Added more padding */
        }
        
        .upload-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
            margin-top: 20px; /* Added margin */
        }
        
        .upload-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #e74c3c, #f39c12, #3498db);
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
            background: #e74c3c;
            border-radius: 2px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        input[type="file"] {
            width: 100%;
            padding: 20px;
            border: 2px dashed #3498db;
            border-radius: 10px;
            background-color: #f8f9fa;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        input[type="file"]:hover {
            background-color: #e8f4fc;
            border-color: #2980b9;
        }
        
        button[type="submit"] {
            background: linear-gradient(to right, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-size: 18px;
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
        
        .result-box {
            margin-top: 25px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            border-left: 4px solid #2ecc71;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .error-box {
            margin-top: 25px;
            padding: 20px;
            background-color: #ffeaea;
            border-left: 4px solid #e74c3c;
            border-radius: 10px;
            color: #c0392b;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .warning-box {
            margin-top: 25px;
            padding: 20px;
            background-color: #fff9e6;
            border-left: 4px solid #f1c40f;
            border-radius: 10px;
            color: #7d6608;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .warning-box strong {
            color: #d35400;
        }
        
        .file-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #e1e8ed;
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
            .upload-container {
                padding: 30px 25px;
            }
            
            h2 {
                font-size: 24px;
            }
            
            button[type="submit"] {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h2>Vulnerable File Upload</h2>
        
        <form method="POST" enctype="multipart/form-data"> <!-- Removed action="" -->
            <input type="file" name="file" required>
            <button type="submit" name="upload">Upload File</button>
        </form>
        
        <?php
        // Display PHP upload errors if any
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                echo '<div class="error-box">';
                echo '<p><strong>Upload Error!</strong> ';
                
                if (isset($_FILES['file'])) {
                    switch ($_FILES['file']['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                            echo 'File exceeds upload_max_filesize directive in php.ini';
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            echo 'File exceeds MAX_FILE_SIZE directive in HTML form';
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            echo 'File was only partially uploaded';
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            echo 'No file was uploaded';
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            echo 'Missing temporary folder';
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            echo 'Failed to write file to disk';
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            echo 'PHP extension stopped the file upload';
                            break;
                        default:
                            echo 'Unknown upload error';
                    }
                } else {
                    echo 'No file selected or form error';
                }
                
                echo '</p></div>';
            } else {
                $file = $_FILES["file"]["name"];
                $target_dir = "uploads/";
                
                // Create uploads directory if it doesn't exist
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $target_file = $target_dir . basename($file);
                
                // Try to move uploaded file
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    echo '<div class="result-box">';
                    echo '<h3 style="color: #2ecc71; margin-bottom: 10px;">✓ Upload Successful!</h3>';
                    echo '<div class="file-info">';
                    echo '<p><strong>Filename:</strong> ' . htmlspecialchars($file) . '</p>';
                    echo '<p><strong>Size:</strong> ' . number_format($_FILES["file"]["size"] / 1024, 2) . ' KB</p>';
                    echo '<p><strong>Type:</strong> ' . htmlspecialchars($_FILES["file"]["type"]) . '</p>';
                    echo '<p><strong>Location:</strong> uploads/' . htmlspecialchars($file) . '</p>';
                    echo '</div>';
                    echo '<p style="margin-top: 10px; color: #e74c3c; font-weight: bold;">⚠️ No security checks were performed!</p>';
                    
                    // Provide direct link if it's a web accessible file
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $accessible_extensions = ['php', 'html', 'htm', 'txt', 'jpg', 'jpeg', 'png', 'gif', 'pdf'];
                    
                    if (in_array($extension, $accessible_extensions)) {
                        // Get current URL path
                        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                        $host = $_SERVER['HTTP_HOST'];
                        $script_path = dirname($_SERVER['PHP_SELF']);
                        
                        echo '<p style="margin-top: 15px;">';
                        echo '<strong>Direct URL:</strong> ';
                        echo '<a href="' . htmlspecialchars($target_file) . '" target="_blank" style="color: #3498db;">';
                        echo $protocol . '://' . $host . $script_path . '/' . $target_file;
                        echo '</a>';
                        echo '</p>';
                        
                        // Special message for PHP files
                        if ($extension === 'php') {
                            echo '<p style="margin-top: 10px; background: #ffcccc; padding: 10px; border-radius: 5px;">';
                            echo '🚨 <strong>WARNING:</strong> PHP file uploaded! You can execute it at the URL above.';
                            echo '</p>';
                        }
                    }
                    echo '</div>';
                } else {
                    echo '<div class="error-box">';
                    echo '<p><strong>Upload failed!</strong> Could not move uploaded file to destination.</p>';
                    echo '<p>Check directory permissions: uploads/ folder must be writable (chmod 777).</p>';
                    echo '</div>';
                }
            }
        }
        ?>
        
        <div class="warning-box">
            <p><strong>⚠️ SECURITY WARNING:</strong></p>
            <p>This file upload is intentionally vulnerable for training purposes:</p>
            <ul style="margin: 10px 0 10px 20px; color: #7d6608;">
                <li>No file type restrictions</li>
                <li>No size limitations</li>
                <li>No virus scanning</li>
                <li>Can upload .php web shells</li>
                <li>No file name sanitization</li>
            </ul>
            <p><em>Perfect for practicing file upload vulnerabilities!</em></p>
            
            <?php
            // Show current upload settings
            echo '<div style="margin-top: 15px; padding: 10px; background: #f0f0f0; border-radius: 5px;">';
            echo '<p><strong>Current PHP Settings:</strong></p>';
            echo '<p>upload_max_filesize: ' . ini_get('upload_max_filesize') . '</p>';
            echo '<p>post_max_size: ' . ini_get('post_max_size') . '</p>';
            echo '<p>max_file_uploads: ' . ini_get('max_file_uploads') . '</p>';
            echo '</div>';
            ?>
        </div>
        
        <a href="nav.php" class="back-link">← Back to Main Menu</a>
    </div>
</body>
</html>