<?php
session_start();

// Determine the request origin dynamically
$allowed_origins = ['http://localhost:5173', 'http://localhost:3000']; // add your dev origins

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
}
`
// Required headers
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Content-Type: application/json');

// Handle OPTIONS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Read JSON from frontend
$input = json_decode(file_get_contents("php://input"), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(["error" => "No input"]);
    exit;
}

// store signup data in session
$_SESSION['signup_data'] = $input;

// ... prepare Khalti payload ...
// College project: use dummy Khalti integration (no real API call)
// If you later want real Khalti, replace this with the live/secret key and remove the dummy branch below.
$khalti_key = "DUMMY_KEY";

$payload = [
    "return_url" => "http://localhost/Project_II/paymentSuccess.php",
    "website_url" => "http://localhost/Project_II/",
    "amount" => 29900,
    "purchase_order_id" => "order_" . rand(100000, 999999),
    "purchase_order_name" => "Premium Account",
    "customer_info" => [
        "name" => $input["name"],
        "email" => $input["email"],
       
    ]
];

// Dummy Khalti: return a local success redirect (no real Khalti API call)
// This keeps checkout working for the college project.
$fake = [
    'status' => 'success',
    'redirect_url' => 'checkout-success.html'
];
echo json_encode($fake);
exit;

// call Khalti initiate
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://dev.khalti.com/api/v2/epayment/initiate/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        "Authorization: $khalti_key",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response;
