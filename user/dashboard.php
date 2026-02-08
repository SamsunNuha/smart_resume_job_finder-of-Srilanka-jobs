<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Fetch User Profile & Account Status
$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user_profile = $stmtUser->fetch();

// Default if not found (safety)
if (!$user_profile) {
    $user_profile = ['account_type' => 'free'];
}

// Fetch Resume & Score
$stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$resume_data = $stmt->fetch();
$resume_score = getResumeScore($resume_data);

// Skill Gap Suggestions
$skill_gaps = [];
if ($resume_data) {
    $user_skills = array_map('trim', explode(',', strtolower($resume_data['skills'])));
    // Get a job that matches partially
    $stmt = $pdo->query("SELECT requirements FROM jobs LIMIT 1");
    $job_reqs_text = $stmt->fetchColumn();
    if ($job_reqs_text) {
        $job_reqs = array_map('trim', explode(',', strtolower($job_reqs_text)));
        $skill_gaps = array_diff($job_reqs, $user_skills);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css?v=4.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        a { text-decoration: none; }
        .plan-overview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .plan-box {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            color: var(--text-color);
        }
        .plan-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .plan-box.active-plan {
            border: 2px solid var(--primary-color);
            background: rgba(13, 148, 136, 0.1);
        }
        .plan-box.free-plan { border-left: 6px solid #94A3B8; }
        .plan-box.pro-plan { border-top: 6px solid var(--primary-color); }
        .plan-box.yearly-plan { border-left: 6px solid #F59E0B; }
        
        .plan-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary-color);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .active-badge { background: #10B981 !important; }

        /* Skill Gap UI Enhancements */
        .gap-container {
            background: #fff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #eef2f6;
        }
        .gap-title {
            color: #0d9488;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .gap-subtitle {
            font-size: 1.1rem;
            color: #1a1a1a;
            margin-bottom: 20px;
        }
        .gap-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .gap-item {
            background: #fff7ed;
            border-left: 5px solid #f97316;
            padding: 15px 20px;
            border-radius: 12px;
            color: #431407;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        .gap-item:hover { transform: translateX(5px); }
        .gap-item strong { color: #9a3412; }
        .gap-course {
            margin-top: 20px;
            font-style: italic;
            color: #4a5568;
            font-size: 0.95rem;
            border-top: 1px dashed #e2e8f0;
            padding-top: 15px;
        }

        @media (max-width: 992px) {
            .plan-overview { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <section class="welcome-banner" style="margin-bottom: 20px;">
            <div class="banner-content">
                <h1>Hello, <?php echo $_SESSION['user_name']; ?>! 👋</h1>
                <p>Welcome to your professional dashboard. Building your career starts here.</p>
            </div>
        </section>

        <!-- 3-Box Plan Overview -->
        <div class="plan-overview">
            <!-- Free Plan -->
            <div class="plan-box free-plan <?php echo ($user_profile['account_type'] == 'free') ? 'active-plan' : ''; ?>">
                <?php if ($user_profile['account_type'] == 'free'): ?>
                    <span class="plan-badge active-badge">ACTIVE PLAN</span>
                <?php endif; ?>
                <div>
                    <h3 style="margin-top: 0; color: var(--text-color);">Free Account</h3>
                    <ul style="list-style: none; padding: 0; margin: 15px 0; font-size: 0.9rem; color: var(--text-muted);">
                        <li style="margin-bottom: 8px;">✨ 1 Professional Resume</li>
                        <li style="margin-bottom: 8px;">✨ Ultimate Professional Template</li>
                        <li style="margin-bottom: 8px;">✨ Standard Job Finder</li>
                    </ul>
                </div>
                <div style="font-weight: 700; font-size: 1.5rem; color: var(--text-color);">$0 <span style="font-size: 0.85rem; font-weight: 400; color: var(--text-muted);">/lifetime</span></div>
            </div>

            <!-- Pro Monthly -->
            <div class="plan-box pro-plan <?php echo ($user_profile['account_type'] == 'pro') ? 'active-plan' : ''; ?>">
                <?php if ($user_profile['account_type'] == 'pro'): ?>
                    <span class="plan-badge active-badge">ACTIVE PLAN</span>
                <?php else: ?>
                    <span class="plan-badge" style="background: var(--primary-color);">MOST POPULAR</span>
                <?php endif; ?>
                <div>
                    <h3 style="color: var(--primary-color); margin-top: 0;">Pro Monthly</h3>
                    <ul style="list-style: none; padding: 0; margin: 15px 0; font-size: 0.9rem; color: var(--secondary-color);">
                        <li style="margin-bottom: 8px;">🚀 Unlimited Resumes</li>
                        <li style="margin-bottom: 8px;">🚀 Profile Photo Support</li>
                        <li style="margin-bottom: 8px;">🚀 Bank Details Enabled</li>
                        <li style="margin-bottom: 8px;">🚀 Skill Gap Analysis Plus</li>
                    </ul>
                </div>
                <div style="font-weight: 700; font-size: 1.5rem; color: var(--primary-color);">$5 <span style="font-size: 0.85rem; font-weight: 400; opacity: 0.7; color: var(--text-muted);">/month</span></div>
                <?php if ($user_profile['account_type'] == 'free'): ?>
                    <a href="pricing.php" class="btn btn-primary" style="margin-top: 15px; width: 100%; border-radius: 10px; padding: 10px;">Upgrade Now</a>
                <?php endif; ?>
            </div>

            <!-- Pro Yearly -->
            <div class="plan-box yearly-plan">
                <span class="plan-badge" style="background: #F59E0B;">SAVE 75%</span>
                <div>
                    <h3 style="color: #F59E0B; margin-top: 0;">Pro Yearly</h3>
                    <ul style="list-style: none; padding: 0; margin: 15px 0; font-size: 0.9rem; color: #FBBF24;">
                        <li style="margin-bottom: 8px;">💎 Everything in Pro</li>
                        <li style="margin-bottom: 8px;">💎 Priority PDF Rendering</li>
                        <li style="margin-bottom: 8px;">💎 Early Feature Access</li>
                    </ul>
                </div>
                <div style="font-weight: 700; font-size: 1.5rem; color: #F59E0B;">$15 <span style="font-size: 0.85rem; font-weight: 400; color: var(--text-muted);">/year</span></div>
                <?php if ($user_profile['account_type'] == 'free'): ?>
                    <a href="pricing.php" class="btn btn-secondary" style="margin-top: 15px; width: 100%; border-radius: 10px; padding: 10px; border-color: #F59E0B; color: #B45309;">Go Yearly</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card action-card">
                <h3>Resume Insight</h3>
                <?php if ($resume_data): ?>
                    <div class="status-ok">
                        <div class="score-circle">
                            <span class="score-val"><?php echo $resume_score; ?></span>
                            <span class="score-label">Score</span>
                        </div>
                        <p>✅ Your resume strength is <strong><?php echo $resume_score; ?>/100</strong></p>
                        <?php if ($resume_score < 100): ?>
                            <p style="font-size: 0.85rem; color: #666; margin-top: 5px;">Tip: Add more skills or experience to reach 100!</p>
                        <?php endif; ?>
                        <div class="card-actions">
                            <a href="resume_builder.php" class="btn btn-secondary">Edit Resume</a>
                            <a href="resume_preview.php?download=1" class="btn btn-primary">Download PDF</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="status-none">
                        <p>❌ You haven't created a resume yet.</p>
                        <a href="resume_builder.php" class="btn btn-primary" style="margin-top: 10px;">Create Now</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="dashboard-card info-card gap-container">
                <h3 class="gap-title">Skill Gap Analysis</h3>
                <p class="gap-subtitle">Based on top industry jobs:</p>
                <?php if (!empty($skill_gaps)): ?>
                    <div class="gap-list">
                        <?php 
                        $counter = 0;
                        foreach ($skill_gaps as $gap): 
                            if ($counter >= 3) break;
                        ?>
                            <div class="gap-item">
                                💡 Missing: <strong><?php echo htmlspecialchars(ucwords($gap)); ?></strong>
                            </div>
                        <?php 
                            $counter++;
                        endforeach; 
                        ?>
                    </div>
                    <?php 
                    $first_gap = current($skill_gaps);
                    ?>
                    <div class="gap-course">
                        Suggested Course: <strong>Learn <?php echo htmlspecialchars(ucwords($first_gap)); ?> on Coursera</strong>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 20px;">
                        <span style="font-size: 3rem;">🎯</span>
                        <p style="margin-top: 15px; font-weight: 600; color: #0d9488;">Your skills are perfectly aligned with current industry trends!</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="dashboard-card action-card">
                <h3>Recommended Jobs</h3>
                <p>Selected matches for you</p>
                
                <?php
                // Fetch recommended jobs logic
                $stmt = $pdo->query("SELECT * FROM jobs"); // In production, limit this
                $all_jobs = $stmt->fetchAll();
                
                $rec_jobs = [];
                if ($resume_data) {
                    $user_skills = array_map('trim', explode(',', strtolower($resume_data['skills'])));
                    foreach ($all_jobs as $job) {
                        $reqs = array_map('trim', explode(',', strtolower($job['requirements'])));
                        $matches = array_intersect($user_skills, $reqs);
                        $score = (count($matches) / max(count(array_unique($reqs)), 1)) * 100;
                        if ($score >= 40) { // Only good matches
                            $job['match_score'] = round($score);
                            $rec_jobs[] = $job;
                        }
                    }
                    // Sort by score
                    usort($rec_jobs, function($a, $b) {
                        return $b['match_score'] <=> $a['match_score'];
                    });
                    $rec_jobs = array_slice($rec_jobs, 0, 3); // Top 3
                }
                ?>

                <?php if (!empty($rec_jobs)): ?>
                    <div style="display: flex; flex-direction: column; gap: 10px; margin: 15px 0;">
                        <?php foreach ($rec_jobs as $job): ?>
                            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px; border-radius: 8px;">
                                <div style="font-weight: 700; color: #166534; font-size: 0.95rem;"><?php echo htmlspecialchars($job['title']); ?></div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #14532d; margin-top: 4px;">
                                    <span><?php echo htmlspecialchars($job['company']); ?></span>
                                    <span style="font-weight: 800;"><?php echo $job['match_score']; ?>% Match</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="background: #f3f4f6; color: #666; padding: 10px; border-radius: 8px; margin: 15px 0; font-weight: 500; text-align: center;">
                        Add more skills to see recommendations!
                    </div>
                <?php endif; ?>

                <a href="jobs.php?smart_filter=1" class="btn btn-primary" style="display: block; text-align: center;">See All Matches →</a>
            </div>
        </div>
    </main>


</body>
</html>
