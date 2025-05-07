<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Arabic Calligraphy Store</title>
    <link rel="icon" type="image/png" href="assets/arabic_icon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .register-container {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .register-form {
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
        .form-group input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        .register-form button {
            background: #4a90e2;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .register-form button:hover {
            background: #357abd;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(74, 144, 226, 0.3);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .login-link a {
            color: #4a90e2;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="register-container">
    <h2>Create an Account</h2>
    <?php if (!empty($_SESSION['register_error'])): ?>
        <div style="color: #e74c3c; text-align: center; margin-bottom: 15px; font-weight: bold;">
            <?= $_SESSION['register_error']; unset($_SESSION['register_error']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['register_success'])): ?>
        <div style="color: #27ae60; text-align: center; margin-bottom: 15px; font-weight: bold;">
            <?= $_SESSION['register_success']; unset($_SESSION['register_success']); ?>
        </div>
    <?php endif; ?>
    <form class="register-form" method="POST" action="process_register.php">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">
            <i class="fas fa-user-plus"></i>
            Register
        </button>
    </form>
    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

</body>
</html> 