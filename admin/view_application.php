<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("
    SELECT a.*, u.name as user_name, j.title as job_title, r.full_name as resume_name, r.id as res_id, r.photo
    FROM applications a 
    JOIN users u ON a.user_id = u.id 
    JOIN jobs j ON a.job_id = j.id
    JOIN resumes r ON a.resume_id = r.id
    WHERE a.id = ?
");
$stmt->execute([$id]);
$app = $stmt->fetch();

if (!$app) die("Application not found.");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    $app['status'] = $status;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application - <?php echo $app['user_name']; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css?v=4.0">
    <style>
        .admin-nav { background: #111827; color: white; padding: 15px 0; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav .logo { color: white; text-decoration: none; font-weight: 800; font-size: 1.4rem; }
        
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--primary-color); font-weight: 600; text-decoration: none; margin-bottom: 20px; font-size: 0.9rem; }
        .back-link:hover { text-decoration: underline; }
        
        .dashboard-card { background: white; padding: 30px; border-radius: 12px; border: 1px solid #eee; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .dashboard-card h3 { margin-bottom: 20px; color: #111827; border-bottom: 2px solid var(--accent-color); display: inline-block; padding-bottom: 5px; }
        .dashboard-card p { margin-bottom: 12px; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="admin-nav" style="margin-bottom: 50px;">
        <div class="container">
            <a href="dashboard.php" class="logo">Admin: <?php echo SITE_NAME; ?></a>
            <div class="nav-links">
                <a href="dashboard.php" style="color:white; font-weight: 700; text-decoration:none; padding: 8px 16px;">Stats</a>
                <a href="manage_jobs.php" style="color:white; opacity: 0.7; text-decoration:none; padding: 8px 16px;">Jobs</a>
                <a href="../logout.php" style="color:#FCA5A5; text-decoration:none; padding: 8px 16px;">Logout</a>
            </div>
        </div>
    </div>

    <main class="container">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
        <h1>Application Details</h1>

        <div class="dashboard-grid" style="margin-top: 20px;">
            <div class="dashboard-card">
                <h3>Candidate Info</h3>
                <?php if ($app['photo']): ?>
                    <img src="../uploads/resumes/<?php echo $app['photo']; ?>" alt="Candidate Photo" style="width: 120px; height: 120px; border-radius: 12px; object-fit: cover; margin-bottom: 20px; border: 2px solid var(--border-color);">
                <?php endif; ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($app['user_name']); ?></p>
                <p><strong>Job:</strong> <?php echo htmlspecialchars($app['job_title']); ?></p>
                <p><strong>Applied on:</strong> <?php echo date('M d, Y', strtotime($app['applied_at'])); ?></p>
                <p style="margin-top: 15px;">
                    <a href="../user/resume_preview.php?user_id=<?php echo $app['user_id']; ?>" target="_blank" class="btn btn-secondary" style="width: auto; padding: 8px 15px; font-size: 0.85rem;">📄 View Full Resume</a>
                </p>
                
                <form method="POST" style="margin-top: 20px;">
                    <label><strong>Status:</strong></label>
                    <select name="status" class="form-control" style="width: 100%; margin: 10px 0; padding: 10px; border-radius: 8px;">
                        <option value="Applied" <?php echo $app['status'] == 'Applied' ? 'selected' : ''; ?>>Applied</option>
                        <option value="Viewed" <?php echo $app['status'] == 'Viewed' ? 'selected' : ''; ?>>Viewed</option>
                        <option value="Shortlisted" <?php echo $app['status'] == 'Shortlisted' ? 'selected' : ''; ?>>Shortlisted</option>
                        <option value="Rejected" <?php echo $app['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                    <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                </form>
            </div>

            <div class="dashboard-card" style="margin-top: 20px; grid-column: 1 / -1;">
                <h3>Specific Application Details</h3>
                <div style="background: #fdf2f8; padding: 20px; border-radius: 8px; border: 1px solid #fbcfe8; margin-bottom: 20px;">
                    <?php 
                    $responses = json_decode($app['form_responses'] ?? '[]', true);
                    if (empty($responses)): ?>
                        <p style="color:#9d174d; margin:0">No specific questions were asked for this job.</p>
                    <?php else: ?>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <?php foreach ($responses as $q => $a): ?>
                                <div>
                                    <div style="font-weight:700; color:#9d174d; margin-bottom:5px"><?php echo htmlspecialchars($q); ?></div>
                                    <div style="font-size:1.1rem; color:#111827"><?php echo htmlspecialchars($a); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h3>AI Generated Cover Letter</h3>
                <div style="background: #f9fafb; padding: 20px; border-radius: 8px; font-style: italic; white-space: pre-line; border: 1px solid #e5e7eb;">
                    <?php echo htmlspecialchars($app['cover_letter']); ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
