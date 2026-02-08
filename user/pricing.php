<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';
require_once '../includes/db.php';

// Fetch current user status
$stmt = $pdo->prepare("SELECT account_type FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css?v=4.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .pricing-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 50px;
        }
        .pricing-card {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
        }
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(13, 148, 136, 0.2);
            border-color: var(--secondary-color);
        }
        .pricing-card.popular {
            border-color: var(--primary-color);
            transform: scale(1.05);
            z-index: 1;
        }
        .pricing-card.popular:hover {
            transform: scale(1.05) translateY(-10px);
        }
        .popular-badge {
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
        }
        .plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        .plan-price {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 30px;
        }
        .plan-price span {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 400;
        }
        .features-list {
            list-style: none;
            padding: 0;
            margin-bottom: 40px;
            text-align: left;
        }
        .features-list li {
            margin-bottom: 15px;
            color: var(--text-color);
        }
        .features-list li:before {
            content: "✓";
            color: var(--primary-color);
            font-weight: bold;
            display: inline-block;
            width: 25px;
        }
        .btn-plan {
            display: block;
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
        }
        .btn-free {
            background: var(--accent-color);
            color: var(--primary-color);
        }
        .btn-pro {
            background: var(--primary-color);
            color: white;
        }
        .btn-pro:hover {
            background: var(--secondary-color);
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.4);
        }
        .current-plan-badge {
            background: #10B981;
            color: white;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <div style="text-align: center; margin-top: 40px;">
            <h1>Flexible Pricing for Every Career</h1>
            <p>Upgrade to Pro for more templates and professional features.</p>
        </div>

        <div class="pricing-container">
            <!-- Free Plan -->
            <div class="pricing-card">
                <div class="plan-name">Free</div>
                <div class="plan-price">$0<span>/lifetime</span></div>
                <ul class="features-list">
                    <li>1 Resume per email</li>
                    <li>2 Professional Templates</li>
                    <li>ATS-Friendly Export</li>
                    <li>Find Matching Jobs</li>
                </ul>
                <?php if ($user['account_type'] == 'free'): ?>
                    <div class="current-plan-badge">Active Plan</div>
                <?php else: ?>
                    <a href="#" class="btn-plan btn-free">Current Free User</a>
                <?php endif; ?>
            </div>

            <!-- Pro Monthly -->
            <div class="pricing-card popular">
                <div class="popular-badge">MOST POPULAR</div>
                <div class="plan-name">Pro Monthly</div>
                <div class="plan-price">$5<span>/month</span></div>
                <ul class="features-list">
                    <li>Unlimited Resumes</li>
                    <li>6 Premium Templates</li>
                    <li>Real Bank Details (SL Only)</li>
                    <li>Priority PDF Generation</li>
                    <li>Profile Picture Support</li>
                </ul>
                <a href="checkout.php?plan=monthly" class="btn-plan btn-pro"><?php echo $user['account_type'] == 'pro' ? 'Renew Plan' : 'Upgrade to Pro'; ?></a>
            </div>

            <!-- Pro Yearly -->
            <div class="pricing-card">
                <div class="plan-name">Pro Yearly</div>
                <div class="plan-price">$15<span>/year</span></div>
                <ul class="features-list">
                    <li>Everything in Monthly</li>
                    <li>Save 75% Yearly</li>
                    <li>Exclusive Future Updates</li>
                    <li>Premium Career Support</li>
                </ul>
                <a href="checkout.php?plan=yearly" class="btn-plan btn-pro"><?php echo $user['account_type'] == 'pro' ? 'Switch to Yearly' : 'Upgrade to Pro'; ?></a>
            </div>
        </div>
    </main>


</body>
</html>
