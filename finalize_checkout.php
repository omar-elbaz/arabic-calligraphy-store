<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/products.php';
require_once 'includes/mail.php';

if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Get user ID
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $userId = $user['id'];

    // Calculate total
    $total = 0;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $total += $products[$productId]['price'] * $quantity;
    }

    // Create order
    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $total, 'pending']);
    $orderId = $pdo->lastInsertId();

    // Add order items
    $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $stmt->execute([$orderId, $productId, $quantity, $products[$productId]['price']]);
    }

    // Commit transaction
    $pdo->commit();

    // Send receipt email
    if (sendOrderReceipt($orderId, $_SESSION['user'])) {
        $_SESSION['order_success'] = true;
    } else {
        error_log("Failed to send order receipt email for order #" . $orderId);
    }

    // Clear cart
    $_SESSION['cart'] = [];

} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    error_log("Error processing order: " . $e->getMessage());
    $_SESSION['order_error'] = 'There was an error processing your order. Please try again.';
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 60vh;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); padding: 40px 32px; max-width: 400px; width: 100%; text-align: center;">
        <i class="fas fa-check-circle" style="font-size: 48px; color: #27ae60; margin-bottom: 20px;"></i>
        <h2 style="margin-top: 0;">Thank you for your purchase!</h2>
        <p>Your order has been received and a confirmation email has been sent to your email address.</p>
        <p>Order #<?= $orderId ?></p>
        <a href="index.php"><button style="margin-top: 20px;">Back to Home</button></a>
    </div>
</div>
</body>
</html> 