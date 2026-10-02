<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$totalProducts = $pdo
    ->query("SELECT COUNT(*) FROM products")
    ->fetchColumn();

$totalUsers = $pdo
    ->query("SELECT COUNT(*) FROM users")
    ->fetchColumn();

$totalAdmins = $pdo
    ->query("SELECT COUNT(*) FROM admins")
    ->fetchColumn();

$totalOrders = 0;
$totalSales = 0;

try {

    $checkTable = $pdo->query("SHOW TABLES LIKE 'orders'");

    if ($checkTable->rowCount() > 0) {

        $totalOrders = $pdo
            ->query("SELECT COUNT(*) FROM orders")
            ->fetchColumn();

        $totalSales = $pdo
            ->query(
                "SELECT COALESCE(SUM(total_amount), 0) FROM orders"
            )
            ->fetchColumn();
    }

} catch (PDOException $e) {

    $totalOrders = 0;
    $totalSales = 0;
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

    <title>Amazon Clone - Admin</title>

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

            overflow-y: auto;
        }


        .sidebar-title {

            font-size: 23px;
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


        .amazon-icon {
            color: #00a8e1;
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

            color: white;

            background: #e47911;

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


        .dashboard-title {

            font-size: 32px;

            margin-bottom: 25px;
        }


        /* =========================
           STAT CARDS
        ========================= */

        .stats {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 20px;
        }


        .stat-card {

            background: white;

            min-height: 180px;

            border-radius: 8px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            align-items: center;

            text-align: center;

            border: 1px solid #ddd;

            box-shadow:
                0 2px 8px rgba(0,0,0,0.12);
        }


        .stat-card i {

            font-size: 40px;

            margin-bottom: 15px;
        }


        .product-icon {

            color: #c45500;
        }


        .user-icon {

            color: #2874f0;
        }


        .admin-icon {

            color: #d13232;
        }


        .sales-icon {

            color: #2ecc71;
        }


        .stat-card h3 {

            font-size: 18px;

            margin-bottom: 10px;
        }


        .stat-number {

            font-size: 32px;

            font-weight: bold;
        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 1000px) {

            .stats {

                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


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


            .top-navbar {

                padding: 0 10px;
            }


            .stats {

                grid-template-columns: 1fr;
            }


            .content {

                padding: 15px;
            }


            .dashboard-title {

                font-size: 26px;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     ONE SIDEBAR ONLY
========================= -->

<div class="sidebar">

    <div class="sidebar-title">
        Amazon Clone Admin
    </div>


    <ul class="sidebar-menu">


        <!-- Dashboard -->

        <li>

            <a href="index.php">

                <i class="fa-solid fa-gauge-high dashboard-icon"></i>

                <span>
                    Dashboard
                </span>

            </a>

        </li>


        <!-- Products -->

        <li>

            <a href="products.php">

                <i class="fa-solid fa-box products-icon"></i>

                <span>
                    Products
                </span>

            </a>

        </li>


        <!-- Add Product -->

        <li>

            <a href="add_product.php">

                <i class="fa-solid fa-plus add-icon"></i>

                <span>
                    Add Product
                </span>

            </a>

        </li>


        <!-- Orders -->

        <li>

            <a href="orders.php">

                <i class="fa-solid fa-receipt orders-icon"></i>

                <span>
                    Orders
                </span>

            </a>

        </li>


        <!-- Sales -->

        <li>

            <a href="sales.php">

                <i class="fa-solid fa-chart-line sales-icon"></i>

                <span>
                    Sales
                </span>

            </a>

        </li>


        <!-- Users -->

        <li>

            <a href="users.php">

                <i class="fa-solid fa-users users-icon"></i>

                <span>
                    Users
                </span>

            </a>

        </li>


        <!-- View Amazon -->

        <li>

            <a href="../amazon.php">

                <i class="fa-solid fa-globe amazon-icon"></i>

                <span>
                    View Amazon
                </span>

            </a>

        </li>


        <!-- Logout -->

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
     MAIN AREA
========================= -->

<div class="main">


    <!-- TOP NAVBAR -->

    <div class="top-navbar">

        <span class="welcome">

            Welcome,

            <?= htmlspecialchars(
                $_SESSION["admin_username"] ?? "Admin"
            ) ?>

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


        <h1 class="dashboard-title">

            <i class="fa-solid fa-gauge-high"></i>

            Dashboard

        </h1>


        <!-- =========================
             STAT CARDS
        ========================= -->

        <div class="stats">


            <!-- PRODUCTS -->

            <div class="stat-card">

                <i class="fa-solid fa-box product-icon"></i>

                <h3>
                    Total Products
                </h3>

                <div class="stat-number">

                    <?= $totalProducts ?>

                </div>

            </div>


            <!-- USERS -->

            <div class="stat-card">

                <i class="fa-solid fa-users user-icon"></i>

                <h3>
                    Total Users
                </h3>

                <div class="stat-number">

                    <?= $totalUsers ?>

                </div>

            </div>


            <!-- ADMINS -->

            <div class="stat-card">

                <i class="fa-solid fa-shield-halved admin-icon"></i>

                <h3>
                    Total Admins
                </h3>

                <div class="stat-number">

                    <?= $totalAdmins ?>

                </div>

            </div>


            <!-- SALES -->

            <div class="stat-card">

                <i class="fa-solid fa-indian-rupee-sign sales-icon"></i>

                <h3>
                    Sales
                </h3>

                <div class="stat-number">

                    ₹<?= number_format(
                        (float)$totalSales,
                        2
                    ) ?>

                </div>

            </div>


        </div>


    </div>

</div>


</body>

</html>