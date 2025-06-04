<?php
require_once 'connection.php';

$orderId = isset($_GET['orderId']) ? intval($_GET['orderId']) : 0;
$response = [];

if ($orderId > 0) {
    $sql = "SELECT p.ProductName, od.OrderQuantity, od.Size, pa.Price
            FROM decadhen.orderdetails od
            JOIN decadhen.products p ON od.ProductID = p.ProductID
            LEFT JOIN decadhen.product_attributes pa ON pa.ProductID = od.ProductID AND pa.Size = od.Size
            WHERE od.OrderID = ?";
    $params = [$orderId];
    $stmt = sqlsrv_query($conn, $sql, $params);

    $products = [];
    $total = 0;
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $subtotal = $row['OrderQuantity'] * $row['Price'];
            $products[] = [
                'name' => $row['ProductName'],
                'size' => $row['Size'],
                'qty' => $row['OrderQuantity'],
                'price' => $row['Price'],
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }
    }
    $response = [
        'products' => $products,
        'total' => $total
    ];
}

header('Content-Type: application/json');
echo json_encode($response);