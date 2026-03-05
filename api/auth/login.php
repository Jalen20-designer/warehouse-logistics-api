<?php
// api/auth/login.php
session_start();
require_once '../../config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['username']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Login details required."]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$data['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($data['password'], $user['password_hash'])) {
    // Start session and store info
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    
    echo json_encode([
        "message" => "Login successful!",
        "role" => $user['role']
    ]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid username or password."]);
}