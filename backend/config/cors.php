<?php
/**
 * CORS Configuration
 * Handle Cross-Origin Resource Sharing
 */

// Allowed origins
$allowed_origins = [
    'http://localhost',
    'http://localhost:8000',
    'http://localhost:80',
    'http://127.0.0.1'
];

// Get the origin of the request
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Check if the origin is allowed or if it's a same-origin request
$is_allowed = in_array($origin, $allowed_origins) || empty($origin);

if ($is_allowed) {
    if (!empty($origin)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('Access-Control-Allow-Origin: *');
    }
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Max-Age: 3600');
    header('Access-Control-Allow-Credentials: true');
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit();
}

// Set content type to JSON
header('Content-Type: application/json; charset=utf-8');

?>
