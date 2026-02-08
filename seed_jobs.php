<?php
require_once 'includes/db.php';

try {
    // Get all categories
    $categories = $pdo->query("SELECT * FROM job_categories")->fetchAll();
    
    // Check if we already have jobs to avoid massive duplicates if run twice
    $check = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
    // if ($check > 100) die("Database already seeded with enough jobs.");

    $locations = ['Remote', 'New York, NY', 'Austin, TX', 'Chicago, IL', 'San Francisco, CA', 'London, UK', 'Dubai, UAE', 'Singapore'];
    $companies = ['Global Solutions', 'Tech Innovators', 'Creative Co', 'Future Ventures', 'Prime Corp', 'Sams .io', 'Dynamic Inc'];
    $salaries = ['$40k - $60k', '$70k - $100k', '$120k+', 'Competitive', '$3,000 - $5,000 / mo'];

    $stmt = $pdo->prepare("INSERT INTO jobs (title, company, location, salary_range, category_id, requirements, description) VALUES (?,?,?,?,?,?,?)");

    $count = 0;
    foreach ($categories as $cat) {
        $cat_name = $cat['name'];
        $cat_id = $cat['id'];
        
        // Base titles for the category
        $base_title = explode('/', $cat_name)[0];
        
        for ($i = 1; $i <= 7; $i++) {
            $title = $base_title . " Specialist " . chr(64 + $i);
            if ($i == 1) $title = "Senior " . $base_title;
            if ($i == 7) $title = $base_title . " Director";
            
            $company = $companies[array_rand($companies)];
            $location = $locations[array_rand($locations)];
            $salary = $salaries[array_rand($salaries)];
            // Realistic skill generation for seed data
            $seed_skills_map = [
                'Account' => ['Excel', 'SAP', 'Finance', 'Auditing'],
                'Software' => ['Java', 'Python', 'C++', 'SQL'],
                'Web' => ['HTML', 'CSS', 'JavaScript', 'React'],
                'Market' => ['SEO', 'Content', 'Social Media', 'Ads'],
                'Manager' => ['Leadership', 'Planning', 'Agile', 'Communication']
            ];
            
            $req_list = ['Problem Solving', 'Communication']; // defaults
            foreach ($seed_skills_map as $key => $vals) {
                if (stripos($base_title, $key) !== false || stripos($title, $key) !== false) {
                    $req_list = array_merge($req_list, $vals);
                }
            }
            
            // Randomize and pick 3-5
            shuffle($req_list);
            $selected_reqs = array_slice($req_list, 0, rand(3, 5));
            $requirements = implode(', ', $selected_reqs);
            $description = "Exciting opportunity for a " . $title . " to join our team in the " . $cat_name . " industry. We are looking for candidates with strong communication skills and a passion for excellence.";
            
            $stmt->execute([$title, $company, $location, $salary, $cat_id, $requirements, $description]);
            $count++;
        }
    }

    echo "<h1>✅ Successfully seeded $count jobs across " . count($categories) . " categories!</h1>";
    echo "<p><a href='user/jobs.php'>View Jobs Page</a></p>";
    echo "<p><strong>Note:</strong> You can delete seed_jobs.php now.</p>";

} catch (PDOException $e) {
    die("Error seeding database: " . $e->getMessage());
}
?>
