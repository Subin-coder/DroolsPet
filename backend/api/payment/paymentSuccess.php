<?php
session_start();

// -------------------------------
// Database Connection
// -------------------------------
require_once 'config.php';


// -------------------------------
// Read pidx from GET
// -------------------------------
$pidx = $_GET["pidx"] ?? null;
if (!$pidx) {
    die("Invalid Request: missing pidx");
}

// -------------------------------
// CALL KHALTI LOOKUP API
// -------------------------------
$lookup_payload = json_encode(["pidx" => $pidx]);
$khalti_key = "Key live_secret_key_68791341fdd94846a146f0457ff7b455";

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://dev.khalti.com/api/v2/epayment/lookup/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $lookup_payload,
    CURLOPT_HTTPHEADER => [
        "Authorization: $khalti_key",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

// -------------------------------
// Verify Payment
// -------------------------------
if (!isset($data["status"]) || $data["status"] !== "Completed") {
    echo "<h1>Payment Failed ❌</h1>";
    echo "<p>Status: " . ($data['status'] ?? 'unknown') . "</p>";
    exit;
}

// -------------------------------
// Get signup data
// -------------------------------
$signup = $_SESSION["signup_data"] ?? [
    'name' => $data['customer_info']['name'] ?? '',
    'email' => $data['customer_info']['email'] ?? '',
    'password' => ''
];

// Generate password if missing
if (empty($signup['password'])) {
    $signup['password'] = bin2hex(random_bytes(6));
}

$plainPassword = $signup['password'];
$name  = $conn->real_escape_string($signup["name"]);
$email = $conn->real_escape_string($signup["email"]);
$passwordHash = password_hash($plainPassword, PASSWORD_BCRYPT);

// -------------------------------
// CHECK IF USER EXISTS
// -------------------------------
$check = $conn->prepare("SELECT id, subscription_expiry FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($existing = $result->fetch_assoc()) {

    // ===============================
    // 🔁 RENEW SUBSCRIPTION
    // ===============================
    $userId = $existing["id"];

    $sql = "
        UPDATE users
        SET
            account_type_premium = 1,
            subscription_expiry =
                IF(
                    subscription_expiry IS NOT NULL AND subscription_expiry > NOW(),
                    DATE_ADD(subscription_expiry, INTERVAL 30 DAY),
                    DATE_ADD(NOW(), INTERVAL 30 DAY)
                )
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

} else {

    // ===============================
    // 🆕 NEW PREMIUM USER
    // ===============================
    $expiryDate = date("Y-m-d H:i:s", strtotime("+30 days"));

    $sql = "
        INSERT INTO users (
            name,
            email,
            password,
            account_type_premium,
            subscription_expiry,
            compression_tries,
            conversion_tries,
            created_at
        )
        VALUES (
            '$name',
            '$email',
            '$passwordHash',
            1,
            '$expiryDate',
            0,
            0,
            NOW()
        )
    ";

    if (!$conn->query($sql)) {
        echo "Database Error: " . $conn->error;
        exit;
    }
}

// -------------------------------
// Redirect to login (auto-fill)
// -------------------------------
$url = "http://localhost:5173/login?"
     . "name=" . urlencode($name)
     . "&email=" . urlencode($email)
     . "&pw=" . urlencode($plainPassword);

header("Location: $url");
exit;
