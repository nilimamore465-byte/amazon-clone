<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Amazon Clone - Orders</title>

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

        .home-btn {
            color: white;
            text-decoration: none;
            background: #37475a;
            padding: 10px 18px;
            border-radius: 5px;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .orders-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
        }

        .orders-box h1 {
            margin-bottom: 15px;
        }

        .orders-box p {
            color: #555;
            margin-bottom: 25px;
        }

        .shop-btn {
            display: inline-block;
            background: #ffd814;
            color: #111;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="header">

    <h2>
        Amazon Clone - Your Orders
    </h2>

    <a
        href="amazon.php"
        class="home-btn"
    >
        ← Back to Amazon
    </a>

</div>


<div class="container">

    <div class="orders-box">

        <h1>
            Your Orders
        </h1>

        <p>
            You don't have any orders yet.
        </p>

        <a
            href="amazon.php"
            class="shop-btn"
        >
            Continue Shopping
        </a>

    </div>

</div>

</body>

</html>