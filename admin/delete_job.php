<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_jobs.php");
exit();
?>
