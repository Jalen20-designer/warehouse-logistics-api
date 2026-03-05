<?php
// api/user/profile.php
session_start();
require_once '../../config.php';
require_once '../../EncryptionHelper.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit;
}

// Fetch user and decrypt the sensitive email field
$stmt = $pdo->prepare("SELECT username, email_encrypted, iv_user, tag_user, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // MANDATORY REQUIREMENT: Decrypt before returning
    $email = EncryptionHelper::decrypt($user['email_encrypted'], ENCRYPTION_KEY, $user['iv_user'], $user['tag_user']);
    
    echo json_encode([
        "username" => $user['username'],
        "role" => $user['role'],
        "email" => $email
    ]);
}