<?php
session_start();
require_once "connection.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_item_id'], $_POST['quantity'])) {
    $user_id = intval($_SESSION['user_id']);
    $cart_id = $user_id;
    $cart_item_id = intval($_POST['cart_item_id']);
    $quantity = max(1, intval($_POST['quantity']));

    // Update quantity
    $update_stmt = sqlsrv_query($conn, "UPDATE decadhen.CartItems SET CartQuantity = ? WHERE CartItemID = ? AND CartID = ?", [$quantity, $cart_item_id, $cart_id]);

    // Get updated subtotal and cart total
    $result = sqlsrv_query($conn, "SELECT CartQuantity, UnitPrice FROM decadhen.CartItems WHERE CartItemID = ? AND CartID = ?", [$cart_item_id, $cart_id]);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['success' => false, 'error' => 'Item not found']);
        exit;
    }
    $subtotal = $row['CartQuantity'] * $row['UnitPrice'];

    $total_result = sqlsrv_query($conn, "SELECT SUM(CartQuantity * UnitPrice) AS cart_total FROM decadhen.CartItems WHERE CartID = ?", [$cart_id]);
    $total_row = sqlsrv_fetch_array($total_result, SQLSRV_FETCH_ASSOC);
    $cart_total = $total_row['cart_total'] ?? 0;

    // Update cart updated date (use GETDATE() for SQL Server)
    sqlsrv_query($conn, "UPDATE decadhen.Cart SET UpdatedDate = GETDATE() WHERE CartID = ?", [$cart_id]);

    echo json_encode([
        'success' => true,
        'updated_quantity' => $quantity,
        'subtotal' => $subtotal,
        'cart_total' => $cart_total
    ]);
    exit;
}
echo json_encode(['success' => false]);
exit;