<?php

session_start();
require_once "connection.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}
$user_id = $_SESSION['user_id'];
$success = false;

if (isset($_POST['address'])) {
    $address = trim($_POST['address']);
    if ($address) {
        $stmt = $conn->prepare("UPDATE users SET UserAddress = ? WHERE UserID = ?");
        $stmt->bind_param("si", $address, $user_id);
        $success = $stmt->execute();
        $stmt->close();
    }
} elseif (isset($_POST['phone'])) {
    $phone = trim($_POST['phone']);
    if ($phone) {
        $stmt = $conn->prepare("UPDATE users SET UserPhone = ? WHERE UserID = ?");
        $stmt->bind_param("si", $phone, $user_id);
        $success = $stmt->execute();
        $stmt->close();
    }
}

echo json_encode(['success' => $success]);