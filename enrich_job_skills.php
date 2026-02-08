<?php
require_once 'includes/db.php';

// Define skill sets mapped to keywords in titles/categories
$skill_maps = [
    'Account' => 'Accounting, Excel, Financial Reporting, GAAP, SAP',
    'Financ' => 'Financial Analysis, Risk Management, Investment Strategies, Excel',
    'Audit' => 'Auditing, Compliance, Risk Assessment, Internal Controls',
    'Front' => 'React, JavaScript, CSS3, HTML5, Redux, Tailwind',
    'Back' => 'PHP, Node.js, SQL, REST APIs, Docker, AWS',
    'Full' => 'React, Node.js, SQL, MongoDB, AWS, Git',
    'Software' => 'Java, Python, C++, Data Structures, Algorithms',
    'Agri' => 'Crop Management, Sustainability, Supply Chain, Logistics',
    'Bank' => 'Customer Service, Banking Operations, Compliance, Sales',
    'Civil' => 'AutoCAD, Structural Engineering, Project Management, Site Safety',
    'Design' => 'Adobe CS, Figma, UI/UX, Typography, Branding',
    'Teach' => 'Curriculum Design, Classroom Management, EdTech, Public Speaking',
    'Manager' => 'Leadership, Strategic Planning, Team Building, Agile',
    'Market' => 'SEO, SEM, Social Media, Analytics, Content Strategy',
    'Data' => 'Python, SQL, Tableau, Machine Learning, Statistics'
];

// Fallback skills
$fallback = "Communication, Problem Solving, Microsoft Office, Teamwork";

try {
    $stmt = $pdo->query("SELECT id, title, requirements FROM jobs");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $count = 0;
    
    foreach ($jobs as $job) {
        $title = $job['title'];
        $new_skills = "";
        
        // Find matching skills
        foreach ($skill_maps as $keyword => $skills) {
            if (stripos($title, $keyword) !== false) {
                // Determine random subset for variety
                $skill_arr = explode(', ', $skills);
                shuffle($skill_arr);
                $subset = array_slice($skill_arr, 0, 3); // Pick 3 random
                $new_skills .= implode(', ', $subset) . ", ";
            }
        }
        
        // If no specific match, or just to add variety
        if (empty($new_skills)) {
             $new_skills = "Project Management, Communication, " . $title . " Knowledge";
        } else {
            // Append some soft skills
            $new_skills .= "Communication, Problem Solving";
        }
        
        // Update DB
        $update = $pdo->prepare("UPDATE jobs SET requirements = ? WHERE id = ?");
        $update->execute([$new_skills, $job['id']]);
        $count++;
    }
    
    echo "<h1>✨ Successfully enriched $count jobs with realistic skills!</h1>";
    echo "<p>Example updates:</p><ul>";
    // Show a few examples
    $stmt = $pdo->query("SELECT title, requirements FROM jobs LIMIT 5");
    while($row = $stmt->fetch()) {
        echo "<li><strong>" . htmlspecialchars($row['title']) . ":</strong> " . htmlspecialchars($row['requirements']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
