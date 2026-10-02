<?php

session_start();

require_once "db.php";


/* =========================
   INITIALIZE CART
========================= */

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


/* =========================
   REMOVE PRODUCT FROM CART
========================= */

if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {

    $remove_id = (int) $_GET['remove'];

    $_SESSION['cart'] = array_values(
        array_filter(
            $_SESSION['cart'],
            function ($id) use ($remove_id) {
                return (int)$id !== $remove_id;
            }
        )
    );

    header("Location: cart.php");
    exit;
}


/* =========================
   GET CART PRODUCT IDs
========================= */

$cartIds = array_map(
    'intval',
    $_SESSION['cart']
);


/* =========================
   GET PRODUCTS
========================= */

$cartProducts = [];

if (!empty($cartIds)) {

    $placeholders = implode(
        ',',
        array_fill(0, count($cartIds), '?')
    );

    $stmt = $pdo->prepare(
        "SELECT 
            id,
            name,
            price,
            category,
            description,
            image
         FROM products
         WHERE id IN ($placeholders)
         ORDER BY id DESC"
    );

    $stmt->execute($cartIds);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /* =========================
       ADD QUANTITY
    ========================= */

    foreach ($products as $product) {

        $quantity = 0;

        foreach ($cartIds as $cartId) {

            if ($cartId == $product['id']) {
                $quantity++;
            }

        }

        $product['quantity'] = $quantity;

        $cartProducts[] = $product;
    }
}


/* =========================
   TOTAL
========================= */

$total = 0;

foreach ($cartProducts as $item) {

    $total +=
        (float)$item['price']
        * (int)$item['quantity'];
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Amazon Clone - Cart
    </title>


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }


        body {
            background: #ffc0cb;
        }


        /* =========================
           HEADER
        ========================= */

        .header {

            background: #131921;

            color: white;

            padding: 18px 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;

        }


        .header h2 {
            font-size: 22px;
        }


        .home-btn {

            color: white;

            text-decoration: none;

            background: #37475a;

            padding: 10px 18px;

            border-radius: 5px;

        }


        .home-btn:hover {
            background: #485769;
        }


        /* =========================
           CONTAINER
        ========================= */

        .container {

            width: 90%;

            max-width: 1100px;

            margin: 30px auto;

        }


        .cart-title {

            background: white;

            padding: 20px;

            margin-bottom: 20px;

            border-radius: 5px;

        }


        /* =========================
           CART ITEM
        ========================= */

        .cart-item {

            background: white;

            padding: 20px;

            margin-bottom: 15px;

            border-radius: 5px;

            display: flex;

            gap: 25px;

            align-items: center;

        }


        /* =========================
           IMAGE
        ========================= */

        .cart-image {

            width: 200px;

            height: 200px;

            object-fit: contain;

            background: white;

            border: 1px solid #ddd;

            padding: 10px;

            border-radius: 5px;

        }


        .no-image {

            width: 200px;

            height: 200px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #eee;

            color: #555;

        }


        /* =========================
           INFORMATION
        ========================= */

        .cart-info {

            flex: 1;

        }


        .cart-info h2 {

            margin-bottom: 12px;

            color: #111;

            font-size: 25px;

        }


        .category {

            color: #555;

            margin-bottom: 10px;

        }


        .price {

            font-size: 24px;

            font-weight: bold;

            margin: 10px 0;

            color: #111;

        }


        .quantity {

            margin: 10px 0;

            color: #333;

        }


        .description {

            color: #555;

            line-height: 22px;

            margin-bottom: 15px;

        }


        /* =========================
           BUTTONS
        ========================= */

        .buttons {

            display: flex;

            gap: 10px;

            flex-wrap: wrap;

        }


        .remove-btn {

            display: inline-block;

            background: #d11a2a;

            color: white;

            text-decoration: none;

            padding: 10px 18px;

            border-radius: 5px;

        }


        .remove-btn:hover {
            background: #b31321;
        }


        .buy-btn {

            display: inline-block;

            background: #ffd814;

            color: #111;

            text-decoration: none;

            padding: 10px 22px;

            border-radius: 20px;

            font-weight: bold;

        }


        .buy-btn:hover {
            background: #f7ca00;
        }


        /* =========================
           TOTAL
        ========================= */

        .cart-total {

            background: white;

            padding: 25px;

            border-radius: 5px;

            text-align: right;

        }


        .cart-total h2 {

            margin-bottom: 18px;

        }


        .checkout-btn {

            display: inline-block;

            background: #ff9900;

            color: #111;

            padding: 13px 25px;

            text-decoration: none;

            border-radius: 25px;

            font-weight: bold;

        }


        .checkout-btn:hover {
            background: #e68a00;
        }


        /* =========================
           EMPTY CART
        ========================= */

        .empty-cart {

            background: white;

            padding: 60px;

            text-align: center;

            border-radius: 5px;

        }


        .empty-cart h2 {

            margin-bottom: 20px;

        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {

            .header {

                padding: 15px;

                gap: 10px;

            }


            .header h2 {

                font-size: 17px;

            }


            .home-btn {

                font-size: 13px;

                padding: 8px 10px;

            }


            .cart-item {

                flex-direction: column;

                align-items: flex-start;

            }


            .cart-image,
            .no-image {

                width: 100%;

                height: 250px;

            }


            .cart-info {

                width: 100%;

            }


            .cart-total {

                text-align: center;

            }

        }

    </style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<div class="header">

    <h2>
        🛒 Amazon Clone - Your Cart
    </h2>


    <a
        href="amazon.php"
        class="home-btn"
    >
        ← Continue Shopping
    </a>

</div>


<div class="container">


    <!-- =========================
         CART TITLE
    ========================= -->

    <div class="cart-title">

        <h1>
            Shopping Cart
        </h1>

    </div>


    <?php if (!empty($cartProducts)): ?>


        <!-- =========================
             PRODUCTS
        ========================= -->

        <?php foreach ($cartProducts as $item): ?>


            <div class="cart-item">


                <!-- PRODUCT IMAGE -->

                <?php if (!empty($item['image'])): ?>

                    <img
                        class="cart-image"
                        src="uploads/<?= htmlspecialchars($item['image']) ?>"
                        alt="<?= htmlspecialchars($item['name']) ?>"
                    >

                <?php else: ?>

                    <div class="no-image">

                        No Image

                    </div>

                <?php endif; ?>


                <!-- PRODUCT INFORMATION -->

                <div class="cart-info">


                    <!-- NAME -->

                    <h2>

                        <?= htmlspecialchars($item['name']) ?>

                    </h2>


                    <!-- CATEGORY -->

                    <p class="category">

                        Category:
                        <strong>
                            <?= htmlspecialchars($item['category']) ?>
                        </strong>

                    </p>


                    <!-- PRICE -->

                    <div class="price">

                        ₹<?= number_format(
                            (float)$item['price'],
                            2
                        ) ?>

                    </div>


                    <!-- QUANTITY -->

                    <p class="quantity">

                        Quantity:
                        <?= (int)$item['quantity'] ?>

                    </p>


                    <!-- DESCRIPTION -->

                    <p class="description">

                        <?= htmlspecialchars(
                            $item['description']
                        ) ?>

                    </p>


                    <!-- BUTTONS -->

                    <div class="buttons">


                        <!-- REMOVE -->

                        <a
                            href="cart.php?remove=<?= (int)$item['id'] ?>"
                            class="remove-btn"
                            onclick="return confirm('Remove this product from cart?');"
                        >

                            🗑️ Remove

                        </a>


                        <!-- BUY NOW -->

                        <a
                            href="product/details.php?id=<?= (int)$item['id'] ?>"
                            class="buy-btn"
                        >

                            🛍️ Buy Now

                        </a>


                    </div>


                </div>


            </div>


        <?php endforeach; ?>


        <!-- =========================
             TOTAL
        ========================= -->

        <div class="cart-total">


            <h2>

                Total:
                ₹<?= number_format($total, 2) ?>

            </h2>


            <a
                href="#"
                class="checkout-btn"
                onclick="alert('Proceed to Buy clicked!'); return false;"
            >

                Proceed to Buy

            </a>


        </div>


    <?php else: ?>


        <!-- =========================
             EMPTY CART
        ========================= -->

        <div class="empty-cart">


            <h2>
                Your Cart is Empty
            </h2>


            <p>
                Add products from Amazon Clone.
            </p>


            <br>


            <a
                href="amazon.php"
                class="checkout-btn"
            >

                🛒 Shop Now

            </a>


        </div>


    <?php endif; ?>


</div>


</body>

</html>