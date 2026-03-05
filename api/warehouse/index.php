<?php
// api/warehouse/index.php
session_start();
require_once '../../config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(["message" => "Forbidden: Admin access required"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['name']) || empty($data['location']) || empty($data['capacity'])) {
        http_response_code(400);
        echo json_encode(["message" => "Missing warehouse details"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO warehouses (name, location, capacity) VALUES (?, ?, ?)");
    $stmt->execute([$data['name'], $data['location'], $data['capacity']]);
    
    http_response_code(201);
    echo json_encode(["message" => "Warehouse created successfully"]);
}

elseif ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM warehouses");
    echo json_encode($stmt->fetchAll());
}