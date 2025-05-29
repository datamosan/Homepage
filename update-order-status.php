<?php
require_once 'connection.php';

$orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

$response = ['success' => false];

if ($orderId > 0 && $status) {
    $sql = "UPDATE orders SET OrderStatus = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $orderId);
    if ($stmt->execute()) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);