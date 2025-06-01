<?php
session_start();
require_once "connection.php";

// Update Announcement
if (isset($_POST['page_title'])) {
    header('Content-Type: application/json');
    $title = trim($_POST['page_title']);
    $stmt = $conn->prepare("UPDATE indexcontents SET ContentDescription=? WHERE ContentName='Announcement'");
    $stmt->bind_param("s", $title);
    $ok = $stmt->execute();
    $stmt->close();
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
                $stmt = $conn->prepare("SELECT ContentID FROM indexcontents WHERE ContentName=?");
                $contentName = "Carousel$i";
                $stmt->bind_param("s", $contentName);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $stmt->close();
                    $stmt = $conn->prepare("UPDATE indexcontents SET ContentDescription=? WHERE ContentName=?");
                    $stmt->bind_param("ss", $target, $contentName);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    $stmt->close();
                    $stmt = $conn->prepare("INSERT INTO indexcontents (ContentName, ContentDescription) VALUES (?, ?)");
                    $stmt->bind_param("ss", $contentName, $target);
                    $stmt->execute();
                    $stmt->close();
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
        $stmt = $conn->prepare("SELECT ContentID FROM indexcontents WHERE ContentName='FeaturedImage'");
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            $stmt = $conn->prepare("UPDATE indexcontents SET ContentDescription=? WHERE ContentName='FeaturedImage'");
            $stmt->bind_param("s", $target);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO indexcontents (ContentName, ContentDescription) VALUES ('FeaturedImage', ?)");
            $stmt->bind_param("s", $target);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: contenteditor.php");
    exit();
}

// If nothing matched, redirect back
header("Location: contenteditor.php");
exit();