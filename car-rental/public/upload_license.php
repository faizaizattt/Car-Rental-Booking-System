<?php
session_start();
require_once '../config/config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['license'])) {
    $target_dir = "../uploads/licenses/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["license"]["name"]);
    $target_file = $target_dir . $user_id . "_" . $file_name;

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $valid_types)) {
        if (move_uploaded_file($_FILES["license"]["tmp_name"], $target_file)) {
            // ✅ Save image and consider user approved
            $stmt = $pdo->prepare("UPDATE users SET license_image = ? WHERE id = ?");
            $stmt->execute([$target_file, $user_id]);
            header("Location: profile.php?upload=success");
            exit;
        } else {
            echo "❌ Failed to upload file.";
        }
    } else {
        echo "❌ Invalid file type.";
    }
} else {
    echo "❌ No file uploaded.";
}
?>
