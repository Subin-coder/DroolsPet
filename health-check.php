<?php
/**
 * Health Check Endpoint
 * Verify API and database connectivity
 */

require_once __DIR__ . '/backend/config/database.php';
require_once __DIR__ . '/backend/middleware/JWTHandler.php';

$status = [
    'api' => 'OK',
    'database' => 'Not connected',
    'tables' => []
];

try {
    // Check if we can query the database
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $status['database'] = 'Connected';
    $status['tables'] = $tables;
    
    // Check for required tables
    $required_tables = ['users', 'products', 'categories', 'orders', 'cart'];
    $missing = array_diff($required_tables, $tables);
    
    if (!empty($missing)) {
        $status['database'] = 'Connected but missing tables: ' . implode(', ', $missing);
    }
} catch (Exception $e) {
    $status['database'] = 'Error: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($status, JSON_PRETTY_PRINT);
?>
