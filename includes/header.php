<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header>
    <a href="/index.php" class="site-title-link"><h1>Arabic Calligraphy</h1></a>
    <div class="nav-buttons">
        <?php if (isset($_SESSION['user'])): ?>
            <a href="/profile.php" class="profile-button">
                <i class="fas fa-user"></i>
                Profile
            </a>
        <?php else: ?>
            <a href="/login.php" class="login-button">
                <i class="fas fa-user"></i>
                Login
            </a>
        <?php endif; ?>
        <a href="/cart.php" class="cart-button">
            <i class="fas fa-shopping-cart"></i>
            Cart
            <span class="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
        </a>
    </div>
</header> 