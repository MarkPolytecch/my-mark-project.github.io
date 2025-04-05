<?php
$host = 'localhost';
$dbname = 'markstore';
$username = 'root';
$password = 'root'; // MAMP қолдансаң, пароль осындай

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Қосылу қатесі: " . $e->getMessage());
}

// Админ логині мен паролі
define("ADMIN_EMAIL", "admin@mail.com");
define("ADMIN_PASSWORD", "5544332211");
?>
