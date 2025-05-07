<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'Please enter a valid email address.';
        header('Location: register.php');
        exit();
    }
    if (strlen($password) < 6) {
        $_SESSION['register_error'] = 'Password must be at least 6 characters.';
        header('Location: register.php');
        exit();
    }

    // Check if user already exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['register_error'] = 'Email is already registered.';
        header('Location: register.php');
        exit();
    }

    // Hash password and insert
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
    if ($stmt->execute([$email, $hash])) {
        $_SESSION['register_success'] = 'Registration successful! You can now log in.';
        header('Location: register.php');
        exit();
    } else {
        $_SESSION['register_error'] = 'Registration failed. Please try again.';
        header('Location: register.php');
        exit();
    }
} else {
    header('Location: register.php');
    exit();
} 