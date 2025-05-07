<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['user'])) {
    $cart = $_SESSION['cart'] ?? [];
    $stmt = $pdo->prepare('UPDATE users SET cart = ? WHERE email = ?');
    $stmt->execute([json_encode($cart), $_SESSION['user']]);
}

session_unset();
session_destroy();
header('Location: index.php');
exit(); 