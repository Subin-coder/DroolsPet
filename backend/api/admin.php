<?php
/**
 * Admin API
 * Handle admin operations
 */

$input = json_decode(file_get_contents('php://input'), true);
$user = AuthMiddleware::adminOnly();

$user_model = new User($pdo);
$order_model = new Order($pdo);
$product_model = new Product($pdo);

// Users management
if ($route . '/' . $action === 'admin/users' && $request_method === 'GET') {
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 10;
    $offset = ($page - 1) * $limit;
    
    $users = $user_model->getAllUsers($limit, $offset);
    
    http_response_code(200);
    die(json_encode(['success' => true, 'users' => $users]));
}

else if ($route . '/' . $action === 'admin/users' && $action && is_numeric($id) && $request_method === 'DELETE') {
    // Delete user
    $result = $user_model->deleteUser($id);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

// Orders management
else if ($route . '/' . $action === 'admin/orders' && $request_method === 'GET') {
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 10;
    $offset = ($page - 1) * $limit;
    
    $orders = $order_model->getAllOrders($limit, $offset);
    
    http_response_code(200);
    die(json_encode(['success' => true, 'orders' => $orders]));
}

else if ($route . '/' . $action === 'admin/orders' && $action && is_numeric($id) && $request_method === 'PUT') {
    // Update order status
    $status = $input['status'] ?? '';
    
    Validator::clearErrors();
    Validator::validateRequired($status, 'status');
    
    if (!empty(Validator::getErrors())) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }
    
    $result = $order_model->updateOrderStatus($id, $status);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

// Products management (admin view)
else if ($route . '/' . $action === 'admin/products' && $request_method === 'GET') {
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 20;
    $offset = ($page - 1) * $limit;
    
    $products = $product_model->getAllProducts($limit, $offset);
    
    http_response_code(200);
    die(json_encode(['success' => true, 'products' => $products]));
}

else {
    http_response_code(404);
    die(json_encode(['error' => 'Admin endpoint not found']));
}

?>
