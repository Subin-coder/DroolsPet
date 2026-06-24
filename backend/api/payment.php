<?php
/**
 * Payment API
 * Handle payment gateway integration (Khalti)
 */

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/Validator.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../config/database.php';

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$action = 'initiate';
$request_method = $_SERVER['REQUEST_METHOD'];

if ($action === 'initiate' && $request_method === 'POST') {
    // Initiate payment
    $user = AuthMiddleware::authenticate();

    Validator::clearErrors();
    Validator::validateRequired($input['order_id'] ?? '', 'order_id');
    Validator::validatePositive($input['amount'] ?? 0, 'amount');

    if (!empty(Validator::getErrors())) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }

    $order_id = $input['order_id'];
    $amount_rupees = (float) ($input['amount'] ?? 0);
    $amount_paisa = (int) round($amount_rupees * 100);

    $purchase_order_id = 'ORD_' . $order_id . '_' . time();

    // Khalti initiate payload
    $khalti_secret_key = 'Key live_secret_key_68791341fdd94846a146f0457ff7b455';
    $payload = [
        // Khalti returns to this URL. We'll also pass order_id so we can update the order after lookup.
        'return_url' => 'http://localhost/PetSupply_eCommerce/backend/api/payment/payment-response.php?order_id=' . urlencode($order_id),
        'website_url' => 'http://localhost/PetSupply_eCommerce/',
        'amount' => $amount_paisa,
        'purchase_order_id' => $purchase_order_id,
        'purchase_order_name' => 'Order ' . $purchase_order_id,
        'customer_info' => [
            'name' => $input['name'] ?? '',
            'email' => $input['email'] ?? ''
        ]
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://dev.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . $khalti_secret_key,
            'Content-Type: application/json'
        ]
    ]);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_code !== 200) {
        http_response_code(400);
        die(json_encode(['error' => 'Khalti initiation failed', 'details' => $response]));
    }

    $response_data = json_decode($response, true);
    // Khalti's initiate response contains `payment_url`
    $redirect_url = $response_data['payment_url'] ?? null;

    if (!$redirect_url) {
        http_response_code(400);
        die(json_encode([
            'error' => 'Khalti initiation missing payment_url',
            'details' => $response_data,
            'raw' => $response
        ]));
    }

    http_response_code(200);
    die(json_encode([
        'success' => true,
        'transaction_id' => $purchase_order_id,
        'redirect_url' => $redirect_url,
        'message' => 'Payment initiated'
    ]));
}

/* Khalti callback via return_url is handled by backend/api/payment/payment-response.php */
else if ($action === 'callback' && $request_method === 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Not used - use payment-response.php']));
}

else if ($action === 'verify' && $request_method === 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Not used - use payment-response.php']));
}

else {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}
?>

