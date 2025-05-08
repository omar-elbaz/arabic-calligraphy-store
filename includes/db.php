<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Try to load .env file if it exists (local development)
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Get database configuration from environment variables
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT');
$dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
$db_user = $_ENV['DB_USER'] ?? getenv('DB_USER');
$db_pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS');

// Validate required database configuration
if (!$host || !$port || !$dbname || !$db_user || !$db_pass) {
    error_log('Database configuration missing. Please check your environment variables.');
    die('Database configuration error. Please contact support.');
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    die('Database connection error. Please try again later.');
} 