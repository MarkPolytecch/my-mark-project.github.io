<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]); // Removed hashing

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "<p style='color:red;'>Бұл email бұрын тіркелген!</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            echo "<p style='color:green;'>Тіркелу сәтті аяқталды! <a href='login.php'>Кіру</a></p>";
        } else {
            echo "<p style='color:red;'>Қате орын алды, қайта тіркеліңіз.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тіркелу</title>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            text-align: center; 
            background: linear-gradient(135deg, #1f1c2c, #928DAB); 
            color: white; 
            padding: 50px; 
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #ff416c;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #ff4b2b;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Тіркелу</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Құпиясөз" required>
            <button type="submit">Тіркелу</button>
        </form>
    </div>
</body>
</html>