<?php
session_start();
require_once "connection.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_item_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $cart_id = $user_id;
    $cart_item_id = intval($_POST['cart_item_id']);

    // Delete the cart item
    $delete_stmt = sqlsrv_query($conn, "DELETE FROM decadhen.CartItems WHERE CartItemID = ? AND CartID = ?", [$cart_item_id, $cart_id]);

    // Get updated cart total
    $total_stmt = sqlsrv_query($conn, "SELECT SUM(CartQuantity * UnitPrice) AS cart_total FROM decadhen.CartItems WHERE CartID = ?", [$cart_id]);
    $cart_total = 0;
    if ($total_stmt && $row = sqlsrv_fetch_array($total_stmt, SQLSRV_FETCH_ASSOC)) {
        $cart_total = $row['cart_total'] ?? 0;
    }

    // Update the cart's updated date (use GETDATE() for SQL Server)
    sqlsrv_query($conn, "UPDATE decadhen.Cart SET UpdatedDate = GETDATE() WHERE CartID = ?", [$cart_id]);

    echo json_encode([
        'success' => true,
        'cart_total' => $cart_total
    ]);
    exit;
}
echo json_encode(['success' => false]);
exit;
?>