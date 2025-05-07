<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $stmt = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, address = ?, phone = ? WHERE email = ?');
    if ($stmt->execute([$first_name, $last_name, $address, $phone, $_SESSION['user']])) {
        $_SESSION['profile_update_msg'] = 'Profile updated successfully!';
    } else {
        $_SESSION['profile_update_msg'] = 'Failed to update profile.';
    }
    header('Location: profile.php');
    exit();
} else {
    header('Location: profile.php');
    exit();
} 