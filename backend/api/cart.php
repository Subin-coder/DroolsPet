<?php
/**
 * Cart API
 * Handle shopping cart operations
 */

$input = json_decode(file_get_contents('php://input'), true);
$user = AuthMiddleware::authenticate();
$cart_model = new Cart($pdo);

if ($request_method === 'GET') {
    // Get user cart
    $cart = $cart_model->getCart($user['user_id']);
    $total = $cart_model->getCartTotal($user['user_id']);
    
    http_response_code(200);
    die(json_encode([
        'success' => true,
        'cart' => $cart,
        'total' => $total,
        'count' => count($cart)
    ]));
}

else if ($request_method === 'POST') {
    // Add to cart
    Validator::clearErrors();
    Validator::validateRequired($input['product_id'] ?? '', 'product_id');
    Validator::validatePositive($input['quantity'] ?? 0, 'quantity');
    
    if (!empty(Validator::getErrors())) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }
    
    $result = $cart_model->addToCart(
        $user['user_id'],
        $input['product_id'],
        $input['quantity'] ?? 1
    );
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action === 'update' && $request_method === 'PUT') {
    // Update cart item quantity
    Validator::clearErrors();
    Validator::validateRequired($input['product_id'] ?? '', 'product_id');
    Validator::validatePositive($input['quantity'] ?? 0, 'quantity');
    
    if (!empty(Validator::getErrors())) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }
    
    $result = $cart_model->updateQuantity(
        $user['user_id'],
        $input['product_id'],
        $input['quantity']
    );
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action && is_numeric($action) && $request_method === 'DELETE') {
    // Remove from cart
    $result = $cart_model->removeFromCart($user['user_id'], $action);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action === 'clear' && $request_method === 'DELETE') {
    // Clear entire cart
    $result = $cart_model->clearCart($user['user_id']);
    
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
