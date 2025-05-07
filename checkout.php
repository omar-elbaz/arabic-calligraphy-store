<?php
session_start();
include 'includes/products.php';
include_once 'includes/db.php';
$cart = $_SESSION['cart'] ?? [];

// If not logged in, redirect to login page
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Fetch user info
$stmt = $pdo->prepare('SELECT first_name, last_name, address, phone FROM users WHERE email = ?');
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$missing = [];
foreach (['first_name', 'last_name', 'address', 'phone'] as $field) {
    if (empty($user[$field])) $missing[] = $field;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($missing)) {
    // Save missing info
    $update = [];
    foreach ($missing as $field) {
        $update[$field] = trim($_POST[$field] ?? '');
    }
    $stmt = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, address = ?, phone = ? WHERE email = ?');
    $stmt->execute([
        $update['first_name'] ?? $user['first_name'],
        $update['last_name'] ?? $user['last_name'],
        $update['address'] ?? $user['address'],
        $update['phone'] ?? $user['phone'],
        $_SESSION['user']
    ]);
    // Refresh user info
    $stmt = $pdo->prepare('SELECT first_name, last_name, address, phone FROM users WHERE email = ?');
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $missing = [];
    foreach (['first_name', 'last_name', 'address', 'phone'] as $field) {
        if (empty($user[$field])) $missing[] = $field;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1000;
            display: none;
        }
        .modal {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.18);
            padding: 36px 32px 28px 32px;
            z-index: 1001;
            min-width: 340px;
            max-width: 95vw;
            display: none;
        }
        .modal.active, .modal-overlay.active { display: block; }
        .modal-close {
            position: absolute;
            top: 16px;
            right: 20px;
            font-size: 1.5em;
            color: #888;
            background: none;
            border: none;
            cursor: pointer;
        }
        .modal h3 { margin-top: 0; }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>
<h2 style="text-align:center; margin-top: 30px;">Checkout</h2>

<?php if (!empty($cart)): ?>
    <div class="cart-list-container">
        <ul class="cart-list">
            <?php foreach ($cart as $id => $qty):
                $item = $products[$id];
            ?>
                <li class="cart-list-item">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img">
                    <div class="cart-item-info">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p>Quantity: <?= $qty ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div style="text-align:center; margin: 30px 0;">
        <button id="openCheckoutModal" style="padding: 12px 32px; font-size: 1.1em; background: #27ae60; color: #fff; border: none; border-radius: 6px; cursor: pointer;">Proceed to Checkout</button>
    </div>
<?php endif; ?>

<div class="modal-overlay" id="modalOverlay"></div>
<div class="modal" id="checkoutModal">
    <button class="modal-close" id="closeModalBtn">&times;</button>
    <?php if (!empty($missing)): ?>
        <h3>Please provide your information to complete the purchase:</h3>
        <form method="POST" class="profile-form" id="infoForm">
            <?php foreach (['first_name'=>'First Name', 'last_name'=>'Last Name', 'address'=>'Address', 'phone'=>'Phone Number'] as $field => $label): ?>
                <?php if (in_array($field, $missing)): ?>
                    <div class="form-group">
                        <label for="<?= $field ?>"><?= $label ?></label>
                        <?php if ($field === 'address'): ?>
                            <textarea id="address" name="address" rows="3" required></textarea>
                        <?php else: ?>
                            <input type="text" id="<?= $field ?>" name="<?= $field ?>" required>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <button type="submit">Save and Continue</button>
        </form>
    <?php else: ?>
        <h3>Confirm your information:</h3>
        <ul style="list-style:none;padding:0;">
            <li><strong>Name:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></li>
            <li><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></li>
            <li><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></li>
        </ul>
        <form method="POST" action="finalize_checkout.php">
            <button type="submit">Confirm Purchase</button>
        </form>
    <?php endif; ?>
</div>

<script>
const openModalBtn = document.getElementById('openCheckoutModal');
const modal = document.getElementById('checkoutModal');
const overlay = document.getElementById('modalOverlay');
const closeModalBtn = document.getElementById('closeModalBtn');

if (openModalBtn) {
    openModalBtn.onclick = function() {
        modal.classList.add('active');
        overlay.classList.add('active');
    };
}
if (closeModalBtn) {
    closeModalBtn.onclick = function() {
        modal.classList.remove('active');
        overlay.classList.remove('active');
    };
}
if (overlay) {
    overlay.onclick = function() {
        modal.classList.remove('active');
        overlay.classList.remove('active');
    };
}
// Auto-open modal if info was just submitted or all info is present
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' || empty($missing)): ?>
    window.onload = function() {
        modal.classList.add('active');
        overlay.classList.add('active');
    };
<?php endif; ?>
</script>
</body>
</html>