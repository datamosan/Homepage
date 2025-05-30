<?php
require_once 'connection.php';

$orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : 0;
$response = ['success' => false];

if ($orderId > 0) {
    // Delete order details first if you have foreign key constraints
    $conn->query("DELETE FROM orderdetails WHERE OrderID = $orderId");
    $result = $conn->query("DELETE FROM orders WHERE OrderID = $orderId");
    if ($result) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);