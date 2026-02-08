<?php
require_once 'includes/db.php';

try {
    $sql = file_get_contents('migrate_lock_feature.sql');
    $pdo->exec($sql);
    echo "Migration successful: 'is_locked' column added to 'resumes' table.";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
