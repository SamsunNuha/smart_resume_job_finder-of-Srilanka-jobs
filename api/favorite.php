<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $user_id = $_SESSION['user_id'];

    // Check if already favorited
    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$user_id, $job_id]);
    if ($stmt->fetch()) {
        // Remove from favorites
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND job_id = ?");
        $stmt->execute([$user_id, $job_id]);
        echo json_encode(['status' => 'success', 'message' => 'Removed from favorites', 'action' => 'removed']);
    } else {
        // Add to favorites
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, job_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $job_id]);
        echo json_encode(['status' => 'success', 'message' => 'Added to favorites', 'action' => 'added']);
    }
}
?>
