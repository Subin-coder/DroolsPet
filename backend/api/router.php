<?php
/**
 * API Router
 * Route API requests to appropriate endpoints
 */

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/JWTHandler.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/Validator.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';

// Parse request
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Extract API endpoint - handle both clean URLs and query parameter routing
$base_path = '/PetSupply_eCommerce/backend/api';
$endpoint = str_replace($base_path, '', $request_uri);
$endpoint = trim($endpoint, '/');

// Fallback: check if endpoint is empty and try query parameters
if (empty($endpoint) && !empty($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];
}

$parts = explode('/', $endpoint);

$route = $parts[0] ?? '';
$action = $parts[1] ?? '';
$id = $parts[2] ?? '';

// Route to appropriate controller
switch ($route) {
    case 'auth':
        require_once __DIR__ . '/auth.php';
        break;
    
    case 'products':
        require_once __DIR__ . '/products.php';
        break;
    
    case 'categories':
        require_once __DIR__ . '/categories.php';
        break;
    
    case 'cart':
        require_once __DIR__ . '/cart.php';
        break;
    
    case 'orders':
        require_once __DIR__ . '/orders.php';
        break;
    
    case 'payment':
        require_once __DIR__ . '/payment.php';
        break;
    
    case 'admin':
        require_once __DIR__ . '/admin.php';
        break;
    
    case 'dashboard':
        require_once __DIR__ . '/dashboard.php';
        break;
    
    case '':
        http_response_code(200);
        die(json_encode(['message' => 'Pet Supply E-Commerce API v1.0']));
    
    default:
        http_response_code(404);
        die(json_encode(['error' => 'Endpoint not found']));
}

?>
