<?php
require_once 'includes/db.php';
$stmt = $pdo->query("SHOW COLUMNS FROM resumes");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($columns);
?>
