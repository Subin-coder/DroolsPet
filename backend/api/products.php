<?php
/**
 * Products API
 * Handle product management
 */

$input = json_decode(file_get_contents('php://input'), true);
$product_model = new Product($pdo);

if ($action && is_numeric($action) && $request_method === 'GET') {
    // Get single product
    $product = $product_model->getProductById($action);
    
    if ($product) {
        http_response_code(200);
        die(json_encode(['success' => true, 'product' => $product]));
    } else {
        http_response_code(404);
        die(json_encode(['error' => 'Product not found']));
    }
}

else if ($request_method === 'GET') {
    // Get products list
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 20;
    $offset = ($page - 1) * $limit;
    $category_id = $_GET['category_id'] ?? null;
    $search = $_GET['search'] ?? null;
    
    if ($search) {
        $products = $product_model->searchProducts($search);
    } else {
        $products = $product_model->getAllProducts($limit, $offset, $category_id);
    }
    
    http_response_code(200);
    die(json_encode(['success' => true, 'products' => $products, 'total' => count($products)]));
}

else if ($request_method === 'POST') {
    // Add product (admin only)
    $user = AuthMiddleware::adminOnly();
    
    // Validate input
    Validator::clearErrors();
    Validator::validateRequired($input['name'] ?? '', 'name');
    Validator::validateRequired($input['category_id'] ?? '', 'category_id');
    Validator::validatePositive($input['price'] ?? 0, 'price');
    
    if (!empty(Validator::getErrors())) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }
    
    $result = $product_model->addProduct($input);
    
    if ($result['success']) {
        http_response_code(201);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action && is_numeric($action) && $request_method === 'PUT') {
    // Update product (admin only)
    $user = AuthMiddleware::adminOnly();
    
    $result = $product_model->updateProduct($action, $input);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action && is_numeric($action) && $request_method === 'DELETE') {
    // Delete product (admin only)
    $user = AuthMiddleware::adminOnly();
    
    $result = $product_model->deleteProduct($action);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

?>
