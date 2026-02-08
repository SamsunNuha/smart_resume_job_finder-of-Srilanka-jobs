<?php
require_once 'includes/db.php';

try {
    // Add portfolio column
    $pdo->exec("ALTER TABLE resumes ADD COLUMN portfolio VARCHAR(255) AFTER website");
    echo "Added portfolio column.\n";
} catch (Exception $e) {
    echo "Portfolio column already exists or error: " . $e->getMessage() . "\n";
}

try {
    // Add updated_at column
    $pdo->exec("ALTER TABLE resumes ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    echo "Added updated_at column.\n";
} catch (Exception $e) {
    echo "Updated_at column already exists or error: " . $e->getMessage() . "\n";
}

echo "Database fix complete.";
?>
