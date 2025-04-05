<?php
session_start();

// Корзинкаға өнім қосу
if (isset($_GET['product_id'])) {
    // Өнім ID-ін алу
    $product_id = $_GET['product_id'];
    
    // Дерекқордан өнім туралы ақпарат алу
    require 'config.php';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Егер өнім сессияда жоқ болса, оны қосу
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Өнімді корзинкаға қосу
        $_SESSION['cart'][] = $product;
    }

    // Қайтадан өнімдер тізіміне оралу
    header("Location: cart.php");
    exit();
}

// Өнімді корзинкадан өшіру
if (isset($_GET['remove_product_id'])) {
    $remove_product_id = $_GET['remove_product_id'];

    // Өнімді сессиядан жою
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $remove_product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // Корзинканы қайтадан индекстермен қайта құру
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: cart.php");
    exit();
}

// Корзинкадағы өнімдер
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзинка | MarkStore</title>
    <style>
        body { font-family: 'Poppins', sans-serif; color: white; background: linear-gradient(135deg, #1f1c2c, #928DAB); }
        .container { max-width: 800px; margin: auto; padding: 30px; border-radius: 15px; background: rgba(255, 255, 255, 0.1); }
        
        /* Өнімдер қатар орналасуы үшін Flexbox қолдану */
        .cart-items {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        
        .cart-item {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 15px;
            width: 48%; /* әрбір өнімнің ені */
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cart-item img { width: 60px; height: 60px; object-fit: cover; }
        .cart-item .info { flex-grow: 1; text-align: left; padding-left: 15px; }
        .cart-item .price { font-weight: bold; color: #ff416c; }
        .btn { display: inline-block; background: #ff416c; color: white; padding: 12px 20px; font-size: 18px; cursor: pointer; border-radius: 5px; text-decoration: none; }
        .btn:hover { background: #ff4b2b; }
        .remove-btn {
            background: #ff4b2b;
            color: white;
            padding: 8px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }
        .remove-btn:hover {
            background: #ff416c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Корзинка</h2>

        <?php if (empty($cart_items)) { echo "<p>Корзинка бос.</p>"; } else { ?>
            <?php $total_price = 0; ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image">
                        <div class="info">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div class="price"><?php echo htmlspecialchars($item['price']); ?> тг</div>
                        </div>
                        <!-- Өнімді өшіру батырмасы -->
                        <a href="cart.php?remove_product_id=<?php echo $item['id']; ?>" class="remove-btn">Өшіру</a>
                    </div>
                    <?php $total_price += $item['price']; ?>
                <?php endforeach; ?>
            </div>
            <h3>Жалпы баға: <?php echo $total_price; ?> тг</h3>
            <a href="purchase.php" class="btn">Сатып алу</a>
        <?php } ?>
    </div>
</body>
</html>
