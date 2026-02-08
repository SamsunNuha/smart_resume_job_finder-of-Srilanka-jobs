<?php
require_once 'includes/db.php';

try {
    // 1. Remove 'Skill 1, Skill 2' if it exists exactly like that
    $pdo->query("UPDATE resumes SET skills = REPLACE(skills, 'Skill 1, Skill 2', '')");
    
    // 2. Remove 'Skill 1'
    $pdo->query("UPDATE resumes SET skills = REPLACE(skills, 'Skill 1', '')");
    
    // 3. Remove 'Skill 2'
    $pdo->query("UPDATE resumes SET skills = REPLACE(skills, 'Skill 2', '')");
    
    // 4. Clean up mess (comma combos)
    $pdo->query("UPDATE resumes SET skills = REPLACE(skills, ', ,', ',')");
    // Trim leading/trailing commas not easily doable in pure SQL safely without regex, 
    // but the builder UI filters empty values on display/save anyway.
    
    echo "Cleanup successful: Removed 'Skill 1' and 'Skill 2' from database records.";
} catch (PDOException $e) {
    echo "Cleanup failed: " . $e->getMessage();
}
?>
