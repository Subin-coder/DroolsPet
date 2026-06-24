<?php
/**
 * Quick API Test
 * Tests if the API endpoints are working now
 */

header('Content-Type: text/html; charset=utf-8');

// Test the API
$test_url = 'http://localhost/PetSupply_eCommerce/backend/api/categories';

$response = @file_get_contents($test_url);

?>
<!DOCTYPE html>
<html>
<head>
    <title>API Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .test { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .success { border-left: 4px solid #28a745; }
        .error { border-left: 4px solid #dc3545; }
        pre { background: #f4f4f4; padding: 10px; overflow: auto; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="test">
        <h2>📡 API Test</h2>
        
        <?php if ($response === false): ?>
            <div class="error">
                <h3>✗ Cannot access API</h3>
                <p>The API is not responding. Possible reasons:</p>
                <ul>
                    <li>mod_rewrite is not enabled</li>
                    <li>.htaccess is not being read</li>
                    <li>index.php is missing in /backend/api/</li>
                </ul>
            </div>
        <?php else: ?>
            <?php 
                try {
                    $json = json_decode($response, true);
                    if ($json !== null):
            ?>
                <div class="success">
                    <h3>✓ API is working!</h3>
                    <p>Response from: <code><?php echo $test_url; ?></code></p>
                    <pre><?php echo json_encode($json, JSON_PRETTY_PRINT); ?></pre>
                    <p><a href="frontend/index.html" style="color: #007bff; text-decoration: underline;">Go to store</a></p>
                </div>
            <?php 
                    else:
            ?>
                <div class="error">
                    <h3>✗ API returned invalid JSON</h3>
                    <p>Raw response:</p>
                    <pre><?php echo htmlspecialchars(substr($response, 0, 500)); ?></pre>
                </div>
            <?php 
                    endif;
                } catch (Exception $e):
            ?>
                <div class="error">
                    <h3>✗ Error: <?php echo $e->getMessage(); ?></h3>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <hr>
        <button onclick="testAllEndpoints()">Test All Endpoints in Console</button>
    </div>

    <script>
        async function testAllEndpoints() {
            console.clear();
            console.log('Testing all API endpoints...\n');
            
            const endpoints = [
                'http://localhost/PetSupply_eCommerce/backend/api/categories',
                'http://localhost/PetSupply_eCommerce/backend/api/products',
            ];
            
            for (const url of endpoints) {
                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    console.log(`✓ ${url}`);
                    console.log(data);
                } catch (error) {
                    console.error(`✗ ${url}`, error.message);
                }
            }
        }
    </script>
</body>
</html>
