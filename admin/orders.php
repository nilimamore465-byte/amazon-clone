<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

/* =========================
   GET ORDERS
========================= */

$orders = $pdo
    ->query("
        SELECT id, user_id, total_amount, order_date
        FROM orders
        ORDER BY id DESC
    ")
    ->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Orders - Amazon Clone Admin</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #eaeded;
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

        .sales-icon {
            color: #2ecc71;
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
           TOP NAVBAR
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
           ORDERS TABLE
        ========================= */

        .orders-box {
            background: white;

            padding: 20px;

            border-radius: 8px;

            border: 1px solid #ddd;

            box-shadow:
                0 2px 8px rgba(0,0,0,0.12);

            overflow-x: auto;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 650px;
        }

        th {
            background: #232f3e;

            color: white;

            padding: 14px;

            text-align: left;
        }

        td {
            padding: 14px;

            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f7f7f7;
        }

        .amount {
            font-weight: bold;
            color: #008a45;
        }

        .no-orders {
            text-align: center;

            padding: 30px;

            color: #555;

            font-size: 18px;
        }

        /* =========================
           BACK BUTTON
        ========================= */

        .back-dashboard {
            display: inline-block;

            margin-top: 25px;

            color: #007185;

            text-decoration: none;

            font-weight: bold;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {

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

                <span>Dashboard</span>

            </a>
        </li>

        <li>
            <a href="products.php">

                <i class="fa-solid fa-box products-icon"></i>

                <span>Products</span>

            </a>
        </li>

        <li>
            <a href="add_product.php">

                <i class="fa-solid fa-plus add-icon"></i>

                <span>Add Product</span>

            </a>
        </li>

        <li>
            <a href="orders.php">

                <i class="fa-solid fa-receipt orders-icon"></i>

                <span>Orders</span>

            </a>
        </li>

        <li>
            <a href="sales.php">

                <i class="fa-solid fa-chart-line sales-icon"></i>

                <span>Sales</span>

            </a>
        </li>

        <li>
            <a href="users.php">

                <i class="fa-solid fa-users users-icon"></i>

                <span>Users</span>

            </a>
        </li>

        <li>
            <a href="logout.php">

                <i class="fa-solid fa-right-from-bracket logout-icon"></i>

                <span>Logout</span>

            </a>
        </li>

    </ul>

</div>


<!-- =========================
     MAIN
========================= -->

<div class="main">

    <div class="top-navbar">

        <span class="welcome">

            Welcome,
            <?= htmlspecialchars($_SESSION["admin_username"]) ?>

        </span>

        <a href="logout.php"
           class="logout-top">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>


    <div class="content">

        <h1 class="page-title">

            <i class="fa-solid fa-receipt"></i>

            Orders

        </h1>


        <div class="orders-box">

            <?php if (count($orders) > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Order ID</th>

                            <th>User ID</th>

                            <th>Total Amount</th>

                            <th>Order Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($orders as $order): ?>

                            <tr>

                                <td>
                                    #<?= htmlspecialchars($order["id"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($order["user_id"] ?? "Guest") ?>
                                </td>

                                <td class="amount">

                                    ₹<?= number_format(
                                        (float)$order["total_amount"],
                                        2
                                    ) ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($order["order_date"]) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="no-orders">

                    No orders found.

                </div>

            <?php endif; ?>

        </div>


        <a href="index.php"
           class="back-dashboard">

            ← Back to Dashboard

        </a>

    </div>

</div>

</body>

</html>