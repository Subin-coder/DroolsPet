<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

// $pidx = $_GET['pidx'] ?? null;

// if (!$pidx) {
//     echo json_encode(["success" => false, "message" => "Missing pidx"]);
//     exit;
// }

$order_id = $_GET['order_id'] ?? '';

if (!$order_id) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing order_id"]);
    exit;
}

/**
 * Khalti return varies by integration. We try to obtain pidx from:
 * - query string: ?pidx=...
 * - POST body (some flows POST back)
 * - raw body JSON (if Khalti sends JSON)
 */
$pidx = $_GET['pidx'] ?? null;

if (!$pidx && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = json_decode(file_get_contents('php://input'), true);
    if (is_array($raw)) {
        $pidx = $raw['pidx'] ?? ($raw['transaction_id'] ?? null);
    }
    $pidx = $pidx ?? ($_POST['pidx'] ?? ($_POST['transaction_id'] ?? null));
}

if (!$pidx) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing pidx"]);
    exit;
}

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../config/database.php';

$order_model = null;

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://dev.khalti.com/api/v2/epayment/lookup/", 
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode(["pidx" => $pidx]),
    // CURLOPT_POSTFIELDS => json_encode(["pidx" => $pidx]),
    CURLOPT_HTTPHEADER => [
        "Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

if (!$response) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "No response from Khalti"]);
    exit;
}

$data = json_decode($response, true);

if (!is_array($data)) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Invalid Khalti response"]);
    exit;
}


$status = $data["status"] ?? null;

// ----------- IMPORTANT PART -------------
if ($status === "Completed") {
    $order_model = new Order($pdo);
    $order_model->updatePaymentStatus($order_id, 'completed');
    $order_model->updateOrderStatus($order_id, 'processing');

    header("Location: http://localhost/PetSupply_eCommerce/frontend/order-confirmation.html?order_id=" . urlencode($order_id) . "&status=success");
    exit;

} else {
    if (!empty($order_id)) {
        $order_model = new Order($pdo);
        $order_model->updatePaymentStatus($order_id, 'failed');
    }

    header("Location: http://localhost/PetSupply_eCommerce/frontend/order-confirmation.html?order_id=" . urlencode($order_id) . "&status=failure");
    exit;
}

