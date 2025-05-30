<?php

session_start();
require_once "connection.php";
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}
$user_id = $_SESSION['user_id'];
$address = trim($_POST['address'] ?? '');
if ($address) {
    $stmt = $conn->prepare("UPDATE users SET UserAddress = ? WHERE UserID = ?");
    $stmt->bind_param("si", $address, $user_id);
    $ok = $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => $ok]);
} else {
    echo json_encode(['success' => false]);
}