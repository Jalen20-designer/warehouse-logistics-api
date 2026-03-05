<?php
// api/auth/register.php
require_once '../../config.php';
require_once '../../EncryptionHelper.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(["message" => "All fields are required."]);
    exit;
}

// 1. Passwords MUST be hashed (Rule from page 1)
$passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

// 2. Sensitive fields (Email) MUST be encrypted (Rule from page 1)
$enc = EncryptionHelper::encrypt($data['email'], ENCRYPTION_KEY);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email_encrypted, iv_user, tag_user, role) VALUES (?, ?, ?, ?, ?, ?)");
    $role = $data['role'] ?? 'user';
    
    $stmt->execute([
        $data['username'],
        $passwordHash,
        $enc['content'],
        $enc['iv'],
        $enc['tag'],
        $role
    ]);

    http_response_code(201);
    echo json_encode(["message" => "User registered successfully!"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error: User might already exist."]);
}