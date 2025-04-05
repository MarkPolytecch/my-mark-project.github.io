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
        body {
            font-family: 'Poppins', sans-serif;
            color: white;
            background: linear-gradient(135deg, #1f1c2c, #928DAB);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }

        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
        }

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
            width: 48%;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }

        .cart-item .info {
            flex-grow: 1;
            text-align: left;
            padding-left: 15px;
        }

        .cart-item .price {
            font-weight: bold;
            color: #ff416c;
            font-size: 18px;
        }

        .remove-btn {
            background: #ff4b2b;
            color: white;
            padding: 8px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .remove-btn:hover {
            background: #ff416c;
        }

        .btn {
            display: inline-block;
            background: #ff416c;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
        }

        .btn:hover {
            background: #ff4b2b;
        }

        h3 {
            color: #fff;
        }

        .total-price {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #ff416c;
            margin-top: 20px;
        }

        .whatsapp-btn {
            display: inline-block;
            background-color: #25D366;
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
        }

        .whatsapp-btn:hover {
            background-color: #128C7E;
        }

        .address-text {
            color: white;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }

        .payment-text {
            color: white;
            text-align: center;
            margin-top: 10px;
            font-size: 18px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Корзинка</h2>

        <?php if (empty($cart_items)) { echo "<p style='text-align: center;'>Корзинка бос.</p>"; } else { ?>
            <?php $total_price = 0; ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image">
                        <div class="info">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div class="price"><?php echo htmlspecialchars($item['price']); ?> тг</div>
                        </div>
                        <a href="cart.php?remove_product_id=<?php echo $item['id']; ?>" class="remove-btn">Өшіру</a>
                    </div>
                    <?php $total_price += $item['price']; ?>
                <?php endforeach; ?>
            </div>
            <div class="total-price">
                <h3>Жалпы баға: <?php echo $total_price; ?> тг</h3>
            </div>
            
            <!-- Мекен-жай және төлем мәтіндері -->
            <div class="address-text">
                <p>1. Сатып алу үшін төлем жасаныз.</p>
                <p>2. Төлем үшін Kaspi номерін пайдаланыңыз: <strong>+77054179557 (Миржалол О)</strong></p>
                <p>3. Немесе WhatsApp арқылы хабарласыңыз, біз сізбен 10 минут ішінде байланысамыз.</p>
                <p>чекты осы вассап номерге жіберіңіз "Markstore" деп жазыңыз! <strong><a href="https://wa.me/77754119210?text=Сіздерден%20сатып%20алған%20өнімдер%20үшін%20төлеймін%20және%20мекенжайым%20бар%20дейін%20жіберіңіз!" target="_blank" class="whatsapp-btn">WhatsApp арқылы төлеу</a></strong></p>
            </div>

            </div>
        <?php } ?>
    </div>
</body>
</html>

