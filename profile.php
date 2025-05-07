<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Fetch user info
$stmt = $pdo->prepare('SELECT first_name, last_name, address, phone FROM users WHERE email = ?');
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle update message
$update_msg = '';
if (!empty($_SESSION['profile_update_msg'])) {
    $update_msg = $_SESSION['profile_update_msg'];
    unset($_SESSION['profile_update_msg']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile - Arabic Calligraphy Store</title>
    <link rel="icon" type="image/png" href="assets/arabic_icon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            max-width: 500px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .form-group label {
            color: #333;
            font-weight: 500;
        }
        .form-group input, .form-group textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        .profile-form button {
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .profile-form button:hover {
            background: #219150;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
        }
        .logout-link {
            text-align: center;
            margin-top: 20px;
        }
        .logout-link a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="profile-container">
    <h2>Your Profile</h2>
    <?php if ($update_msg): ?>
        <div style="color: #27ae60; text-align: center; margin-bottom: 15px; font-weight: bold;">
            <?= htmlspecialchars($update_msg) ?>
        </div>
    <?php endif; ?>
    <form class="profile-form" method="POST" action="update_profile.php">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>
        <button type="submit">
            <i class="fas fa-save"></i>
            Save Changes
        </button>
    </form>
    <div class="logout-link">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html> 