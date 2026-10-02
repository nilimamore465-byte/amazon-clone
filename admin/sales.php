<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| SALES DATA
|--------------------------------------------------------------------------
|Here you can see the total sales and total orders from your amazon clone.
*/

$totalSales = 0;
$totalOrders = 0;

/* Check whether orders table exists */

try {

    $checkTable = $pdo->query(
        "SHOW TABLES LIKE 'orders'"
    );

    if ($checkTable->rowCount() > 0) {

        $totalOrders = $pdo
            ->query("SELECT COUNT(*) FROM orders")
            ->fetchColumn();

        $totalSales = $pdo
            ->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders")
            ->fetchColumn();
    }

} catch (PDOException $e) {

    $totalSales = 0;
    $totalOrders = 0;
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

    <title>Sales - Amazon Clone Admin</title>

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
            background: #eaeded;
            color: #111;
        }

        /* SIDEBAR */

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

        /* MAIN */

        .main {
            margin-left: 240px;
            min-height: 100vh;
        }

        /* TOP NAVBAR */

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

        /* CONTENT */

        .content {
            padding: 30px;
        }

        .page-title {
            font-size: 30px;
            margin-bottom: 25px;
        }

        /* SALES CARDS */

        .sales-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            max-width: 900px;
        }

        .sales-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .sales-icon-box {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #e8f8ee;
            color: #2ecc71;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .sales-card h3 {
            font-size: 17px;
            color: #555;
            margin-bottom: 10px;
        }

        .sales-number {
            font-size: 30px;
            font-weight: bold;
            color: #111;
        }

        /* INFO */

        .sales-info {
            background: white;
            margin-top: 30px;
            padding: 25px;
            max-width: 900px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .sales-info h2 {
            margin-bottom: 10px;
        }

        .back-dashboard {
            display: inline-block;
            margin-top: 25px;
            color: #007185;
            text-decoration: none;
            font-weight: bold;
        }

        /* MOBILE */

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

            .sales-grid {
                grid-template-columns: 1fr;
            }

            .top-navbar {
                padding: 0 10px;
            }
        }

    </style>

</head>

<body>

<!-- SIDEBAR -->

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
            <a href="#">
                <i class="fa-solid fa-receipt orders-icon"></i>
                <span>Orders</span>
            </a>
        </li>

        <!-- SALES -->

        <li>
            <a href="sales.php">
                <i class="fa-solid fa-chart-line sales-icon"></i>
                <span>Sales</span>
            </a>
        </li>

        <li>
            <a href="#">
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


<!-- MAIN -->

<div class="main">

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


    <div class="content">

        <h1 class="page-title">
            <i class="fa-solid fa-chart-line"></i>
            Sales
        </h1>


        <div class="sales-grid">

            <!-- TOTAL SALES -->

            <div class="sales-card">

                <div class="sales-icon-box">

                    <i class="fa-solid fa-indian-rupee-sign"></i>

                </div>

                <div>

                    <h3>
                        Total Sales
                    </h3>

                    <div class="sales-number">

                        ₹<?= number_format($totalSales, 2) ?>

                    </div>

                </div>

            </div>


            <!-- TOTAL ORDERS -->

            <div class="sales-card">

                <div class="sales-icon-box">

                    <i class="fa-solid fa-cart-shopping"></i>

                </div>

                <div>

                    <h3>
                        Total Orders
                    </h3>

                    <div class="sales-number">

                        <?= $totalOrders ?>

                    </div>

                </div>

            </div>

        </div>


        <div class="sales-info">

            <h2>
                Sales Overview
            </h2>

            <p>
                Here you can see the total sales and total orders from your amazon clone
            </p>

        </div>


        <a
            href="index.php"
            class="back-dashboard"
        >
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>