<?php
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM resumes");
$resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$found = false;

foreach ($resumes as $r) {
    foreach ($r as $col => $val) {
        if (is_string($val) && (stripos($val, 'Skill 1') !== false || stripos($val, 'Skill 2') !== false)) {
            echo "FOUND in Resume ID {$r['id']}, Column '{$col}':\n";
            echo "Value: " . substr($val, 0, 100) . "...\n"; // truncate for readability
            
            // CLEANUP
            $clean_val = str_ireplace(['Skill 1', 'Skill 2'], '', $val);
            
            // If it's a JSON column, we might need to be careful, but pure string replace is usually safe for simple values
            // Fix double commas if it was a CSV
            if ($col == 'skills') {
               $p = explode(',', $clean_val);
               $p = array_filter(array_map('trim', $p));
               $clean_val = implode(', ', $p);
            }
            
            $update = $pdo->prepare("UPDATE resumes SET `$col` = ? WHERE id = ?");
            $update->execute([$clean_val, $r['id']]);
            echo "FIXED.\n";
            
            $found = true;
        }
    }
}

if (!$found) {
    echo "No occurrences of 'Skill 1' or 'Skill 2' found in database.";
}
?>
