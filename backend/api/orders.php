<?php
/**
 * Orders API
 * Handle order management
 */

$input = json_decode(file_get_contents('php://input'), true);
$user = AuthMiddleware::authenticate();
$order_model = new Order($pdo);
$cart_model = new Cart($pdo);

if ($action && is_numeric($action) && $request_method === 'GET') {
    // Get single order
    $order = $order_model->getOrderById($action);
    
    if ($order && ($order['user_id'] == $user['user_id'] || $user['role'] === 'admin')) {
        http_response_code(200);
        die(json_encode(['success' => true, 'order' => $order]));
    } else {
        http_response_code(404);
        die(json_encode(['error' => 'Order not found']));
    }
}

else if ($request_method === 'GET') {
    // Get user orders
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 10;
    $offset = ($page - 1) * $limit;
    
    $orders = $order_model->getUserOrders($user['user_id'], $limit, $offset);
    
    http_response_code(200);
    die(json_encode(['success' => true, 'orders' => $orders]));
}

else if ($request_method === 'POST') {
    // Create order
    Validator::clearErrors();
    Validator::validateRequired($input['payment_method'] ?? '', 'payment_method');
    Validator::validateRequired($input['shipping_address'] ?? '', 'shipping_address');
    
    if (!empty(Validator::getErrors())) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }
    
    // Get cart items
    $cart_items = $cart_model->getCart($user['user_id']);
    
    if (empty($cart_items)) {
        http_response_code(400);
        die(json_encode(['error' => 'Cart is empty']));
    }
    
    // Calculate total
    $total_amount = 0;
    $order_items = [];
    
    foreach ($cart_items as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total_amount += $subtotal;
        
        $order_items[] = [
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['price'],
            'subtotal' => $subtotal
        ];
    }
    
    // Create order
    $order_result = $order_model->createOrder($user['user_id'], [
        'total_amount' => $total_amount,
        'payment_method' => $input['payment_method'],
        'shipping_address' => $input['shipping_address']
    ]);
    
    if (!$order_result['success']) {
        http_response_code(400);
        die(json_encode($order_result));
    }
    
    $order_id = $order_result['order_id'];
    
    // Add order items
    $items_result = $order_model->addOrderItems($order_id, $order_items);
    
    if (!$items_result['success']) {
        http_response_code(400);
        die(json_encode($items_result));
    }
    
    // Set payment status based on payment method
    if ($input['payment_method'] === 'cod') {
        $order_model->updatePaymentStatus($order_id, 'pending');
        $order_model->updateOrderStatus($order_id, 'pending');
    } else {
        $order_model->updatePaymentStatus($order_id, 'pending');
    }
    
    // Clear cart
    $cart_model->clearCart($user['user_id']);
    
    http_response_code(201);
    die(json_encode([
        'success' => true,
        'order_id' => $order_id,
        'order_number' => $order_result['order_number'],
        'total_amount' => $total_amount,
        'payment_method' => $input['payment_method'],
        'message' => 'Order created successfully'
    ]));
}

else {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

?>
