<?php
require_once '../includes/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user is Pro
$stmt = $pdo->prepare("SELECT account_type FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$is_pro = ($user['account_type'] == 'pro');

if ($is_pro) {
    echo json_encode(['success' => true, 'message' => 'Pro users are exempt from locking.']);
    exit();
}

// Lock the resume for Free users
try {
    $stmt = $pdo->prepare("UPDATE resumes SET is_locked = 1 WHERE user_id = ?");
    $result = $stmt->execute([$user_id]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Resume locked successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to lock resume.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
