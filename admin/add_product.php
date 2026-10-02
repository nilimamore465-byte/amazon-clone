<?php

session_start();
require_once "../db.php";

/* =========================
   ADMIN LOGIN CHECK
========================= */

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";


/* =========================
   ADD PRODUCT
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $description = trim($_POST["description"] ?? "");

    $imageName = "";


    /* Check required fields */

    if ($name === "" || $price === "" || $category === "") {

        $error = "Please fill all required fields.";

    } else {


        /* =========================
           IMAGE UPLOAD
        ========================= */

        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] === UPLOAD_ERR_OK
        ) {

            $originalName = basename($_FILES["image"]["name"]);

            $extension = strtolower(
                pathinfo($originalName, PATHINFO_EXTENSION)
            );

            $allowedExtensions = [
                "jpg",
                "jpeg",
                "png",
                "webp",
                "gif"
            ];


            if (!in_array($extension, $allowedExtensions)) {

                $error = "Only JPG, JPEG, PNG, WEBP and GIF images are allowed.";

            } else {

                /*
                 * Create unique image name
                 */

                $imageName =
                    time() . "_" .
                    uniqid() . "." .
                    $extension;


                /*
                 * Upload folder
                 */

                $uploadDirectory = "../uploads/";


                /*
                 * Create folder if it doesn't exist
                 */

                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }


                $uploadPath =
                    $uploadDirectory . $imageName;


                /*
                 * Move uploaded image
                 */

                if (
                    move_uploaded_file(
                        $_FILES["image"]["tmp_name"],
                        $uploadPath
                    )
                ) {

                    /* =========================
                       INSERT PRODUCT
                    ========================= */

                    $stmt = $pdo->prepare(
                        "INSERT INTO products
                        (name, price, category, description, image)
                        VALUES (?, ?, ?, ?, ?)"
                    );

                    $stmt->execute([
                        $name,
                        $price,
                        $category,
                        $description,
                        $imageName
                    ]);


                    $message =
                        "Product added successfully!";


                } else {

                    $error =
                        "Image upload failed.";

                }

            }

        } else {

            $error =
                "Please select a product image.";

        }

    }

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
        Amazon Clone - Add Product
    </title>


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }


        body {
            background: #f3f3f3;
            color: #111;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {

            position: fixed;

            left: 0;
            top: 0;

            width: 240px;
            height: 100vh;

            background: #131921;

            color: white;

            padding: 25px 15px;

        }


        .sidebar-title {

            font-size: 22px;

            font-weight: bold;

            padding: 10px;

            margin-bottom: 30px;

            border-bottom: 1px solid #444;

        }


        .sidebar-menu {

            list-style: none;

        }


        .sidebar-menu li {

            margin-bottom: 8px;

        }


        .sidebar-menu a {

            display: flex;

            align-items: center;

            gap: 15px;

            color: white;

            text-decoration: none;

            padding: 13px 12px;

            border-radius: 5px;

            font-size: 16px;

        }


        .sidebar-menu a:hover {

            background: #232f3e;

        }


        .sidebar-menu i {

            width: 25px;

            text-align: center;

        }


        .dashboard-icon {
            color: #61dafb;
        }


        .products-icon {
            color: #ff9900;
        }


        .add-icon {
            color: #7c5cff;
        }


        .orders-icon {
            color: #ffd814;
        }


        .users-icon {
            color: #9b59b6;
        }


        .logout-icon {
            color: #ff6b35;
        }


        /* =========================
           MAIN
        ========================= */

        .main {

            margin-left: 240px;

            min-height: 100vh;

        }


        /* =========================
           TOP BAR
        ========================= */

        .top-navbar {

            height: 65px;

            background: #131921;

            color: white;

            display: flex;

            align-items: center;

            justify-content: flex-end;

            padding: 0 30px;

            gap: 20px;

        }


        .welcome {

            font-size: 15px;

        }


        .logout-top {

            background: #e47911;

            color: white;

            text-decoration: none;

            padding: 9px 15px;

            border-radius: 4px;

        }


        .logout-top:hover {

            background: #c65f00;

        }


        /* =========================
           CONTENT
        ========================= */

        .content {

            padding: 30px;

        }


        .page-title {

            font-size: 30px;

            margin-bottom: 25px;

        }


        /* =========================
           FORM CARD
        ========================= */

        .form-card {

            background: white;

            width: 100%;

            max-width: 700px;

            padding: 30px;

            border-radius: 8px;

            border: 1px solid while;

            box-shadow:
                0 2px 8px rgba(0,0,0,0.12);

        }


        .form-group {

            margin-bottom: 18px;

        }


        .form-group label {

            display: block;

            font-weight: bold;

            margin-bottom: 7px;

        }


        .form-group input,
        .form-group textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #aaa;

            border-radius: 4px;

            font-size: 15px;

        }


        .form-group input:focus,
        .form-group textarea:focus {

            outline: none;

            border: 2px solid #ff9900;

        }


        .form-group textarea {

            resize: vertical;

            min-height: 120px;

        }


        /* =========================
           IMAGE INPUT
        ========================= */

        .image-box {

            border: 2px dashed #aaa;

            padding: 25px;

            text-align: center;

            border-radius: 5px;

            background: while;

        }


        .image-box i {

            font-size: 40px;

            color: #ff9900;

            margin-bottom: 10px;

        }


        .image-box input {

            border: none;

            padding: 10px;

        }


        /* =========================
           BUTTON
        ========================= */

        .add-button {

            width: 100%;

            padding: 13px;

            background: #ffd814;

            color: #111;

            border: 1px solid #fcd200;

            border-radius: 5px;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

        }


        .add-button:hover {

            background: #f7ca00;

        }


        /* =========================
           MESSAGE
        ========================= */

        .success {

            background: while;

            color: #267326;

            border: 1px solid #b6d7a8;

            padding: 12px;

            margin-bottom: 20px;

            border-radius: 5px;

        }


        .error {

            background: #f8d7da;

            color: #842029;

            border: 1px solid #f1aeb5;

            padding: 12px;

            margin-bottom: 20px;

            border-radius: 5px;

        }


        /* =========================
           BACK BUTTON
        ========================= */

        .back-dashboard {

            display: inline-block;

            margin-top: 20px;

            color: #007185;

            text-decoration: none;

            font-weight: bold;

        }


        .back-dashboard:hover {

            text-decoration: underline;

        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 650px) {

            .sidebar {

                width: 70px;

                padding: 15px 8px;

            }


            .sidebar-title {

                font-size: 0;

                text-align: center;

            }


            .sidebar-title::after {

                content: "🛒";

                font-size: 25px;

            }


            .sidebar-menu a {

                justify-content: center;

                padding: 13px 5px;

            }


            .sidebar-menu span {

                display: none;

            }


            .main {

                margin-left: 70px;

            }


            .content {

                padding: 15px;

            }


            .top-navbar {

                padding: 0 10px;

            }


            .form-card {

                padding: 20px;

            }

        }

    </style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <div class="sidebar-title">

        Amazon Clone Admin

    </div>


    <ul class="sidebar-menu">


        <li>

            <a href="index.php">

                <i class="fa-solid fa-chart-column dashboard-icon"></i>

                <span>
                    Dashboard
                </span>

            </a>

        </li>


        <li>

            <a href="products.php">

                <i class="fa-solid fa-box products-icon"></i>

                <span>
                    Products
                </span>

            </a>

        </li>


        <li>

            <a href="add_product.php">

                <i class="fa-solid fa-plus add-icon"></i>

                <span>
                    Add Product
                </span>

            </a>

        </li>


        <li>

            <a href="#">

                <i class="fa-solid fa-receipt orders-icon"></i>

                <span>
                    Orders
                </span>

            </a>

        </li>


        <li>

            <a href="#">

                <i class="fa-solid fa-users users-icon"></i>

                <span>
                    Users
                </span>

            </a>

        </li>


        <li>

            <a href="logout.php">

                <i class="fa-solid fa-right-from-bracket logout-icon"></i>

                <span>
                    Logout
                </span>

            </a>

        </li>


    </ul>

</div>


<!-- =========================
     MAIN
========================= -->

<div class="main">


    <!-- TOP BAR -->

    <div class="top-navbar">

        <span class="welcome">

            Welcome,

            <?= htmlspecialchars($_SESSION["admin_username"]) ?>

        </span>


        <a
            href="logout.php"
            class="logout-top"
        >

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>


    <!-- CONTENT -->

    <div class="content">


        <h1 class="page-title">

            <i class="fa-solid fa-plus"></i>

            Add Product

        </h1>


        <!-- SUCCESS -->

        <?php if ($message !== ""): ?>

            <div class="success">

                <i class="fa-solid fa-circle-check"></i>

                <?= htmlspecialchars($message) ?>

            </div>

        <?php endif; ?>


        <!-- ERROR -->

        <?php if ($error !== ""): ?>

            <div class="error">

                <i class="fa-solid fa-circle-exclamation"></i>

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>


        <!-- FORM -->

        <div class="form-card">


            <form
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- PRODUCT NAME -->

                <div class="form-group">

                    <label>
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter product name"
                        required
                    >

                </div>


                <!-- PRICE -->

                <div class="form-group">

                    <label>
                        Price
                    </label>

                    <input
                        type="number"
                        name="price"
                        step="0.01"
                        placeholder="Enter price"
                        required
                    >

                </div>


                <!-- CATEGORY -->

                <div class="form-group">

                    <label>
                        Category
                    </label>

                    <input
                        type="text"
                        name="category"
                        placeholder="Enter category"
                        required
                    >

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Enter product description"
                    ></textarea>

                </div>


                <!-- IMAGE -->

                <div class="form-group">

                    <label>
                        Product Image
                    </label>


                    <div class="image-box">

                        <i class="fa-solid fa-cloud-arrow-up"></i>

                        <br>

                        <strong>
                            Select Product Image
                        </strong>

                        <br><br>

                        <input
                            type="file"
                            name="image"
                            accept="image/jpeg,image/png,image/webp,image/gif"
                            required
                        >

                    </div>

                </div>


                <!-- BUTTON -->

                <button
                    type="submit"
                    class="add-button"
                >

                    <i class="fa-solid fa-plus"></i>

                    Add Product

                </button>


            </form>


            <a
                href="index.php"
                class="back-dashboard"
            >

                ← Back to Dashboard

            </a>


        </div>

    </div>

</div>


</body>

</html>