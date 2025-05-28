<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['unit_price'])) {
    $user_id = intval($_SESSION['user_id']);
    $cart_id = $user_id;
    $product_id = intval($_POST['product_id']);
    $unit_price = floatval($_POST['unit_price']);
    $size = $_POST['size'] ?? '';

    $conn = new mysqli('localhost', 'root', '', 'decadhen');
    if ($conn->connect_error) die(json_encode(['success' => false]));

    // Check if item already in cart (optionally by size)
    $stmt = $conn->prepare("SELECT CartItemID, CartQuantity FROM CartItems WHERE CartID = ? AND ProductID = ? AND Size = ?");
    $stmt->bind_param('iis', $cart_id, $product_id, $size);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Update quantity
        $new_qty = $row['CartQuantity'] + 1;
        $update = $conn->prepare("UPDATE CartItems SET CartQuantity = ? WHERE CartItemID = ?");
        $update->bind_param('ii', $new_qty, $row['CartItemID']);
        $update->execute();
        $update->close();
    } else {
        // Insert new item
        $insert = $conn->prepare("INSERT INTO CartItems (CartID, ProductID, CartQuantity, UnitPrice, Size) VALUES (?, ?, 1, ?, ?)");
        $insert->bind_param('iids', $cart_id, $product_id, $unit_price, $size);
        $insert->execute();
        $insert->close();
    }
    $stmt->close();

    // Get updated cart
    $cart_items = [];
    $cart_total = 0;
    $cart_items_query = $conn->query("
        SELECT ci.CartItemID, ci.ProductID, ci.CartQuantity, ci.UnitPrice, p.ProductName
        FROM CartItems ci
        JOIN products p ON ci.ProductID = p.ProductID
        WHERE ci.CartID = $cart_id
    ");
    while ($item = $cart_items_query->fetch_assoc()) {
        $item['Subtotal'] = $item['UnitPrice'] * $item['CartQuantity'];
        $cart_total += $item['Subtotal'];
        $cart_items[] = $item;
    }
    $conn->query("UPDATE Cart SET UpdatedDate = NOW() WHERE CartID = $cart_id");
    $conn->close();

    echo json_encode([
        'success' => true,
        'cart_items' => $cart_items,
        'cart_total' => $cart_total
    ]);
    exit;
}
echo json_encode(['success' => false]);
exit;
?>