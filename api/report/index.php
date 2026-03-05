<?php
// api/reports/index.php
session_start();
require_once '../../config.php';

// Only Admin can see reports
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["message" => "Access denied. Admin only."]);
    exit;
}

$report_type = $_GET['type'] ?? 'status';

// --- Report: Delivery Status ---
if ($report_type === 'status') {
    $stmt = $pdo->query("SELECT status, COUNT(*) as total FROM shipments GROUP BY status");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "report_name" => "Current Delivery Status",
        "data" => $data
    ]);
}

// --- Report: Logistics Performance ---
elseif ($report_type === 'performance') {
    // Simple logic: Count total shipments vs delivered
    $total = $pdo->query("SELECT COUNT(*) FROM shipments")->fetchColumn();
    $delivered = $pdo->query("SELECT COUNT(*) FROM shipments WHERE status = 'delivered'")->fetchColumn();
    
    $rate = ($total > 0) ? round(($delivered / $total) * 100, 2) : 0;

    echo json_encode([
        "report_name" => "Logistics Performance",
        "metrics" => [
            "total_shipments" => $total,
            "delivered_count" => $delivered,
            "success_rate" => $rate . "%"
        ]
    ]);
}