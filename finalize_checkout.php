<?php
session_start();
$_SESSION['cart'] = [];
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
        <h2 style="margin-top: 0;">Thank you for your purchase!</h2>
        <p>We'll reach out to confirm your order soon.</p>
        <a href="index.php"><button style="margin-top: 20px;">Back to Home</button></a>
    </div>
</div>
</body>
</html> 