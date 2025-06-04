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
        $stmt = sqlsrv_query($conn, "UPDATE decadhen.users SET UserAddress = ? WHERE UserID = ?", [$address, $user_id]);
        $success = $stmt ? true : false;
    }
} elseif (isset($_POST['phone'])) {
    $phone = trim($_POST['phone']);
    if ($phone) {
        $stmt = sqlsrv_query($conn, "UPDATE decadhen.users SET UserPhone = ? WHERE UserID = ?", [$phone, $user_id]);
        $success = $stmt ? true : false;
    }
}

echo json_encode(['success' => $success]);