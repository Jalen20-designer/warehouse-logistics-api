<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'warehouse_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Mandatory Encryption Key: 32-byte HEX string converted to binary
// You can use: bin2hex(openssl_random_pseudo_bytes(32)) to generate a real one
define('ENCRYPTION_KEY', hex2bin('4e616d65596f75724f776e33324279746552616e646f6d4865784b6579212121'));

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed"]));
}