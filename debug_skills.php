<?php
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT id, skills FROM resumes");
$resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($resumes as $r) {
    echo "ID: " . $r['id'] . "\n";
    echo "Raw: " . $r['skills'] . "\n";
    echo "Hex: " . bin2hex($r['skills']) . "\n";
    echo "-------------------\n";
}
?>
