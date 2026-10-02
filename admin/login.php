<?php

session_start();
require_once "../db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare(
        "SELECT * FROM admins WHERE username = ?"
    );

    $stmt->execute([$username]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && $password === $admin["password"]) {

        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_username"] = $admin["username"];

        header("Location: index.php");
        exit;

    } else {

        $error = "Invalid admin username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Amazon Clone - Admin Login</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eaeded;
        }

        .top-bar {
            height: 60px;
            background: #131921;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: bold;
        }

        .login-box {
            width: 380px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #888;
            border-radius: 3px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ffd814;
            border: 1px solid #fcd200;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #f7ca00;
        }

        .error {
            color: red;
            background: #ffe5e5;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
        }

    </style>

</head>

<body>

<div class="top-bar">
    Amazon Clone
</div>

<div class="login-box">

    <h2>Admin Login</h2>

    <?php if ($error != ""): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Username</label>

        <input
            type="text"
            name="username"
            placeholder="Enter admin username"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter admin password"
            required
        >

        <button type="submit">
            Admin Login
        </button>

    </form>

</div>

</body>

</html>