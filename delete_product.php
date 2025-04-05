<?php
require 'config.php';
session_start();

// Тек админ ғана өнімді өшіре алады
if (empty($_SESSION["username"]) || $_SESSION["username"] !== ADMIN_EMAIL) {
    die("Сізде бұл әрекетті жасауға рұқсат жоқ!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);

    // Өнім бар ма, соны тексеру
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Суретті серверден өшіру
        $image_path = "uploads/" . $product['image'];
        if (file_exists($image_path)) {
            unlink($image_path); // Файлды өшіру
        }

        // Өнімді дерекқордан өшіру
        $delete_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $delete_stmt->execute([$product_id]);

        header("Location: products.php");
        exit;
    } else {
        echo "Өнім табылмады!";
    }
} else {
    echo "Қате сұраныс!";
}
?>
