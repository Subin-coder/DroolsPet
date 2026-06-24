<?php
/**
 * Dashboard API
 * Analytics and reporting for admin dashboard
 */

$user = AuthMiddleware::adminOnly();
$order_model = new Order($pdo);

if ($request_method === 'GET') {
    // Get dashboard statistics
    $total_sales = $order_model->getTotalSales();
    $total_orders = $order_model->getOrderCount();
    
    // Get recent orders
    $recent_orders = $order_model->getAllOrders(5, 0);
    
    http_response_code(200);
    die(json_encode([
        'success' => true,
        'statistics' => [
            'total_sales' => $total_sales,
            'total_orders' => $total_orders,
            'recent_orders' => $recent_orders
        ]
    ]));
}

else {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

?>
