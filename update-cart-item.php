<?php
session_start();
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

    $conn = new mysqli('localhost', 'root', '', 'decadhen');
    if ($conn->connect_error) die(json_encode(['success' => false]));

    // Update quantity
    $stmt = $conn->prepare("UPDATE CartItems SET CartQuantity = ? WHERE CartItemID = ? AND CartID = ?");
    $stmt->bind_param('iii', $quantity, $cart_item_id, $cart_id);
    $stmt->execute();
    $stmt->close();

    // Get updated subtotal and cart total
    $result = $conn->query("SELECT CartQuantity, UnitPrice FROM CartItems WHERE CartItemID = $cart_item_id AND CartID = $cart_id");
    $row = $result->fetch_assoc();
    if (!$row) {
        $conn->close();
        echo json_encode(['success' => false, 'error' => 'Item not found']);
        exit;
    }
    $subtotal = $row['CartQuantity'] * $row['UnitPrice'];

    $total_result = $conn->query("SELECT SUM(CartQuantity * UnitPrice) AS cart_total FROM CartItems WHERE CartID = $cart_id");
    $cart_total = $total_result->fetch_assoc()['cart_total'] ?? 0;

    // Update cart updated date
    $conn->query("UPDATE Cart SET UpdatedDate = NOW() WHERE CartID = $cart_id");

    $conn->close();

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