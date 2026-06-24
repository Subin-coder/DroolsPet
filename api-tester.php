<?php
/**
 * API Response Tester
 * Check what the API is actually returning
 */

header('Content-Type: text/html; charset=utf-8');

$api_base = 'http://localhost/PetSupply_eCommerce/backend/api';

?>
<!DOCTYPE html>
<html>
<head>
    <title>API Response Tester</title>
    <style>
        body { font-family: monospace; max-width: 1200px; margin: 20px auto; padding: 20px; }
        .test { margin: 20px 0; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .endpoint { font-weight: bold; color: #007bff; margin-bottom: 10px; }
        .response { background: #f4f4f4; padding: 10px; border-radius: 3px; max-height: 300px; overflow: auto; white-space: pre-wrap; word-break: break-all; font-size: 12px; }
        .success { border-left: 4px solid #28a745; }
        .error { border-left: 4px solid #dc3545; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 14px; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔍 API Response Tester</h1>
    <p>This tool shows what the API is actually returning:</p>
    
    <button onclick="testAPI()">Test All Endpoints</button>
    
    <div id="results"></div>

    <script>
        async function testAPI() {
            const results = document.getElementById('results');
            results.innerHTML = '';
            
            const endpoints = [
                { name: 'GET /categories', url: '/categories' },
                { name: 'GET /products', url: '/products' },
                { name: 'POST /auth/login', url: '/auth/login', method: 'POST', body: { email: 'admin@example.com', password: 'admin123' } },
            ];
            
            for (const endpoint of endpoints) {
                const test = document.createElement('div');
                test.className = 'test';
                test.innerHTML = `<div class="endpoint">${endpoint.name}</div><div id="test-${endpoints.indexOf(endpoint)}" class="response">Loading...</div>`;
                results.appendChild(test);
                
                try {
                    const options = {
                        method: endpoint.method || 'GET',
                        headers: { 'Content-Type': 'application/json' }
                    };
                    
                    if (endpoint.body) {
                        options.body = JSON.stringify(endpoint.body);
                    }
                    
                    const response = await fetch('http://localhost/PetSupply_eCommerce/backend/api' + endpoint.url, options);
                    const text = await response.text();
                    
                    const resultDiv = document.getElementById(`test-${endpoints.indexOf(endpoint)}`);
                    
                    try {
                        const json = JSON.parse(text);
                        test.classList.add('success');
                        resultDiv.textContent = `Status: ${response.status}\n\n${JSON.stringify(json, null, 2)}`;
                    } catch (e) {
                        test.classList.add('error');
                        resultDiv.textContent = `Status: ${response.status}\nNOT JSON - Raw Response:\n\n${text.substring(0, 500)}`;
                    }
                } catch (error) {
                    const resultDiv = document.getElementById(`test-${endpoints.indexOf(endpoint)}`);
                    test.classList.add('error');
                    resultDiv.textContent = `Network Error: ${error.message}`;
                }
            }
        }
        
        // Test on load
        window.addEventListener('load', testAPI);
    </script>
</body>
</html>
