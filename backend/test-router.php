<?php
/**
 * Direct Router Test
 * Test if the router can be called directly
 */

header('Content-Type: application/json; charset=utf-8');

// Simulate a categories request
$_SERVER['REQUEST_URI'] = '/PetSupply_eCommerce/backend/api/categories';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once __DIR__ . '/api/router.php';
?>
