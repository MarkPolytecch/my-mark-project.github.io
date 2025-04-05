<?php
require 'config.php';
session_start();

// Өнімдерді дерекқордан алу
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Біздің өнімдер | MarkStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        .product {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer; /* Басқанда өту үшін */
        }
        img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid white;
        }
        .info {
            flex-grow: 1;
            text-align: left;
            padding-left: 15px;
        }
        .info h3 {
            margin: 0;
            font-size: 18px;
        }
        .info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .price {
            font-size: 18px;
            font-weight: bold;
            color: #ff416c;
        }
        .btn {
            display: inline-block;
            background: #ff416c;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: #ff4b2b;
            transform: translateY(-3px);
        }
        .cart-icon {
            font-size: 24px;
            color: white;
            background-color: #ff416c;
            padding: 10px;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        .cart-icon:hover {
            background-color: #ff4b2b;
        }
        .purchase-btn {
            display: inline-block;
            background: #ff416c; /* бастапқы түс */
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .purchase-btn:hover {
            background: #ff4b2b; /* батырманың hover кезінде түсі өзгереді */
            transform: translateY(-3px);
        }
        .cart-bottom {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ff416c;
            padding: 15px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .cart-bottom:hover {
            background-color: #ff4b2b;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Біздің өнімдер</h2>

        <?php if (!empty($_SESSION["username"]) && $_SESSION["username"] === ADMIN_EMAIL): ?>
            <a href="add_product.php" class="btn">Өнім қосу</a>
        <?php endif; ?>

        <?php if (empty($products)) echo "<p>Әзірге ешқандай өнім жоқ.</p>"; ?>
        
        <?php foreach ($products as $product): ?>
            <div class="product" onclick="window.location='product_detail.php?product_id=<?php echo $product['id']; ?>'">
                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                <div class="info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="price"><?php echo htmlspecialchars($product['price']); ?> тг</div>
                </div>

                <!-- Корзинкаға қосу сілтемесі -->
                <a href="cart.php?product_id=<?php echo $product['id']; ?>" class="cart-icon"><i class="fas fa-shopping-cart"></i></a>

                <!-- Сатып алу батырмасы -->
                <a href="purchase.php?product_id=<?php echo $product['id']; ?>" class="purchase-btn">Сатып алу</a>

                <?php if (!empty($_SESSION["username"]) && $_SESSION["username"] === ADMIN_EMAIL): ?>
                    <form method="POST" action="delete_product.php" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="delete-btn" onclick="return confirm('Бұл өнімді өшіргіңіз келе ме?')">Өшіру</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Дәл төменгі оң жақта орналасатын корзинка эмблемасы -->
    <div class="cart-bottom">
        <a href="cart.php" class="cart-icon"><i class="fas fa-shopping-cart"></i></a>
    </div>
</body>
</html> 
