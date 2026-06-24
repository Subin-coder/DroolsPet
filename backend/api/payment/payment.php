<?php
session_start();
header('Content-Type: application/json');

// ================= CONFIGURATION =================
$environment = 'sandbox';
$khalti_config = [
    'sandbox' => [
        'api_url' => 'https://dev.khalti.com/api/v2/',
        'secret_key' => '14509adc517743ce8f8eb6bd83bf7082'
    ]
];

if (!array_key_exists($environment, $khalti_config)) {
    die(json_encode(['success' => false, 'message' => 'Invalid environment']));
}

$api_base = $khalti_config[$environment]['api_url'];
$secret_key = $khalti_config[$environment]['secret_key'];

// ================= DATABASE CONNECTION =================
$conn = new mysqli("localhost", "root", "", "compressor");
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// ================= PAYMENT INITIATION =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'initiate') {
    
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    // Generate a purchase reference
    $purchase_order_id = 'PREM_' . time() . '_' . $user_id . '_' . uniqid();
    $amount = 5000; // Example: Rs. 50.00 for premium in paisa

    $payload = [
        'return_url' => 'http://localhost/Project_II/payment.php', // adjust
        'website_url' => 'http://localhost/Project_II/',
        'amount' => $amount,
        'purchase_order_id' => $purchase_order_id,
        'purchase_order_name' => 'Premium Signup',
        'customer_info' => [
            'name' => $_SESSION['username'] ?? 'Guest',
            'email' => $_SESSION['email'] ?? 'guest@example.com'
        ]
    ];

    // Initiate Khalti payment
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_base . 'epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Authorization: Key ' . $secret_key,
            'Content-Type: application/json'
        ]
    ]);
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_code !== 200) {
        echo json_encode(['success' => false, 'message' => 'Payment initiation failed']);
        exit;
    }

    $response_data = json_decode($response, true);
    $pidx = $response_data['pidx'];
    $payment_url = $response_data['payment_url'];

    // Save pending premium upgrade in DB
    $stmt = $conn->prepare("UPDATE users SET account_type_premium = 0, compression_tries = 0, pending_pidx = ? WHERE user_id = ?");
    $stmt->bind_param("si", $pidx, $user_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true, 'payment_url' => $payment_url]);
    exit;
}

// ================= PAYMENT VERIFICATION =================
if (isset($_GET['pidx'])) {
    $pidx = $_GET['pidx'];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_base . 'epayment/lookup/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
        CURLOPT_HTTPHEADER => [
            'Authorization: Key ' . $secret_key,
            'Content-Type: application/json'
        ]
    ]);
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_code !== 200) {
        die('Payment verification failed');
    }

    $payment_data = json_decode($response, true);
    $status = $payment_data['status'];

    if ($status === 'Completed') {
        // Activate premium and infinite tries
        $stmt = $conn->prepare("UPDATE users SET account_type_premium = 1, compression_tries = -1, pending_pidx = NULL WHERE pending_pidx = ?");
        $stmt->bind_param("s", $pidx);
        $stmt->execute();
        $stmt->close();

        header("Location: premium-success.php");
        exit;
    } else {
        header("Location: premium-failed.php");
        exit;
    }
}

?>
