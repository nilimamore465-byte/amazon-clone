<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"])) {

    $id = (int) $_GET["id"];

    // Get image name
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    // Delete image from uploads folder
    if ($product && !empty($product["image"])) {

        $imagePath = "../uploads/" . $product["image"];

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}

header("Location: products.php");
exit;
?>