<?php
session_start();
header('Content-Type: application/json');
require_once "connection.php";

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

    // Check for an active cart
    $cart_res = sqlsrv_query($conn, "SELECT CartID FROM decadhen.Cart WHERE UserID = ? AND Status = 'active'", [$user_id]);
    if ($cart_row = sqlsrv_fetch_array($cart_res, SQLSRV_FETCH_ASSOC)) {
        $cart_id = $cart_row['CartID'];
    } else {
        $insert_cart = sqlsrv_query($conn, "INSERT INTO decadhen.Cart (UserID, Status, CreatedDate) OUTPUT INSERTED.CartID VALUES (?, 'active', GETDATE())", [$user_id]);
        $cart_row = sqlsrv_fetch_array($insert_cart, SQLSRV_FETCH_ASSOC);
        $cart_id = $cart_row['CartID'];
    }

    // Check if item already in cart (optionally by size)
    $stmt = sqlsrv_query($conn, "SELECT CartItemID, CartQuantity FROM decadhen.CartItems WHERE CartID = ? AND ProductID = ? AND Size = ?", [$cart_id, $product_id, $size]);
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Update quantity
        $new_qty = $row['CartQuantity'] + 1;
        sqlsrv_query($conn, "UPDATE decadhen.CartItems SET CartQuantity = ? WHERE CartItemID = ?", [$new_qty, $row['CartItemID']]);
    } else {
        // Insert new item
        sqlsrv_query($conn, "INSERT INTO decadhen.CartItems (CartID, ProductID, CartQuantity, UnitPrice, Size) VALUES (?, ?, 1, ?, ?)", [$cart_id, $product_id, $unit_price, $size]);
    }

    // Get updated cart
    $cart_items = [];
    $cart_total = 0;
    $cart_items_query = sqlsrv_query($conn, "
        SELECT ci.CartItemID, ci.ProductID, ci.CartQuantity, ci.UnitPrice, p.ProductName
        FROM decadhen.CartItems ci
        JOIN decadhen.products p ON ci.ProductID = p.ProductID
        WHERE ci.CartID = ?", [$cart_id]);
    while ($item = sqlsrv_fetch_array($cart_items_query, SQLSRV_FETCH_ASSOC)) {
        $item['Subtotal'] = $item['UnitPrice'] * $item['CartQuantity'];
        $cart_total += $item['Subtotal'];
        $cart_items[] = $item;
    }
    sqlsrv_query($conn, "UPDATE decadhen.Cart SET UpdatedDate = GETDATE() WHERE CartID = ?", [$cart_id]);

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