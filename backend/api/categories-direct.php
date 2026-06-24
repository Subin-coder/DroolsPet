<?php
/**
 * API Categories Endpoint
 * Fallback direct endpoint (works without .htaccess)
 */

require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Category.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

$category_model = new Category($pdo);

if ($request_method === 'GET' && !$action && !$id) {
    // Get all categories
    $result = $category_model->getAll();
    
    if ($result) {
        http_response_code(200);
        die(json_encode(['success' => true, 'categories' => $result]));
    } else {
        http_response_code(500);
        die(json_encode(['error' => 'Failed to fetch categories']));
    }
}

elseif ($request_method === 'GET' && $id) {
    // Get specific category
    $result = $category_model->getById($id);
    
    if ($result) {
        http_response_code(200);
        die(json_encode(['success' => true, 'category' => $result]));
    } else {
        http_response_code(404);
        die(json_encode(['error' => 'Category not found']));
    }
}

else {
    http_response_code(404);
    die(json_encode(['error' => 'Endpoint not found']));
}
?>
