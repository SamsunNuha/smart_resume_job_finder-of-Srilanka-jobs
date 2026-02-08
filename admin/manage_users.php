<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php';

// Handle Actions
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    $msg = "User deleted successfully.";
}

if (isset($_GET['promote'])) {
    $id = $_GET['promote'];
    $pdo->prepare("UPDATE users SET account_type = 'pro' WHERE id = ?")->execute([$id]);
    $msg = "User promoted to Pro.";
}

if (isset($_GET['demote'])) {
    $id = $_GET['demote'];
    $pdo->prepare("UPDATE users SET account_type = 'free' WHERE id = ?")->execute([$id]);
    $msg = "User demoted to Free.";
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - <?php echo SITE_NAME; ?></title>
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
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        .badge-pro { background: #dbeafe; color: #1e40af; }
        .badge-free { background: #f3f4f6; color: #374151; }

        .btn-sm { padding: 5px 10px; font-size: 0.8rem; border-radius: 5px; text-decoration: none; margin-right: 5px; }
        .btn-danger { background: #fee2e2; color: #b91c1c; }
        .btn-success { background: #dcfce7; color: #15803d; }
    </style>
</head>
<body>
    <div class="admin-nav">
        <div class="container">
            <a href="dashboard.php" class="logo">Admin Panel</a>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="manage_users.php" class="active">Users</a>
                <a href="manage_jobs.php">Jobs</a>
                <a href="manage_templates.php">Templates</a>
                <a href="view_downloads.php">Downloads</a>
                <a href="../logout.php" style="color:#fca5a5">Logout</a>
            </div>
        </div>
    </div>

    <main class="container">
        <h1>Manage Users</h1>
        <?php if (isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $u['account_type']; ?>">
                                <?php echo ucfirst($u['account_type']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                        <td>
                            <?php if ($u['account_type'] == 'free'): ?>
                                <a href="?promote=<?php echo $u['id']; ?>" class="btn-sm btn-success">Make Pro</a>
                            <?php else: ?>
                                <a href="?demote=<?php echo $u['id']; ?>" class="btn-sm btn-danger">Make Free</a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $u['id']; ?>" class="btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
