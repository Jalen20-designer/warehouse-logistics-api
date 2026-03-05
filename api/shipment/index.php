<?php
// api/shipments/index.php
session_start();
require_once '../../config.php';
require_once '../../EncryptionHelper.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// --- POST: Create a Shipment ---
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (empty($data['shipment_code']) || empty($data['warehouse_id']) || empty($data['destination'])) {
        http_response_code(400);
        echo json_encode(["message" => "Code, Warehouse ID, and Destination are required"]);
        exit;
    }

    // MANDATORY REQUIREMENT: Encrypt the Destination (Sensitive Field #2)
    $encDestination = EncryptionHelper::encrypt($data['destination'], ENCRYPTION_KEY);

    try {
        $stmt = $pdo->prepare("INSERT INTO shipments (shipment_code, warehouse_id, user_id, origin, destination, iv_shipment, tag_shipment, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['shipment_code'],
            $data['warehouse_id'],
            $_SESSION['user_id'],
            $data['origin'] ?? 'Warehouse Hub',
            $encDestination['content'],
            $encDestination['iv'],
            $encDestination['tag'],
            'pending'
        ]);

        http_response_code(201);
        echo json_encode(["message" => "Shipment created successfully"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Error: Duplicate shipment code or DB error."]);
    }
}

// --- GET: View Shipments ---
elseif ($method === 'GET') {
    if (isset($_GET['id'])) {
        // Fetch specific shipment
        $stmt = $pdo->prepare("SELECT * FROM shipments WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($shipment) {
            // Decrypt destination before returning
            $shipment['destination'] = EncryptionHelper::decrypt($shipment['destination'], ENCRYPTION_KEY, $shipment['iv_shipment'], $shipment['tag_shipment']);
            echo json_encode($shipment);
        } else {
            echo json_encode(["message" => "Not found"]);
        }
    } else {
        // List all shipments
        $stmt = $pdo->query("SELECT * FROM shipments");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}