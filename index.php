<?php
include 'includes/products.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Arabic Calligraphy Store</title>
    <link rel="icon" type="image/png" href="assets/arabic_icon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="notification" id="notification"></div>

<div class="landing-section">
    <div class="welcome-content">
        <h2>Welcome to Our Arabic Calligraphy Store</h2>
        <p>Discover the beauty of Arabic calligraphy through our carefully curated collection of art pieces. Each piece is a unique expression of this ancient art form, bringing elegance and cultural richness to your space.</p>
        <p>Our calligraphy works are created by skilled artists who have mastered the traditional techniques while incorporating modern elements to create timeless pieces.</p>
    </div>
</div>

<div class="container">
    <?php foreach ($products as $id => $product): ?>
        <div class="card">
            <div class="card-image">
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            </div>
            <div class="card-content">
                <h3><?= $product['name'] ?></h3>
                <p class="price">$<?= $product['price'] ?></p>
                <p class="description"><?= $product['description'] ?></p>
                <button onclick="addToCart(<?= $id ?>)" class="add-to-cart-btn">
                    <i class="fas fa-cart-plus"></i>
                    Add to Cart
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function addToCart(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            document.querySelector('.cart-count').textContent = data.cart_count;
            
            // Show notification
            const notification = document.getElementById('notification');
            notification.textContent = 'Item added to cart!';
            notification.classList.add('show');
            
            // Hide notification after 2 seconds
            setTimeout(() => {
                notification.classList.remove('show');
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
</body>
</html>