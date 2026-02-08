<?php
session_start();
require_once '../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Create table if not exists (Lazy Load)
$pdo->exec("CREATE TABLE IF NOT EXISTS resume_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    resume_snapshot LONGTEXT,
    version_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$user_id = $_SESSION['user_id'];
$name = $_POST['version_name'] ?? 'Auto-Backup ' . date('M d, H:i');

// Fetch current resume
$stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
$stmt->execute([$user_id]);
$resume = $stmt->fetch(PDO::FETCH_ASSOC);

if($resume) {
    // Save snapshot
    $snapshot = json_encode($resume);
    $stmt = $pdo->prepare("INSERT INTO resume_versions (user_id, resume_snapshot, version_name) VALUES (?, ?, ?)");
    if($stmt->execute([$user_id, $snapshot, $name])) {
        echo json_encode(['status' => 'success', 'timestamp' => date('H:i')]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'DB Insert Failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No resume to save']);
}
?>
