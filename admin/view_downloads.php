<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php';

$logs = $pdo->query("
    SELECT l.*, u.name as user_name 
    FROM activity_logs l 
    LEFT JOIN users u ON l.user_id = u.id 
    WHERE action IN ('print_resume', 'download_resume', 'share_resume') 
    ORDER BY l.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Downloads - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #111827; color: white; padding: 15px 0; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav .logo { color: white; text-decoration: none; font-weight: 800; }
        .admin-nav .nav-links a { color: white; text-decoration: none; margin-left: 20px; opacity: 0.8; }
        .admin-nav .nav-links a:hover, .admin-nav .nav-links a.active { opacity: 1; font-weight: 700; }
        
        table { width: 100%; border-collapse: separate; border-spacing: 0; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #eee; margin-top: 20px; }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #F9FAFB; font-weight: 600; color: #374151; }
    </style>
</head>
<body>
    <div class="admin-nav">
        <div class="container">
            <a href="dashboard.php" class="logo">Admin Panel</a>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="manage_users.php">Users</a>
                <a href="manage_jobs.php">Jobs</a>
                <a href="manage_templates.php">Templates</a>
                <a href="view_downloads.php" class="active">Downloads</a>
                <a href="../logout.php" style="color:#fca5a5">Logout</a>
            </div>
        </div>
    </div>

    <main class="container">
        <h1>Resume Activity Log</h1>
        <p>Track who is printing, downloading, and sharing resumes.</p>

        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>IP Address</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['user_name'] ?? 'Public/Guest'); ?></td>
                        <td>
                            <?php 
                                $badges = [
                                    'print_resume' => 'bg-blue-100 text-blue-800',
                                    'share_resume' => 'bg-green-100 text-green-800'
                                ];
                                $cls = $badges[$log['action']] ?? 'bg-gray-100';
                            ?>
                            <span style="padding:4px 8px; border-radius:4px; font-weight:bold; font-size:0.85rem; background:#eee">
                                <?php echo htmlspecialchars($log['action']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($log['details']); ?></td>
                        <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                        <td><?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
