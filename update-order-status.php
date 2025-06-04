<?php
require_once 'connection.php';

$orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

$response = ['success' => false];

if ($orderId > 0 && $status) {
    $sql = "UPDATE decadhen.orders SET OrderStatus = ? WHERE OrderID = ?";
    $stmt = sqlsrv_query($conn, $sql, [$status, $orderId]);
    if ($stmt) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);