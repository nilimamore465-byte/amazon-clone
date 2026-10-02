<?php

session_start();

require_once "../db.php";

/* =========================
   CHECK PRODUCT ID
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Product not found");
}

$product_id = (int) $_GET['id'];


/* =========================
   GET PRODUCT
========================= */

$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE id = ?"
);

$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found");
}


/* =========================
   ADD TO CART
========================= */

if (isset($_POST['add_to_cart'])) {

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $product_id;

    header("Location: ../cart.php");
    exit;
}


/* =========================
   GET MULTIPLE IMAGES
========================= */

$imageStmt = $pdo->prepare(
    "SELECT * FROM product_images
     WHERE product_id = ?
     ORDER BY id ASC"
);

$imageStmt->execute([$product_id]);

$images = $imageStmt->fetchAll(PDO::FETCH_ASSOC);

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
        <?= htmlspecialchars($product['name']) ?>
    </title>

    <link
        rel="stylesheet"
        href="details.css"
    >

</head>

<body>


<div class="product-page">


    <!-- BACK BUTTON -->

    <a
        href="../amazon.php"
        class="back-button"
    >

        ← Back to Amazon

    </a>


    <div class="product-details">


        <!-- =========================
             LEFT SIDE
        ========================= -->

        <div class="product-left">


            <!-- MAIN IMAGE -->

            <div class="main-image">

                <?php if (!empty($product['image'])): ?>

                    <img
                        id="mainProductImage"
                        src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>"
                    >

                <?php else: ?>

                    <div class="no-image">
                        No Image
                    </div>

                <?php endif; ?>

            </div>


            <!-- =========================
                 THUMBNAILS
            ========================= -->

            <div class="thumbnail-container">


                <?php if (!empty($product['image'])): ?>

                    <img
                        class="thumbnail active"
                        src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                        onclick="changeImage(this)"
                        alt="Product image"
                    >

                <?php endif; ?>


                <?php foreach ($images as $image): ?>

                    <img
                        class="thumbnail"
                        src="../uploads/<?= htmlspecialchars($image['image']) ?>"
                        onclick="changeImage(this)"
                        alt="Product image"
                    >

                <?php endforeach; ?>


            </div>


        </div>


        <!-- =========================
             RIGHT SIDE
        ========================= -->

        <div class="product-info">


            <!-- PRODUCT NAME -->

            <h1>

                <?= htmlspecialchars($product['name']) ?>

            </h1>


            <!-- CATEGORY -->

            <p class="category">

                <?= htmlspecialchars($product['category']) ?>

            </p>


            <hr>


            <!-- PRICE -->

            <div class="price">

                ₹<?= number_format((float)$product['price'], 2) ?>

            </div>


            <!-- DESCRIPTION -->

            <p class="description">

                <?= nl2br(htmlspecialchars($product['description'])) ?>

            </p>


            <!-- =========================
                 ADD TO CART
            ========================= -->

            <form method="POST">

                <button
                    class="cart-button"
                    type="submit"
                    name="add_to_cart"
                >

                    Add to Cart

                </button>

            </form>


            <!-- BUY NOW -->

            <button
                class="buy-button"
                type="button"
                onclick="buyNow()"
            >

                Buy Now

            </button>


        </div>


    </div>

</div>


<script>

/* =========================
   CHANGE MAIN IMAGE
========================= */

function changeImage(image) {

    document.getElementById(
        "mainProductImage"
    ).src = image.src;


    document.querySelectorAll(
        ".thumbnail"
    ).forEach(function(item) {

        item.classList.remove("active");

    });


    image.classList.add("active");

}


/* =========================
   BUY NOW
========================= */

function buyNow() {

    alert("Buy Now clicked!");

}

</script>


</body>

</html>