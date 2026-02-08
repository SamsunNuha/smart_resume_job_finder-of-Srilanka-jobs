<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';

$stmt = $pdo->prepare("
    SELECT a.*, j.title as job_title, j.company 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    WHERE a.user_id = ? 
    ORDER BY a.applied_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$applications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css?v=4.0">
    <style>
        .app-list { background: white; border-radius: 12px; border: 1px solid #eee; overflow: hidden; }
        .app-item { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .app-item:last-child { border-bottom: none; }
        .app-info h3 { margin-bottom: 5px; color: var(--primary-color); }
        .app-date { color: #666; font-size: 0.85rem; }
        .status-badge { padding: 6px 15px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; }
        .status-Applied { background: #E0F2FE; color: #0369A1; }
        .status-Viewed { background: var(--accent-color); color: var(--primary-color); }
        .status-Shortlisted { background: #D1FAE5; color: #059669; }
        .status-Rejected { background: #FEE2E2; color: #B91C1C; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <h1>Track Your Applications</h1>
        <p>Keep an eye on your career progress</p>

        <div class="app-list">
            <?php if (empty($applications)): ?>
                <div style="padding: 40px; text-align: center; color: #666;">
                    You haven't applied for any jobs yet. <br>
                    <a href="jobs.php" class="btn btn-primary" style="margin-top:20px; width:auto">Browse Jobs</a>
                </div>
            <?php else: ?>
                <?php foreach ($applications as $app): ?>
                    <div class="app-item">
                        <div class="app-info">
                            <h3><?php echo htmlspecialchars($app['job_title']); ?></h3>
                            <p><?php echo htmlspecialchars($app['company']); ?></p>
                            <span class="app-date">Applied on: <?php echo date('M d, Y', strtotime($app['applied_at'])); ?></span>
                        </div>
                        <div class="status-badge status-<?php echo $app['status']; ?>">
                            <?php echo $app['status']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>


</body>
</html>
