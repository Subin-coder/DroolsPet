<?php
/**
 * Comprehensive System Diagnostic
 * Check all components of the system
 */

header('Content-Type: application/json; charset=utf-8');

$diagnostics = [
    'php' => [],
    'database' => [],
    'files' => [],
    'api_endpoints' => [],
    'errors' => [],
    'summary' => ''
];

// 1. Check PHP
$diagnostics['php']['version'] = phpversion();
$diagnostics['php']['extensions'] = [
    'PDO' => extension_loaded('pdo'),
    'PDO_MySQL' => extension_loaded('pdo_mysql'),
    'JSON' => extension_loaded('json'),
    'Curl' => extension_loaded('curl')
];

// 2. Check files exist
$files_to_check = [
    'backend/config/database.php' => __DIR__ . '/backend/config/database.php',
    'backend/api/router.php' => __DIR__ . '/backend/api/router.php',
    'backend/api/auth.php' => __DIR__ . '/backend/api/auth.php',
    'backend/models/User.php' => __DIR__ . '/backend/models/User.php',
    'database/schema.sql' => __DIR__ . '/database/schema.sql'
];

foreach ($files_to_check as $name => $path) {
    $diagnostics['files'][$name] = file_exists($path) ? 'EXISTS' : 'MISSING';
}

// 3. Check database connection
try {
    require_once __DIR__ . '/backend/config/database.php';
    $diagnostics['database']['connection'] = 'SUCCESS';
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $diagnostics['database']['tables'] = $tables;
    
    // Check specific tables
    $required = ['users', 'products', 'categories', 'orders', 'cart'];
    $diagnostics['database']['required_tables'] = [];
    foreach ($required as $table) {
        $diagnostics['database']['required_tables'][$table] = in_array($table, $tables) ? 'EXISTS' : 'MISSING';
    }
    
    // Count data
    foreach (['users', 'products', 'categories'] as $table) {
        if (in_array($table, $tables)) {
            try {
                $result = $pdo->query("SELECT COUNT(*) as count FROM $table");
                $count = $result->fetch()['count'] ?? 0;
                $diagnostics['database']["${table}_count"] = $count;
            } catch (Exception $e) {
                $diagnostics['database']["${table}_count"] = 'Error: ' . $e->getMessage();
            }
        }
    }
    
} catch (Exception $e) {
    $diagnostics['database']['connection'] = 'FAILED';
    $diagnostics['database']['error'] = $e->getMessage();
    $diagnostics['errors'][] = 'Database connection failed: ' . $e->getMessage();
}

// 4. Test API endpoints
$api_base = 'http://localhost/PetSupply_eCommerce/backend/api';
$endpoints_to_test = [
    'GET /categories' => $api_base . '/categories',
    'GET /products' => $api_base . '/products',
];

foreach ($endpoints_to_test as $name => $url) {
    $result = @file_get_contents($url);
    if ($result !== false) {
        $decoded = @json_decode($result, true);
        $diagnostics['api_endpoints'][$name] = [
            'status' => 'RESPONDING',
            'response' => is_array($decoded) ? 'Valid JSON' : 'Invalid response'
        ];
    } else {
        $diagnostics['api_endpoints'][$name] = 'NOT RESPONDING';
        $diagnostics['errors'][] = "API endpoint not responding: $name";
    }
}

// 5. Generate summary
$critical_issues = [];

if (!extension_loaded('pdo_mysql')) {
    $critical_issues[] = 'PDO MySQL extension not loaded';
}

if ($diagnostics['database']['connection'] === 'FAILED') {
    $critical_issues[] = 'Database connection failed';
}

if ($diagnostics['database']['connection'] === 'SUCCESS') {
    foreach (['users', 'products', 'categories'] as $table) {
        if (($diagnostics['database']['required_tables'][$table] ?? null) === 'MISSING') {
            $critical_issues[] = "Database table missing: $table";
        }
    }
}

if (empty($critical_issues)) {
    $diagnostics['summary'] = 'System appears to be working properly. Check error messages above or browser console for details.';
} else {
    $diagnostics['summary'] = 'CRITICAL ISSUES FOUND: ' . implode('; ', $critical_issues);
}

echo json_encode($diagnostics, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
