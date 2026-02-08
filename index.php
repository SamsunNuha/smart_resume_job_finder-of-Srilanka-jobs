<?php
session_start();
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Your Career Starts Here</title>
    <link rel="stylesheet" href="assets/css/style.css?v=4.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .hero {
            padding: 100px 0;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 20px; }
        .hero p { font-size: 1.25rem; opacity: 0.9; margin-bottom: 40px; max-width: 700px; margin-inline: auto; }
        .features { padding: 80px 0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .feature-card { background: white; padding: 40px; border-radius: 20px; text-align: center; border: 1px solid #eee; }
        .feature-card h3 { margin: 20px 0 10px; color: var(--primary-color); }
        .cta-btns { display: flex; gap: 20px; justify-content: center; }
        .cta-btns .btn { width: auto; padding: 15px 40px; border-radius: 50px; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo"><?php echo SITE_NAME; ?></a>
                <div class="nav-links">
                    <a href="#features">Features</a>
                    <a href="#about">How it Works</a>
                </div>
                <div class="nav-auth">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="user/dashboard.php" class="btn btn-primary">My Dashboard</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                        <a href="register.php" class="btn btn-primary">Get Started</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Build Your Future in Minutes</h1>
            <p>The smartest way to build a professional resume and find jobs that actually match your skills.</p>
            <div class="cta-btns">
                <a href="register.php" class="btn btn-primary" style="background:white; color:var(--primary-color)">Start Building Now</a>
                <a href="login.php" class="btn btn-secondary" style="border:2px solid white; background:transparent; color:white">Find Jobs</a>
            </div>
        </div>
    </section>

    <section id="features" class="container" style="padding: 80px 0">
        <div class="features">
            <div class="feature-card">
                <div class="icon">📄</div>
                <h3>Smart Resume Builder</h3>
                <p>Multi-step form with live preview and professionally designed A4 templates.</p>
            </div>
            <div class="feature-card">
                <div class="icon">🔍</div>
                <h3>Intelligent Matching</h3>
                <p>Our algorithm scans your resume and matches you with the best job opportunities.</p>
            </div>
            <div class="feature-card">
                <div class="icon">⚡</div>
                <h3>One-Click Apply</h3>
                <p>Apply to multiple jobs instantly using your generated resume.</p>
            </div>
        </div>
    </section>

    <footer style="background:var(--card-bg); padding:40px 0; border-top:1px solid var(--border-color); text-align:center">
        <div class="container">
            <p>&copy; 2024 <?php echo SITE_NAME; ?>. All rights reserved.</p>
            <div style="margin-top:10px">
                <a href="admin/login.php" style="color:#9CA3AF; font-size:0.8rem">Admin Login</a>
            </div>
        </div>
    </footer>
</body>
</html>
