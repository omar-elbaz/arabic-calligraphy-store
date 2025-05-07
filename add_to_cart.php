<?php
session_start();
include 'includes/products.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    
    // Return success response with cart count
    $total_items = array_sum($_SESSION['cart']);
    echo json_encode([
        'success' => true,
        'message' => 'Item added to cart',
        'cart_count' => $total_items
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
} 