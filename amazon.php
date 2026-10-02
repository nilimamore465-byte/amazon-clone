<?php

session_start();

require_once "db.php";

/* =========================
   GET CART COUNT
========================= */

$cartCount = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = count($_SESSION['cart']);
}


/* =========================
   GET PRODUCTS
========================= */

$stmt = $pdo->query(
    "SELECT * FROM products ORDER BY id DESC"
);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Amazon Clone</title>


    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    >


    <link
        rel="stylesheet"
        href="style.css"
    >


    <style>

        /* =========================
           FULL HOMEPAGE PINK
        ========================= */

        body {
            background-color: #ffc0cb;
        }


        /* =========================
           OLD 8 CARDS
        ========================= */

        .shop-section {
            background-color: #ffc0cb;
        }

        .box {
            background-color: white;
        }

        .box-content {
            background-color: white;
        }


        /* =========================
           CART
        ========================= */

        .nav-cart {

            position: relative;

            color: white;

            text-decoration: none;

            display: flex;

            align-items: center;

            gap: 5px;

        }


        .cart-count {

            position: absolute;

            top: -8px;

            left: 18px;

            background-color: #ff9900;

            color: #111;

            font-size: 13px;

            font-weight: bold;

            min-width: 18px;

            height: 18px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

        }


        /* =========================
           DATABASE PRODUCTS
        ========================= */

        .database-products {

            padding: 30px 5%;

            background-color: #ffc0cb;

        }


        .database-products > h2 {

            margin-bottom: 20px;

        }


        .database-product-grid {

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 20px;

        }


        /* =========================
           PRODUCT CARD
        ========================= */

        .database-product-card {

            background-color: white;

            padding: 20px;

            min-height: 420px;

            display: flex;

            flex-direction: column;

        }


        /* =========================
           PRODUCT NAME
        ========================= */

        .database-product-card h2 {

            font-size: 20px;

            margin-bottom: 15px;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        /* =========================
           PRODUCT IMAGE
        ========================= */

        .database-product-image {

            width: 100%;

            height: 300px;

            display: flex;

            align-items: center;

            justify-content: center;

            margin-bottom: 15px;

            background-color: white;

        }


        .database-product-image img {

            width: 100%;

            height: 100%;

            object-fit: contain;

        }


        /* =========================
           SHOP NOW
        ========================= */

        .database-product-card p {

            margin-top: auto;

        }


        .database-product-card p a {

            color: #007185;

            text-decoration: none;

            font-size: 14px;

        }


        .database-product-card p a:hover {

            color: #c7511f;

            text-decoration: underline;

        }


        /* =========================
           NO IMAGE
        ========================= */

        .no-image {

            width: 100%;

            height: 300px;

            display: flex;

            align-items: center;

            justify-content: center;

            background-color: #eee;

            color: #555;

        }


        .no-products {

            font-size: 18px;

            padding: 20px;

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1000px) {

            .database-product-grid {

                grid-template-columns: repeat(2, 1fr);

            }

        }


        @media (max-width: 500px) {

            .database-product-grid {

                grid-template-columns: 1fr;

            }

        }

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<header>

    <div class="navbar">


        <div class="nav-logo border">

            <div class="logo"></div>

        </div>


        <div class="nav-address border">

            <p class="add-first">

                Deliver to

            </p>


            <div class="add-icon">

                <i class="fa-solid fa-location-dot"></i>


                <p class="add-second">

                    India

                </p>

            </div>

        </div>


        <div class="nav-search">


            <select class="search-select">

                <option>
                    All
                </option>

            </select>


            <input
                type="text"
                class="search-input"
                placeholder="Search Amazon"
            >


            <div class="search-icon">

                <i class="fa-solid fa-magnifying-glass"></i>

            </div>

        </div>


        <div class="nav-en border">

            <i class="fa-solid fa-globe"></i>

            English

        </div>


        <div class="nav-signin border">

            <p>

                <a href="login.php">

                    hello sign in

                </a>

            </p>


            <p class="nav-second">

                Account &amp; Lists

            </p>

        </div>


        <a
    href="orders.php"
    class="nav-return border"
    style="color:white; text-decoration:none;"
>

    <p>
        <span>Returns</span>
    </p>

    <p class="nav-second">
        &amp; Orders
    </p>

</a>


        <!-- =========================
             CART ICON
        ========================= -->

        <a
            href="cart.php"
            class="nav-cart border"
        >

            <i class="fa-solid fa-cart-shopping"></i>


            <span class="cart-count">

                <?= $cartCount ?>

            </span>


            <p>

                Cart

            </p>

        </a>


    </div>


    <!-- =========================
         PANEL
    ========================= -->

    <div class="panel">


        <div class="panel-all">

            <i class="fa-solid fa-bars"></i>

            All

        </div>


        <div class="panel-ops">

            <p>
                Today's Deals
            </p>

            <p>
                Customer Service
            </p>

            <p>
                Registry
            </p>

            <p>
                Gift Cards
            </p>

            <p>
                MX Player
            </p>

            <p>
                Prime
            </p>

            <p>
                Home & Kitchen
            </p>

            <p>
                Sell
            </p>

        </div>


        <div class="panel-deals">

            Shop deals in Electronics

        </div>

    </div>

</header>


<!-- =========================
     HERO
========================= -->

<div class="hero-section">


    <div class="hero-msg">

        <p>

            You are on Amazon.com.

            You can also shop on Amazon India
            for millions of products with fast local delivery.


            <a href="#">

                Amazon India

            </a>

        </p>

    </div>

</div>


<!-- =========================
     OLD 8 SHOP CARDS
========================= -->

<div class="shop-section">


    <div class="box">

        <div class="box-content">

            <h2>
                Clothes
            </h2>


            <div
                class="box-img"
                style="background-image:url('images/box1_image.jpg');">
            </div>


            <p>

                <a href="clothes.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


    <div class="box">

        <div class="box-content">

            <h2>
                Health & Personal
            </h2>


            <div
                class="box-img"
                style="background-image:url('box2_image.jpg');">
            </div>


            <p>

                <a href="health.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


    <div class="box">

        <div class="box-content">

            <h2>
                Furniture
            </h2>


            <div
                class="box-img"
                style="background-image:url('box3_image.jpg');">
            </div>


            <p>

                <a href="furniture.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


    <div class="box">

        <div class="box-content">

            <h2>
                Electronics
            </h2>


            <div
                class="box-img"
                style="background-image:url('box4_image.jpg');">
            </div>


            <p>

                <a href="electronics.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


    <div class="box">

        <div class="box-content">

            <h2>
                Beauty Picks
            </h2>


            <div
                class="box-img"
                style="background-image:url('box5_image.jpg');">
            </div>


            <p>

                <a href="beauty.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


    <div class="box">

        <div class="box-content">

            <h2>
                Pet Care
            </h2>


            <div
                class="box-img"
                style="background-image:url('box6_image.jpg');">
            </div>


            <p>

                <a href="petcare.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


    <div class="box">

        <div class="box-content">

            <h2>
                New Arrival in Toys
            </h2>


            <div
                class="box-img"
                style="background-image:url('box7_image.jpg');">
            </div>


            <p>

                <a href="newarrival.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


    <div class="box">

        <div class="box-content">

            <h2>
                Discover Fashion
            </h2>


            <div
                class="box-img"
                style="background-image:url('box8_image (1).jpg');">
            </div>


            <p>

                <a href="discover.html">

                    Shop Now

                </a>

            </p>

        </div>

    </div>


</div>


<!-- =========================
     DATABASE PRODUCTS
========================= -->

<div class="database-products">


    <h2>
        
    </h2>


    <div class="database-product-grid">


        <?php if (!empty($products)): ?>


            <?php foreach ($products as $product): ?>


                <div class="database-product-card">


                    <!-- PRODUCT NAME -->

                    <h2
                        title="<?= htmlspecialchars($product['name']) ?>"
                    >

                        <?= htmlspecialchars($product['name']) ?>

                    </h2>


                    <!-- PRODUCT IMAGE -->

                    <div class="database-product-image">


                        <?php if (!empty($product['image'])): ?>


                            <img
                                src="uploads/<?= htmlspecialchars($product['image']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                            >


                        <?php else: ?>


                            <div class="no-image">

                                No Image

                            </div>


                        <?php endif; ?>


                    </div>


                    <!-- SHOP NOW -->

                    <p>

                        <a
                            href="product/details.php?id=<?= (int)$product['id'] ?>"
                        >

                            Shop Now

                        </a>

                    </p>


                </div>


            <?php endforeach; ?>


        <?php else: ?>


            <p class="no-products">

                No products available.

            </p>


        <?php endif; ?>


    </div>

</div>


<!-- =========================
     FOOTER
========================= -->

<footer>


    <div class="foot-panel1">

        Back to Top

    </div>


    <div class="foot-panel2">


        <ul>

            <p>
                Get to Know Us
            </p>

            <a>
                Careers
            </a>

            <a>
                Blog
            </a>

            <a>
                About Amazon
            </a>

            <a>
                Investor Relations
            </a>

            <a>
                Amazon Devices
            </a>

            <a>
                Amazon Science
            </a>

        </ul>


        <ul>

            <p>
                Connect with Us
            </p>

            <a>
                Facebook
            </a>

            <a>
                Instagram
            </a>

        </ul>


        <ul>

            <p>
                Make Money with Us
            </p>

            <a>
                Sell on Amazon
            </a>

            <a>
                Sell under Amazon Accelerator
            </a>

            <a>
                Supply to Amazon
            </a>

            <a>
                Become an Affiliate
            </a>

            <a>
                Protect and Build Your Brand
            </a>

            <a>
                Advertise Your Products
            </a>

            <a>
                Fulfilment by Amazon
            </a>

            <a>
                Amazon Pay on Merchants
            </a>

            <a>
                Amazon Global Selling
            </a>

        </ul>


        <ul>

            <p>
                Let Us Help You
            </p>

            <a>
                Your Account
            </a>

            <a>
                Returns Centre
            </a>

            <a>
                Amazon App Download
            </a>

            <a>
                Recall and Product Safety Alerts
            </a>

            <a>
                100% Purchase Protection
            </a>

            <a>
                Help
            </a>

        </ul>

    </div>


    <div class="foot-panel3">

        <div class="logo"></div>

    </div>


    <div class="foot-panel4">


        <div class="pages">

            <a>
                Conditions of Use
            </a>

            <a>
                Privacy Notice
            </a>

            <a>
                Your Ads Privacy Choices
            </a>

        </div>


        <div class="copyright">

            @ 1996-2023, Amazon.com, Inc. or its affiliates

        </div>

    </div>


</footer>


</body>

</html>