<?php
/**
 * System & Router Diagnostic
 */

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Backend Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .test { margin: 15px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { border-left: 4px solid #28a745; }
        .error { border-left: 4px solid #dc3545; }
        .info { border-left: 4px solid #17a2b8; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 3px; overflow: auto; }
        h2 { color: #333; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; margin: 5px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔧 Backend Diagnostic</h1>
    
    <div class="test info">
        <h3>Test 1: Server Configuration</h3>
        <p><strong>mod_rewrite enabled:</strong> 
            <?php echo extension_loaded('mod_rewrite') ? '✓ Yes' : '⚠ Cannot detect (may still be enabled)'; ?>
        </p>
        <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        <p><strong>Current Script:</strong> <?php echo $_SERVER['SCRIPT_NAME']; ?></p>
        <p><strong>REQUEST_URI:</strong> <code><?php echo $_SERVER['REQUEST_URI']; ?></code></p>
    </div>
    
    <div class="test">
        <h3>Test 2: Can We Access API Directly?</h3>
        <p>Click the button to test if the router can handle requests:</p>
        <button onclick="testRouterDirect()">Test Router Direct</button>
        <div id="router-result"></div>
    </div>
    
    <div class="test">
        <h3>Test 3: Check .htaccess Files</h3>
        <p>Checking if .htaccess files are in place:</p>
        <ul id="htaccess-check"></ul>
    </div>

    <script>
        async function testRouterDirect() {
            const result = document.getElementById('router-result');
            result.innerHTML = '<p>Testing...</p>';
            
            try {
                // Try to access the test-router.php which calls router directly
                const response = await fetch('http://localhost/PetSupply_eCommerce/backend/test-router.php');
                const text = await response.text();
                
                result.innerHTML = `
                    <h4>Response (Status: ${response.status}):</h4>
                    <pre>${text.substring(0, 500)}</pre>
                `;
            } catch (error) {
                result.innerHTML = `<p style="color: red;">Error: ${error.message}</p>`;
            }
        }
        
        // Check .htaccess files
        async function checkHTAccess() {
            const files = [
                'http://localhost/PetSupply_eCommerce/backend/api/.htaccess',
                'http://localhost/PetSupply_eCommerce/backend/.htaccess'
            ];
            
            const list = document.getElementById('htaccess-check');
            
            for (const file of files) {
                const li = document.createElement('li');
                try {
                    const response = await fetch(file);
                    if (response.status === 200) {
                        li.innerHTML = `✓ <code>${file}</code> exists`;
                        li.style.color = 'green';
                    } else {
                        li.innerHTML = `✗ <code>${file}</code> (status: ${response.status})`;
                        li.style.color = 'red';
                    }
                } catch (error) {
                    li.innerHTML = `⚠ <code>${file}</code> - cannot verify`;
                    li.style.color = 'orange';
                }
                list.appendChild(li);
            }
        }
        
        window.addEventListener('load', checkHTAccess);
    </script>
</body>
</html>
