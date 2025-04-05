<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_image"])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
    $allowed_types = ["jpg", "jpeg", "png", "gif"];

    if (in_array($imageFileType, $allowed_types)) {
        $file_name = "user_" . $_SESSION["user_id"] . "." . $imageFileType;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $stmt->execute([$file_name, $_SESSION["user_id"]]);
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Файлды жүктеу кезінде қате кетті.";
        }
    } else {
        echo "Тек JPG, JPEG, PNG, GIF файлдарын жүктеуге болады.";
    }
}
?>
