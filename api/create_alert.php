<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

// Auto-create table if not exists (Lazy migration)
$pdo->exec("CREATE TABLE IF NOT EXISTS job_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    keywords VARCHAR(255),
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$keywords = $_POST['keywords'] ?? '';
$location = $_POST['location'] ?? '';

if (!$keywords && !$location) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide keywords or location.']);
    exit();
}

$stmt = $pdo->prepare("INSERT INTO job_alerts (user_id, keywords, location) VALUES (?, ?, ?)");
if ($stmt->execute([$_SESSION['user_id'], $keywords, $location])) {
    echo json_encode(['status' => 'success', 'message' => 'Job alert created! We will notify you of new matches.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
?>
