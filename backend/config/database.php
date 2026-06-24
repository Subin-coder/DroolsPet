<?php
/**
 * Database Configuration
 * Configure your database connection here
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'petsupply_ecommerce');
define('DB_PORT', 3306);

// API Configuration
define('API_BASE_URL', 'http://localhost/PetSupply_eCommerce/backend/api');
define('FRONTEND_URL', 'http://localhost/PetSupply_eCommerce/frontend');

// JWT Configuration
define('JWT_SECRET', 'your-secret-key-change-in-production-12345');
define('JWT_EXPIRATION', 86400); // 24 hours in seconds

// Payment Gateway Configuration
define('ESEWA_MERCHANT_CODE', 'EPAYTEST');
define('ESEWA_SUCCESS_URL', FRONTEND_URL . '/checkout-success.html');
define('ESEWA_FAILURE_URL', FRONTEND_URL . '/checkout-failure.html');
define('ESEWA_SANDBOX_URL', 'http://localhost/PetSupply_eCommerce/backend/api/esewa-sandbox.php');

// Create database connection
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}

?>
