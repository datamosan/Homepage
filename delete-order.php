<?php
require_once 'connection.php';

$orderId = isset($_POST['orderId']) ? intval($_POST['orderId']) : 0;
$response = ['success' => false];

if ($orderId > 0) {
    // Delete order details first if you have foreign key constraints
    sqlsrv_query($conn, "DELETE FROM decadhen.orderdetails WHERE OrderID = ?", [$orderId]);
    $result = sqlsrv_query($conn, "DELETE FROM decadhen.orders WHERE OrderID = ?", [$orderId]);
    if ($result) {
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);