<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$template_id = $data['template_id'] ?? null;

if (!$template_id) {
    echo json_encode(['success' => false, 'message' => 'Template ID required']);
    exit();
}

// Check if resume exists
$stmt = $pdo->prepare("SELECT id FROM resumes WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$exists = $stmt->fetch();

if ($exists) {
    $stmt = $pdo->prepare("UPDATE resumes SET template_id = ? WHERE user_id = ?");
    $res = $stmt->execute([$template_id, $_SESSION['user_id']]);
} else {
    // Basic insert if doesn't exist yet
    $stmt = $pdo->prepare("INSERT INTO resumes (user_id, template_id, full_name, email, phone, summary, skills) VALUES (?, ?, '', '', '', '', '')");
    $res = $stmt->execute([$_SESSION['user_id'], $template_id]);
}

if ($res) {
    echo json_encode(['success' => true, 'message' => 'Template saved']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
