<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->execute([$id]);
$job = $stmt->fetch();

if (!$job) die("Job not found.");

$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_job'])) {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $category_id = $_POST['category_id'];
    $reqs = $_POST['requirements'];
    $desc = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE jobs SET title=?, company=?, location=?, salary_range=?, category_id=?, requirements=?, description=? WHERE id=?");
    if ($stmt->execute([$title, $company, $location, $salary, $category_id, $reqs, $desc, $id])) {
        $success = "Job updated successfully!";
    }
}

$categories = $pdo->query("SELECT * FROM job_categories ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - <?php echo htmlspecialchars($job['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css?v=4.0">
    <style>
        .admin-nav { background: #111827; color: white; padding: 15px 0; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav .logo { color: white; transition: opacity 0.2s; }
        .admin-nav .logo:hover { opacity: 0.8; }
        
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--primary-color); font-weight: 600; text-decoration: none; margin-bottom: 20px; font-size: 0.9rem; }
        .back-link:hover { text-decoration: underline; }
        
        .dashboard-card { background: white; padding: 30px; border-radius: 12px; border: 1px solid #eee; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 0.9rem; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 1rem; transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px var(--accent-color); }
    </style>
</head>
<body>
    <div class="admin-nav" style="margin-bottom: 50px;">
        <div class="container">
            <a href="dashboard.php" class="logo" style="text-decoration:none; font-weight:800; font-size:1.4rem">Admin: <?php echo SITE_NAME; ?></a>
            <div class="nav-links">
                <a href="dashboard.php" style="color:white; opacity: 0.7; text-decoration:none; padding: 8px 16px;">Stats</a>
                <a href="manage_jobs.php" style="color:white; font-weight: 700; text-decoration:none; padding: 8px 16px;">Jobs</a>
                <a href="../logout.php" style="color:#FCA5A5; text-decoration:none; padding: 8px 16px;">Logout</a>
            </div>
        </div>
    </div>

    <main class="container">
        <a href="manage_jobs.php" class="back-link">← Back to Manage Jobs</a>
        <h1>Edit Job Posting</h1>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="dashboard-card" style="max-width: 600px; margin: 20px auto;">
            <form method="POST">
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
                </div>
                <!-- ... other fields ... -->
                <div class="form-group">
                    <label>Company</label>
                    <input type="text" name="company" value="<?php echo htmlspecialchars($job['company']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Salary Range</label>
                    <input type="text" name="salary" value="<?php echo htmlspecialchars($job['salary_range']); ?>">
                </div>
                <div class="form-group">
                    <label>Job Category</label>
                    <select name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $job['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Requirements (Skills)</label>
                    <input type="text" name="requirements" value="<?php echo htmlspecialchars($job['requirements']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4"><?php echo htmlspecialchars($job['description']); ?></textarea>
                </div>
                <button type="submit" name="update_job" class="btn btn-primary">Update Job</button>
            </form>
        </div>
    </main>
</body>
</html>
