<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: products.php");
    exit;
}

$id = (int) $_GET["id"];


/* =========================
   GET PRODUCT
========================= */

$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE id = ?"
);

$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found");
}


/* =========================
   GET ADDITIONAL IMAGES
========================= */

$imageStmt = $pdo->prepare(
    "SELECT * FROM product_images
     WHERE product_id = ?
     ORDER BY id ASC"
);

$imageStmt->execute([$id]);

$additionalImages = $imageStmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================
   UPDATE PRODUCT
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $price = trim($_POST["price"]);
    $category = trim($_POST["category"]);
    $description = trim($_POST["description"]);


    /* =========================
       MAIN IMAGE
    ========================= */

    if (
        isset($_FILES["image"]) &&
        !empty($_FILES["image"]["name"])
    ) {

        $imageName =
            time() . "_" .
            basename($_FILES["image"]["name"]);

        $uploadDir = "../uploads/";

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $uploadDir . $imageName
        );


        $stmt = $pdo->prepare(
            "UPDATE products
             SET name=?,
                 price=?,
                 category=?,
                 description=?,
                 image=?
             WHERE id=?"
        );

        $stmt->execute([
            $name,
            $price,
            $category,
            $description,
            $imageName,
            $id
        ]);

    } else {

        $stmt = $pdo->prepare(
            "UPDATE products
             SET name=?,
                 price=?,
                 category=?,
                 description=?
             WHERE id=?"
        );

        $stmt->execute([
            $name,
            $price,
            $category,
            $description,
            $id
        ]);
    }


    /* =========================
       ADD MULTIPLE IMAGES
    ========================= */

    if (
        isset($_FILES["additional_images"]) &&
        !empty($_FILES["additional_images"]["name"][0])
    ) {

        $uploadDir = "../uploads/";

        foreach (
            $_FILES["additional_images"]["name"]
            as $key => $originalName
        ) {

            if (
                $_FILES["additional_images"]["error"][$key]
                !== UPLOAD_ERR_OK
            ) {
                continue;
            }


            $imageName =
                time() . "_" .
                $key . "_" .
                basename($originalName);


            $tmpName =
                $_FILES["additional_images"]["tmp_name"][$key];


            if (
                move_uploaded_file(
                    $tmpName,
                    $uploadDir . $imageName
                )
            ) {

                $imageStmt = $pdo->prepare(
                    "INSERT INTO product_images
                     (product_id, image)
                     VALUES (?, ?)"
                );

                $imageStmt->execute([
                    $id,
                    $imageName
                ]);
            }
        }
    }


    header(
        "Location: edit_product.php?id=" . $id
    );

    exit;
}


/* =========================
   GET UPDATED IMAGES
========================= */

$imageStmt = $pdo->prepare(
    "SELECT * FROM product_images
     WHERE product_id = ?
     ORDER BY id ASC"
);

$imageStmt->execute([$id]);

$additionalImages =
    $imageStmt->fetchAll(PDO::FETCH_ASSOC);

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
        Edit Product
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            font-family: Arial, sans-serif;

            background: #eaeded;

            margin: 0;

            padding: 30px;

        }


        .container {

            max-width: 700px;

            margin: auto;

            background: white;

            padding: 30px;

            border-radius: 8px;

            box-shadow:
                0 2px 8px
                rgba(0,0,0,0.15);

        }


        h1 {

            margin-bottom: 25px;

        }


        label {

            display: block;

            margin-top: 15px;

            margin-bottom: 5px;

            font-weight: bold;

        }


        input,
        textarea {

            width: 100%;

            padding: 10px;

            border: 1px solid #aaa;

            border-radius: 4px;

        }


        textarea {

            height: 100px;

            resize: vertical;

        }


        button {

            margin-top: 20px;

            padding: 12px 20px;

            background: #ffd814;

            border: none;

            border-radius: 5px;

            cursor: pointer;

            font-weight: bold;

        }


        button:hover {

            background: #f7ca00;

        }


        .back {

            display: inline-block;

            margin-top: 15px;

            text-decoration: none;

            color: #007185;

        }


        /* =========================
           MAIN IMAGE
        ========================= */

        .current-image {

            width: 150px;

            height: 150px;

            object-fit: contain;

            margin-top: 10px;

            border: 1px solid #ddd;

            padding: 5px;

        }


        /* =========================
           ADDITIONAL IMAGES
        ========================= */

        .additional-images {

            display: flex;

            flex-wrap: wrap;

            gap: 12px;

            margin-top: 15px;

        }


        .additional-image-box {

            width: 110px;

            text-align: center;

        }


        .additional-image-box img {

            width: 100px;

            height: 100px;

            object-fit: contain;

            border: 1px solid #ddd;

            padding: 5px;

            background: white;

        }


        .additional-title {

            margin-top: 30px;

            padding-top: 20px;

            border-top: 1px solid #ddd;

        }


        .upload-info {

            color: #666;

            font-size: 14px;

            margin-top: 5px;

        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 600px) {

            body {

                padding: 10px;

            }


            .container {

                padding: 20px;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <h1>
        Edit Product
    </h1>


    <form
        method="POST"
        enctype="multipart/form-data"
    >


        <!-- =========================
             PRODUCT NAME
        ========================= -->

        <label>
            Product Name
        </label>


        <input
            type="text"
            name="name"
            value="<?= htmlspecialchars($product["name"]) ?>"
            required
        >


        <!-- =========================
             PRICE
        ========================= -->

        <label>
            Price
        </label>


        <input
            type="number"
            name="price"
            value="<?= htmlspecialchars($product["price"]) ?>"
            required
        >


        <!-- =========================
             CATEGORY
        ========================= -->

        <label>
            Category
        </label>


        <input
            type="text"
            name="category"
            value="<?= htmlspecialchars($product["category"]) ?>"
            required
        >


        <!-- =========================
             DESCRIPTION
        ========================= -->

        <label>
            Description
        </label>


        <textarea
            name="description"
            required
        ><?= htmlspecialchars($product["description"]) ?></textarea>


        <!-- =========================
             CURRENT MAIN IMAGE
        ========================= -->

        <label>
            Current Main Image
        </label>


        <?php if (!empty($product["image"])): ?>

            <img
                class="current-image"
                src="../uploads/<?= htmlspecialchars($product["image"]) ?>"
                alt="Product Image"
            >

        <?php else: ?>

            <p>
                No main image
            </p>

        <?php endif; ?>


        <!-- =========================
             CHANGE MAIN IMAGE
        ========================= -->

        <label>
            Change Main Image
        </label>


        <input
            type="file"
            name="image"
            accept="image/*"
        >


        <!-- =========================
             ADDITIONAL IMAGES
        ========================= -->

        <h3 class="additional-title">
            Additional Product Images
        </h3>


        <p class="upload-info">
            You can select multiple images at once.
        </p>


        <input
            type="file"
            name="additional_images[]"
            accept="image/*"
            multiple
        >


        <!-- =========================
             EXISTING ADDITIONAL IMAGES
        ========================= -->

        <?php if (!empty($additionalImages)): ?>

            <h3>
                Existing Additional Images
            </h3>


            <div class="additional-images">


                <?php foreach ($additionalImages as $image): ?>


                    <div
                        class="additional-image-box"
                    >

                        <img
                            src="../uploads/<?= htmlspecialchars($image["image"]) ?>"
                            alt="Product Image"
                        >

                    </div>


                <?php endforeach; ?>


            </div>

        <?php endif; ?>


        <!-- =========================
             UPDATE
        ========================= -->

        <button type="submit">

            Update Product

        </button>


    </form>


    <a
        class="back"
        href="products.php"
    >

        ← Back to Products

    </a>


</div>


</body>

</html>