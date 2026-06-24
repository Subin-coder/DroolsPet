<?php
/**
 * eSewa Sandbox Simulation
 * Simulates eSewa payment gateway
 */

require_once __DIR__ . '/../config/database.php';

$transaction_id = $_GET['tx'] ?? '';
$amount = $_GET['amt'] ?? 0;
$action = $_GET['action'] ?? '';
$order_id = $_GET['oid'] ?? '';

// Redirect to appropriate page based on simulation
if ($action === 'fail' || (isset($_POST['fail']))) {
    header('Location: ' . ESEWA_FAILURE_URL . '?tx=' . $transaction_id . '&status=failed&order_id=' . $order_id);
} else {
    // Success by default
    header('Location: ' . ESEWA_SUCCESS_URL . '?tx=' . $transaction_id . '&status=completed&amt=' . $amount . '&order_id=' . $order_id);
}

?>
