<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_item_id'])) {
    $user_id = intval($_SESSION['user_id']);
    $cart_id = $user_id;
    $cart_item_id = intval($_POST['cart_item_id']);

    $conn = new mysqli('localhost', 'root', '', 'decadhen');
    if ($conn->connect_error) die(json_encode(['success' => false]));

    $stmt = $conn->prepare("DELETE FROM CartItems WHERE CartItemID = ? AND CartID = ?");
    $stmt->bind_param('ii', $cart_item_id, $cart_id);
    $stmt->execute();
    $stmt->close();

    // Get updated cart total
    $total_result = $conn->query("SELECT SUM(CartQuantity * UnitPrice) AS cart_total FROM CartItems WHERE CartID = $cart_id");
    $cart_total = $total_result->fetch_assoc()['cart_total'] ?? 0;

    // Update the cart's updated date
    $conn->query("UPDATE Cart SET UpdatedDate = NOW() WHERE CartID = $cart_id");

    $conn->close();

    echo json_encode([
        'success' => true,
        'cart_total' => $cart_total
    ]);
    exit;
}
echo json_encode(['success' => false]);
exit;
?>