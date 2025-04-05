<?php
require 'config.php';
session_start();

// Өнім ID-ін алу
$product_id = $_GET['product_id'];

// Өнімді дерекқордан алу
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<p>Өнім табылмады.</p>";
    exit;
}

// Админ болу тексерісі
$is_admin = !empty($_SESSION["username"]) && $_SESSION["username"] === ADMIN_EMAIL;
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Өнім туралы толық ақпарат | MarkStore</title>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            text-align: center; 
            background: linear-gradient(135deg, #1f1c2c, #928DAB); 
            color: white; 
            padding: 50px; 
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        }
        .product-detail {
            text-align: left;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
        .product-detail h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .product-detail p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .admin-input {
            margin-top: 20px;
        }
        .admin-input textarea {
            width: 100%;
            height: 100px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }
        .admin-input button {
            padding: 10px 20px;
            background-color: #ff416c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .admin-input button:hover {
            background-color: #ff4b2b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Өнім туралы толық ақпарат</h2>

        <div class="product-detail">
            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="width: 200px; height: 200px; object-fit: cover; border-radius: 10px;">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><strong>Сипаттама:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>Бағасы:</strong> <?php echo htmlspecialchars($product['price']); ?> тг</p>

            <?php if (!empty($product['video'])): ?>
                <p><strong>Бейнемазмұн:</strong></p>
                <video width="320" height="240" controls>
                    <source src="uploads/<?php echo htmlspecialchars($product['video']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>

            <?php if ($is_admin): ?>
                <!-- Админ үшін қосымша ақпарат қосу -->
                <div class="admin-input">
                    <h3>Қосымша ақпарат қосу</h3>
                    <form method="POST" action="add_product_info.php">
                        <textarea name="additional_info" placeholder="Қосымша ақпаратты енгізіңіз"></textarea>
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Жүктеу</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
