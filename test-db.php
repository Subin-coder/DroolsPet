<?php
/**
 * Simple Database Connection Test
 */

header('Content-Type: application/json; charset=utf-8');

$result = [
    'database_exists' => false,
    'connection' => 'FAILED',
    'error' => '',
    'steps' => []
];

// Test 1: Can we connect to MySQL?
try {
    $conn = new mysqli('localhost', 'root', '', '', 3306);
    
    if ($conn->connect_error) {
        $result['error'] = 'Cannot connect to MySQL: ' . $conn->connect_error;
        echo json_encode($result);
        exit;
    }
    
    $result['steps'][] = '✓ Connected to MySQL server';
    
    // Test 2: Does the database exist?
    $db_result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'petsupply_ecommerce'");
    
    if ($db_result && $db_result->num_rows > 0) {
        $result['database_exists'] = true;
        $result['steps'][] = '✓ Database "petsupply_ecommerce" exists';
        
        // Test 3: Connect to the database
        $conn->select_db('petsupply_ecommerce');
        
        if ($conn->errno) {
            $result['error'] = 'Error selecting database: ' . $conn->error;
            echo json_encode($result);
            exit;
        }
        
        $result['steps'][] = '✓ Selected database "petsupply_ecommerce"';
        $result['connection'] = 'SUCCESS';
        
        // Test 4: Check tables
        $tables_query = $conn->query("SHOW TABLES");
        $tables = [];
        while ($row = $tables_query->fetch_row()) {
            $tables[] = $row[0];
        }
        
        $result['tables'] = $tables;
        $result['steps'][] = '✓ Found ' . count($tables) . ' tables: ' . implode(', ', $tables);
        
        // Test 5: Check for required tables
        $required = ['users', 'products', 'categories', 'orders', 'cart'];
        $missing = array_diff($required, $tables);
        
        if (!empty($missing)) {
            $result['error'] = 'Missing tables: ' . implode(', ', $missing);
            $result['steps'][] = '✗ Missing tables: ' . implode(', ', $missing);
            $result['steps'][] = '→ Run setup-db.php to create them';
        } else {
            $result['steps'][] = '✓ All required tables exist';
            
            // Count data
            foreach ($required as $table) {
                $count = $conn->query("SELECT COUNT(*) as cnt FROM $table")->fetch_assoc()['cnt'];
                $result['steps'][] = "  - $table: $count rows";
            }
        }
        
    } else {
        $result['error'] = 'Database "petsupply_ecommerce" does not exist';
        $result['steps'][] = '✗ Database "petsupply_ecommerce" NOT FOUND';
        $result['steps'][] = '→ Run setup-db.php to create it';
    }
    
    $conn->close();
    
} catch (Exception $e) {
    $result['error'] = 'Connection error: ' . $e->getMessage();
    $result['steps'][] = '✗ Error: ' . $e->getMessage();
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
