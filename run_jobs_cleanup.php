<?php
require_once 'includes/db.php';

try {
    // 1. Simple Replace
    $pdo->query("UPDATE jobs SET requirements = REPLACE(requirements, 'Skill 1, Skill 2, ', '')");
    $pdo->query("UPDATE jobs SET requirements = REPLACE(requirements, 'Skill 1, Skill 2', '')");
    
    // 2. Cover edge cases
    $pdo->query("UPDATE jobs SET requirements = REPLACE(requirements, 'Skill 1', '')");
    $pdo->query("UPDATE jobs SET requirements = REPLACE(requirements, 'Skill 2', '')");

    echo "Jobs Cleaned Successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
