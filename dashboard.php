<?php
session_start();
require 'config.php';

// Егер пайдаланушы жүйеге кірмеген болса, кіру бетіне жібереді
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Пайдаланушы мәліметтерін шығару
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();

// Профиль суретін көрсету
$profile_image = !empty($user["profile_image"]) ? "uploads/" . $user["profile_image"] : "default.png";
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Жеке кабинет | MarkStore</title>
    <style>
        body { 
            font-family: 'Poppins', sans-serif;
            text-align: center;
            background: linear-gradient(135deg, #1f1c2c, #928DAB); 
            color: white; 
            padding: 50px;
        }
        .profile-container {
            max-width: 400px;
            margin: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            background: white;
            margin-bottom: 15px;
        }
        .username {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .btn {
            display: block;
            background: #ff416c;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover {
            background: #ff4b2b;
            transform: translateY(-3px);
        }
        input[type="file"] {
            margin-top: 10px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img src="<?= htmlspecialchars($profile_image) ?>" class="profile-img">
        <h2 class="username"><?= htmlspecialchars($user["username"]) ?></h2>
        
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_image" accept="image/*" required>
            <button type="submit" class="btn">Суретті жүктеу</button>
        </form>

        <a href="products.php" class="btn">Біздің өнімдер</a>

        <a href="store_info.php" class="btn">Дүкен туралы</a>

        <?php if ($user["email"] === ADMIN_EMAIL): ?>
            <a href="add_product.php" class="btn">Өнім қосу</a>
        <?php endif; ?>

        <a href="logout.php" class="btn">Шығу</a>
    </div>
</body>
</html>
