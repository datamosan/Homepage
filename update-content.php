<?php
session_start();
require_once "connection.php";

// Update Announcement
if (isset($_POST['page_title'])) {
    header('Content-Type: application/json');
    $title = trim($_POST['page_title']);
    $ok = false;
    $stmt = sqlsrv_query($conn, "UPDATE decadhen.indexcontents SET ContentDescription=? WHERE ContentName='Announcement'", [$title]);
    if ($stmt) $ok = true;
    echo json_encode(['success' => $ok]);
    exit();
}

// Update Carousel Images
if (isset($_POST['save_carousel'])) {
    for ($i = 1; $i <= 3; $i++) {
        $input = "carousel_$i";
        if (isset($_FILES[$input]) && $_FILES[$input]['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES[$input]['tmp_name'];
            $name = basename($_FILES[$input]['name']);
            $target = "images/carousel_" . time() . "_$i" . strrchr($name, '.');
            if (move_uploaded_file($tmp, $target)) {
                $contentName = "Carousel$i";
                // Check if exists
                $check = sqlsrv_query($conn, "SELECT ContentID FROM decadhen.indexcontents WHERE ContentName=?", [$contentName]);
                if ($check && sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC)) {
                    sqlsrv_query($conn, "UPDATE decadhen.indexcontents SET ContentDescription=? WHERE ContentName=?", [$target, $contentName]);
                } else {
                    sqlsrv_query($conn, "INSERT INTO decadhen.indexcontents (ContentName, ContentDescription) VALUES (?, ?)", [$contentName, $target]);
                }
            }
        }
    }
    header("Location: contenteditor.php");
    exit();
}

// Update Featured Image
if (isset($_POST['save_featured_image']) && isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['featured_image']['tmp_name'];
    $name = basename($_FILES['featured_image']['name']);
    $target = "images/featured_" . time() . strrchr($name, '.');
    if (move_uploaded_file($tmp, $target)) {
        $check = sqlsrv_query($conn, "SELECT ContentID FROM decadhen.indexcontents WHERE ContentName='FeaturedImage'");
        if ($check && sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC)) {
            sqlsrv_query($conn, "UPDATE decadhen.indexcontents SET ContentDescription=? WHERE ContentName='FeaturedImage'", [$target]);
        } else {
            sqlsrv_query($conn, "INSERT INTO decadhen.indexcontents (ContentName, ContentDescription) VALUES (?, ?)", ['FeaturedImage', $target]);
        }
    }
    header("Location: contenteditor.php");
    exit();
}

// If nothing matched, redirect back
header("Location: contenteditor.php");
exit();