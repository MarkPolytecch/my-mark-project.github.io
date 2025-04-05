<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Егер админ кірсе
    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        $_SESSION["user_id"] = "admin";
        $_SESSION["username"] = ADMIN_EMAIL; // Админ логинін сессияға жазамыз
        header("Location: dashboard.php");
        exit;
    }

    // Қарапайым қолданушыны тексеру
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["email"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Қате email немесе пароль!";
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кіру | MarkStore</title>
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
        .error {
            color: #ff4b2b;
            margin-top: 10px;
        }
        .link {
            display: block;
            margin-top: 15px;
            color: #ff416c;
            text-decoration: none;
            font-size: 14px;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Кіру</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Құпиясөз" required>
            <button type="submit">Кіру</button>
        </form>
        <a href="register.php" class="link">Тіркелу</a>
    </div>
</body>
</html>
