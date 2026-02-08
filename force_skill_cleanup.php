<?php
require_once 'includes/db.php';

try {
    // Fetch all resumes
    $stmt = $pdo->query("SELECT id, user_id, skills FROM resumes");
    $resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $count = 0;
    
    foreach ($resumes as $resume) {
        $raw_skills = $resume['skills'];
        if (!$raw_skills) continue;

        // Aggressive replacement
        $temp = str_ireplace(['Skill 1', 'Skill 2'], '', $raw_skills);
        
        // Reconstruction to fix commas
        $parts = explode(',', $temp);
        $clean_parts = [];
        foreach ($parts as $p) {
            $t = trim($p);
            if (!empty($t)) $clean_parts[] = $t;
        }
        $new_skills = implode(', ', $clean_parts);
        
        // Update if changed
        if ($new_skills !== $raw_skills) {
            $update = $pdo->prepare("UPDATE resumes SET skills = ? WHERE id = ?");
            $update->execute([$new_skills, $resume['id']]);
            $count++;
            echo "Fixed Resume ID {$resume['id']}: '{$raw_skills}' -> '{$new_skills}'\n";
        }
    }
    
    echo "Done. Updated $count resumes.";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
