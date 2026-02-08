<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

// Auto-create table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(50) NOT NULL,
    details VARCHAR(255),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    exit(json_encode(['status' => 'error', 'message' => 'No data provided']));
}

$user_id = $_SESSION['user_id'] ?? null;
$action = $data['action'] ?? 'unknown';
$details = $data['details'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];

$stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$user_id, $action, $details, $ip])) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
