<?php

session_start();
require_once "../db.php";

// Admin login check
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

// Get all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Products - Amazon Clone</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eaeded;
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
            margin: 0;
        }

        .back {
            color: white;
            text-decoration: none;
            background: #37475a;
            padding: 10px 15px;
            border-radius: 4px;
        }

        .back:hover {
            background: #485769;
        }

        /* =========================
           CONTAINER
        ========================= */

        .container {
            width: 95%;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        }

        h1 {
            margin-top: 0;
        }

        /* =========================
           ADD BUTTON
        ========================= */

        .add-btn {
            display: inline-block;
            background: #ffd814;
            color: #111;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .add-btn:hover {
            background: #f7ca00;
        }

        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #232f3e;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        tr:hover {
            background: #f7f7f7;
        }

        /* =========================
           PRODUCT IMAGE
        ========================= */

        .product-image {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 5px;
            background: white;
            border-radius: 4px;
        }

        /* =========================
           ACTION BUTTONS
        ========================= */

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .edit-btn {
            display: inline-block;
            background: #ffd814;
            color: #111;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .edit-btn:hover {
            background: #f7ca00;
        }

        .delete-btn {
            display: inline-block;
            background: #d11a2a;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .delete-btn:hover {
            background: #b31321;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty {
            text-align: center;
            padding: 30px;
            color: #666;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {

            .header {
                padding: 15px;
            }

            .header h2 {
                font-size: 18px;
            }

            .back {
                padding: 8px 10px;
                font-size: 13px;
            }

            .container {
                width: 98%;
                padding: 15px;
                margin: 15px auto;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .edit-btn,
            .delete-btn {
                text-align: center;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <h2>Amazon Clone - Admin</h2>

    <a href="index.php" class="back">
        Back to Dashboard
    </a>

</div>


<div class="container">

    <h1>Manage Products</h1>

    <a href="add_product.php" class="add-btn">
        + Add Product
    </a>


    <?php if (count($products) > 0): ?>

    <div class="table-wrapper">

    <table>

        <thead>

            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Description</th>
                <th>Action</th>
            </tr>

        </thead>


        <tbody>

        <?php foreach ($products as $product): ?>

            <tr>

                <td>

                    <?php if (!empty($product["image"])): ?>

                        <img
                            src="../uploads/<?= htmlspecialchars($product["image"]) ?>"
                            class="product-image"
                            alt="<?= htmlspecialchars($product["name"]) ?>"
                        >

                    <?php else: ?>

                        No Image

                    <?php endif; ?>

                </td>


                <td>
                    <?= htmlspecialchars($product["name"]) ?>
                </td>


                <td>
                    ₹<?= htmlspecialchars($product["price"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($product["category"]) ?>
                </td>


                <td>
                    <?= htmlspecialchars($product["description"]) ?>
                </td>


                <td>

                    <div class="action-buttons">

                        <!-- EDIT -->

                        <a
                            href="edit_product.php?id=<?= $product["id"] ?>"
                            class="edit-btn"
                        >
                            ✏️ Edit
                        </a>


                        <!-- DELETE -->

                        <a
                            href="delete_product.php?id=<?= $product["id"] ?>"
                            class="delete-btn"
                            onclick="return confirm('Delete this product?');"
                        >
                            🗑️ Delete
                        </a>

                    </div>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

    </div>


    <?php else: ?>

        <div class="empty">
            No products found.
        </div>

    <?php endif; ?>


</div>

</body>

</html>