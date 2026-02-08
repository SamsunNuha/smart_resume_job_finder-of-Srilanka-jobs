<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php';

// Stats
// Ensure activity_logs table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(50) NOT NULL,
    details VARCHAR(255),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$job_count = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
$app_count = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();

// Recent Applications
$recent_apps = $pdo->query("
    SELECT a.*, u.name as user_name, j.title as job_title 
    FROM applications a 
    JOIN users u ON a.user_id = u.id 
    JOIN jobs j ON a.job_id = j.id 
    ORDER BY a.applied_at DESC LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #111827; color: white; padding: 15px 0; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav .logo { color: white; }
        .admin-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 30px; border-radius: 12px; border: 1px solid #eee; text-align: center; }
        .stat-card h2 { font-size: 3rem; color: var(--primary-color); }
        
        table { width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #eee; }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #F9FAFB; font-weight: 600; color: #374151; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
        tr:last-child td { border-bottom: none; }
        
        .btn-view { background: var(--accent-color); color: var(--primary-color); padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; }
        .btn-view:hover { background: var(--primary-color); color: white; }

        .admin-nav .nav-links a { text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 0.9rem; }
        .admin-nav .nav-links a:hover { background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="admin-nav">
        <div class="container">
            <a href="dashboard.php" class="logo" style="text-decoration:none; color:white; font-weight:800; font-size:1.4rem">Admin: <?php echo SITE_NAME; ?></a>
            <div class="nav-links">
                <a href="dashboard.php" style="color:white; font-weight: 700;">Dashboard</a>
                <a href="manage_users.php" style="color:white; opacity: 0.7;">Users</a>
                <a href="manage_jobs.php" style="color:white; opacity: 0.7;">Jobs</a>
                <a href="manage_templates.php" style="color:white; opacity: 0.7;">Templates</a>
                <a href="view_downloads.php" style="color:white; opacity: 0.7;">Downloads</a>
                <a href="../logout.php" style="color:#FCA5A5">Logout</a>
            </div>
        </div>
    </div>

    <main class="container">
        <h1>Overview</h1>
        
        <div class="admin-stats">
            <div class="stat-card">
                <h2><?php echo $user_count; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $job_count; ?></h2>
                <p>Jobs Posted</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $app_count; ?></h2>
                <p>Applications</p>
            </div>
            <!-- New Stat -->
            <div class="stat-card">
                <h2><?php 
                    // Count total print/download actions
                    $dl_count = $pdo->query("SELECT COUNT(*) FROM activity_logs WHERE action IN ('print_resume', 'download_resume')")->fetchColumn();
                    echo $dl_count; 
                ?></h2>
                <p>Resumes Downloaded</p>
            </div>
        </div>

        <h2>Recent Applications</h2>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Job Title</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_apps as $app): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($app['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                        <td><span class="status-badge status-<?php echo $app['status']; ?>"><?php echo $app['status']; ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                        <td><a href="view_application.php?id=<?php echo $app['id']; ?>" class="btn-view">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
