<?php
session_start();
require_once "connection.php";
header('Content-Type: application/json');

// Optionally, check if user is admin here

if (isset($_POST['page_title'])) {
    $title = trim($_POST['page_title']);
    $stmt = $conn->prepare("UPDATE indexcontents SET ContentDescription=? WHERE ContentName='Announcement'");
    $stmt->bind_param("s", $title);
    $ok = $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => $ok]);
} else {
    echo json_encode(['success' => false]);
}