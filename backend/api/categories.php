<?php
/**
 * Categories API
 * Handle category management
 */

$input = json_decode(file_get_contents('php://input'), true);
$category_model = new Category($pdo);

if ($action && is_numeric($action) && $request_method === 'GET') {
    // Get single category
    $category = $category_model->getCategoryById($action);
    
    if ($category) {
        http_response_code(200);
        die(json_encode(['success' => true, 'category' => $category]));
    } else {
        http_response_code(404);
        die(json_encode(['error' => 'Category not found']));
    }
}

else if ($request_method === 'GET') {
    // Get all categories
    $categories = $category_model->getAllCategories();
    
    http_response_code(200);
    die(json_encode(['success' => true, 'categories' => $categories]));
}

else if ($request_method === 'POST') {
    // Add category (admin only)
    $user = AuthMiddleware::adminOnly();
    
    // Validate input
    Validator::clearErrors();
    Validator::validateRequired($input['name'] ?? '', 'name');
    
    if (!empty(Validator::getErrors())) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }
    
    $result = $category_model->addCategory($input);
    
    if ($result['success']) {
        http_response_code(201);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action && is_numeric($action) && $request_method === 'PUT') {
    // Update category (admin only)
    $user = AuthMiddleware::adminOnly();
    
    $result = $category_model->updateCategory($action, $input);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action && is_numeric($action) && $request_method === 'DELETE') {
    // Delete category (admin only)
    $user = AuthMiddleware::adminOnly();
    
    $result = $category_model->deleteCategory($action);
    
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
