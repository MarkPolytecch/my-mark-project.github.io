<?php
require 'config.php';
session_start();

// Егер админ болмаса, рұқсат бермеу


// Өнім қосу
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = floatval($_POST["price"]);
    $image = $_FILES["image"]["name"];
    
    // Файлды жүктеу
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Дерекқорға енгізу
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $description, $price, $image])) {
            echo "Өнім сәтті қосылды!";
        } else {
            echo "Қате орын алды!";
        }
    } else {
        echo "Суретті жүктеуде қате кетті!";
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Өнім қосу</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f4f4f4; padding: 50px; }
        .container { max-width: 400px; margin: auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        input, button, textarea { width: 100%; padding: 10px; margin-top: 10px; border: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Жаңа өнім қосу</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Өнім атауы" required>
            <textarea name="description" placeholder="Өнім сипаттамасы" required></textarea>
            <input type="number" name="price" placeholder="Бағасы" required>
            <input type="file" name="image" required>
            <button type="submit">Қосу</button>
        </form>
    </div>
</body>
</html>
