<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$users = $pdo
    ->query("SELECT * FROM users")
    ->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Users - Amazon Clone Admin</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eaeded;
        }

        .header {
            background: #131921;
            color: white;
            padding: 20px;
        }

        .container {
            padding: 30px;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #232f3e;
            color: white;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #ff9900;
            color: black;
            padding: 10px 15px;
            border-radius: 5px;
        }

    </style>

</head>

<body>

<div class="header">
    <h2>Amazon Clone Admin - Users</h2>
</div>

<div class="container">

    <h1>Users</h1>

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>

        <?php foreach ($users as $user): ?>

        <tr>

            <td>
                <?= htmlspecialchars($user["id"]) ?>
            </td>

            <td>
                <?= htmlspecialchars($user["name"] ?? "") ?>
            </td>

            <td>
                <?= htmlspecialchars($user["email"] ?? "") ?>
            </td>

        </tr>

        <?php endforeach; ?>

    </table>

    <a href="index.php" class="back">
        ← Back to Dashboard
    </a>

</div>

</body>

</html>