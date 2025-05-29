<?php
require_once 'connection.php';

$orderId = isset($_GET['orderId']) ? intval($_GET['orderId']) : 0;
$response = [];

if ($orderId > 0) {
    $sql = "SELECT p.ProductName, od.OrderQuantity, od.Size, pa.Price
            FROM orderdetails od
            JOIN products p ON od.ProductID = p.ProductID
            LEFT JOIN product_attributes pa ON pa.ProductID = od.ProductID AND pa.Size = od.Size
            WHERE od.OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    $total = 0;
    while ($row = $result->fetch_assoc()) {
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
    $response = [
        'products' => $products,
        'total' => $total
    ];
}

header('Content-Type: application/json');
echo json_encode($response);