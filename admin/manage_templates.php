<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php';
require_once '../includes/template_config.php'; // Load dynamic template definition

// $templates is now available from the config file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Templates - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-nav { background: #111827; color: white; padding: 15px 0; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav .logo { color: white; text-decoration: none; font-weight: 800; }
        .admin-nav .nav-links a { color: white; text-decoration: none; margin-left: 20px; opacity: 0.8; }
        .admin-nav .nav-links a:hover, .admin-nav .nav-links a.active { opacity: 1; font-weight: 700; }
        
        .template-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; margin-top: 30px; }
        .template-card { background: white; border: 1px solid #eee; border-radius: 12px; overflow: hidden; transition: transform 0.2s; }
        .template-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .template-img { height: 180px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af; overflow:hidden; }
        .template-img img { width: 100%; height: 100%; object-fit: cover; opacity: 0.8; }
        .template-info { padding: 20px; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-Pro { background: #dbeafe; color: #1e40af; }
        .badge-Free { background: #dcfce7; color: #166534; }
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
                <a href="manage_templates.php" class="active">Templates</a>
                <a href="view_downloads.php">Downloads</a>
                <a href="../logout.php" style="color:#fca5a5">Logout</a>
            </div>
        </div>
    </div>

    <main class="container">
        <h1>Resume Templates</h1>
        <p>Overview of available resume templates (Managed via <code>includes/template_config.php</code>)</p>

        <div class="template-grid">
            <?php foreach ($templates as $id => $t): ?>
                <div class="template-card">
                    <div class="template-img">
                         <!-- Placeholder or Real Image -->
                         <?php if(isset($t['image']) && $t['image']): ?>
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background: #e5e7eb;">
                                <span style="font-size:3rem;">📄</span>
                            </div>
                         <?php else: ?>
                            <span>Preview Image</span>
                         <?php endif; ?>
                    </div>
                    <div class="template-info">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h3 style="margin: 0; font-size: 1.1rem; color: #111;"><?php echo htmlspecialchars($t['name']); ?></h3>
                            <span class="badge badge-<?php echo $t['type']; ?>"><?php echo $t['type']; ?></span>
                        </div>
                        <p style="color:#666; font-size:0.85rem; margin:0 0 10px 0; min-height: 40px;">
                            <?php echo htmlspecialchars($t['description']); ?>
                        </p>
                        <div style="font-size:0.8rem; color:#999;">
                            ID: <strong><?php echo $id; ?></strong> | File: <code><?php echo $t['file']; ?></code>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
