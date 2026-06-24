<?php
/**
 * Auto Database Setup Script
 * Initialize database and tables
 */

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? 'setup';
    
    if ($action === 'setup') {
        setupDatabase();
    } elseif ($action === 'load_sample') {
        loadSampleData();
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
    exit;
}

// Show HTML interface
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>PetSupply Database Setup</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
            .container { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
            h1 { color: #333; }
            .status { padding: 10px; margin: 10px 0; border-radius: 3px; }
            .success { background: #d4edda; color: #155724; }
            .error { background: #f8d7da; color: #721c24; }
            .warning { background: #fff3cd; color: #856404; }
            .info { background: #d1ecf1; color: #0c5460; }
            button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
            button:hover { background: #0056b3; }
            pre { background: #f4f4f4; padding: 10px; border-radius: 3px; overflow-x: auto; }
            .spinner { animation: spin 1s linear infinite; display: inline-block; }
            @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🐾 PetSupply Database Setup</h1>
            
            <div id="output"></div>
            
            <button onclick="runSetup()">Initialize Database</button>
            <button onclick="loadSampleData()" style="background: #28a745;">Load Sample Data</button>
            <button onclick="location.reload()" style="background: #6c757d;">Refresh</button>
        </div>

        <script>
            async function runSetup() {
                const output = document.getElementById('output');
                output.innerHTML = '<div class="status info"><span class="spinner">⏳</span> Setting up database...</div>';
                
                try {
                    const response = await fetch('setup-db.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'setup' })
                    });
                    
                    const text = await response.text();
                    
                    try {
                        const result = JSON.parse(text);
                        
                        if (result.success) {
                            output.innerHTML = `
                                <div class="status success">✓ Database setup complete!</div>
                                <p><strong>Tables created:</strong> ${result.tables_created.join(', ')}</p>
                                <p>Next step: Click "Load Sample Data" button below</p>
                                <p><a href="health-check.php" style="color: #007bff; text-decoration: underline;">View system status</a></p>
                            `;
                        } else {
                            output.innerHTML = `
                                <div class="status error">✗ Error: ${result.error}</div>
                                <pre>${result.details || JSON.stringify(result.errors || [], null, 2)}</pre>
                            `;
                        }
                    } catch (parseErr) {
                        output.innerHTML = `
                            <div class="status error">✗ Response parsing error</div>
                            <p>Raw response:</p>
                            <pre style="max-height: 300px; overflow: auto;">${text}</pre>
                        `;
                    }
                } catch (error) {
                    output.innerHTML = `<div class="status error">✗ Error: ${error.message}</div>`;
                }
            }

            async function loadSampleData() {
                const output = document.getElementById('output');
                output.innerHTML = '<div class="status info"><span class="spinner">⏳</span> Loading sample data...</div>';
                
                try {
                    const response = await fetch('setup-db.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'load_sample' })
                    });
                    
                    const text = await response.text();
                    
                    try {
                        const result = JSON.parse(text);
                        
                        if (result.success) {
                            output.innerHTML = `
                                <div class="status success">✓ Sample data loaded!</div>
                                <p>${result.message}</p>
                                <p style="color: green; font-weight: bold;">${result.next_step}</p>
                                <p><a href="frontend/index.html" style="color: #007bff; text-decoration: underline;">Click here to view the store</a></p>
                            `;
                        } else {
                            output.innerHTML = `<div class="status warning">⚠ ${result.error}</div><pre>${JSON.stringify(result.errors || [], null, 2)}</pre>`;
                        }
                    } catch (parseErr) {
                        output.innerHTML = `
                            <div class="status error">✗ Response parsing error</div>
                            <p>Raw response:</p>
                            <pre style="max-height: 300px; overflow: auto;">${text}</pre>
                        `;
                    }
                } catch (error) {
                    output.innerHTML = `<div class="status error">✗ Error: ${error.message}</div>`;
                }
            }
        </script>
    </body>
    </html>
    <?php
}

function setupDatabase() {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_port = 3306;
    $db_name = 'petsupply_ecommerce';
    
    // Create connection without database
    $conn = new mysqli($db_host, $db_user, $db_password, '', $db_port);
    
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database connection failed',
            'details' => $conn->connect_error
        ]);
        return;
    }
    
    // Read schema
    $schema_file = __DIR__ . '/database/schema.sql';
    if (!file_exists($schema_file)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'schema.sql not found',
            'details' => "Expected at: $schema_file"
        ]);
        return;
    }
    
    $schema = file_get_contents($schema_file);
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    $executed = 0;
    $errors = [];
    
    foreach ($statements as $statement) {
        if (empty($statement)) continue;
        
        if (!$conn->query($statement)) {
            $errors[] = $conn->error;
        } else {
            $executed++;
        }
    }
    
    // Verify tables created
    $verify = $conn->query("SHOW TABLES FROM $db_name");
    $tables = [];
    if ($verify) {
        while ($row = $verify->fetch_row()) {
            $tables[] = $row[0];
        }
    }
    
    $conn->close();
    
    http_response_code(200);
    echo json_encode([
        'success' => empty($errors),
        'executed_statements' => $executed,
        'tables_created' => $tables,
        'errors' => $errors,
        'message' => 'Database setup complete. Now load sample data!'
    ]);
}

function loadSampleData() {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_port = 3306;
    $db_name = 'petsupply_ecommerce';
    
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);
    
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Database connection failed',
            'details' => $conn->connect_error
        ]);
        return;
    }
    
    $sample_file = __DIR__ . '/database/sample_data.sql';
    if (!file_exists($sample_file)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'sample_data.sql not found'
        ]);
        return;
    }
    
    $sample = file_get_contents($sample_file);
    
    // Remove USE statement
    $sample = preg_replace('/USE\s+[^;]+;/i', '', $sample);
    
    $statements = array_filter(array_map('trim', explode(';', $sample)));
    
    $executed = 0;
    $errors = [];
    
    foreach ($statements as $statement) {
        if (empty($statement)) continue;
        
        if (!$conn->query($statement)) {
            $errors[] = $conn->error;
        } else {
            $executed++;
        }
    }
    
    $conn->close();
    
    http_response_code(200);
    echo json_encode([
        'success' => count($errors) === 0,
        'message' => "Sample data loaded! $executed statements executed",
        'executed' => $executed,
        'errors' => $errors,
        'next_step' => 'Refresh the store page to see products'
    ]);
}
?>
