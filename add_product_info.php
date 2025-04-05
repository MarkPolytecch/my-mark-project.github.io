<?php
require 'config.php';
session_start();

// Админ тексеруі
if (empty($_SESSION["username"]) || $_SESSION["username"] !== ADMIN_EMAIL) {
    echo "<p>Сіз админ емессіз!</p>";
    exit;
}

// POST мәліметтерін тексеру
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $additional_info = trim($_POST['additional_info']); // Ақпаратты тазарту

    // Қосымша ақпараттың бос емес екендігін тексеру
    if (empty($additional_info)) {
        echo "<p>Қосымша ақпаратты енгізу қажет.</p>";
        exit;
    }

    // Қосымша ақпаратты дерекқорға сақтау
    $stmt = $pdo->prepare("INSERT INTO product_info (product_id, additional_info) VALUES (?, ?)");
    $stmt->execute([$product_id, $additional_info]);

    // Қайта бағыттау
    header("Location: product_detail.php?product_id=" . $product_id);
    exit;
} else {
    echo "<p>Қате: Форма жіберілмеген.</p>";
}
?>

