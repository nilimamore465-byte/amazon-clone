<?php
session_start();
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        header("Location: amazon.php");
        exit;

    } else {

        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Amazon Clone</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #eaeded;
        }

        .login-box {
            width: 350px;
            margin: 60px auto;
            padding: 25px;
            background: white;
            border: 1px solid #ddd;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 11px;
            margin-top: 20px;
            background: #ffd814;
            border: 1px solid #fcd200;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

    </style>

</head>

<body>

<div class="login-box">

    <h2>Login</h2>

    <?php if ($message != ""): ?>

        <p class="error">
            <?= htmlspecialchars($message) ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <div class="register-link">

        New to Amazon Clone?

        <a href="register.php">
            Create account
        </a>

    </div>

</div>

</body>
</html>