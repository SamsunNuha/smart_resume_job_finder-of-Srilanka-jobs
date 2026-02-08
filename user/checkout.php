<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';
require_once '../includes/db.php';

$plan = $_GET['plan'] ?? 'monthly';
$price = ($plan == 'yearly') ? 15 : 5;
$plan_name = ucfirst($plan) . " Pro Plan";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_payment'])) {
    // In a real app, you would verify the bank slip/transaction ID here
    $duration = ($plan == 'yearly') ? '+1 year' : '+1 month';
    $new_expiry = date('Y-m-d H:i:s', strtotime($duration));
    
    $stmt = $pdo->prepare("UPDATE users SET account_type = 'pro', subscription_end = ? WHERE id = ?");
    if ($stmt->execute([$new_expiry, $_SESSION['user_id']])) {
        $_SESSION['success_msg'] = "Success! Payment received. You are now a PRO member.";
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css?v=4.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .checkout-container { max-width: 1000px; margin: 40px auto; }
        .checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; }
        .payment-method-card { background: white; border: 1px solid var(--border-color); border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .payment-tabs { display: flex; border-bottom: 1px solid #eee; background: #f9fafb; }
        .tab-btn { flex: 1; padding: 20px; text-align: center; cursor: pointer; font-weight: 700; color: #64748b; transition: 0.3s; border: none; background: none; }
        .tab-btn.active { background: white; color: var(--primary-color); border-bottom: 3px solid var(--primary-color); }
        .tab-content { padding: 40px; display: none; }
        .tab-content.active { display: block; }
        
        .bank-details { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; border-radius: 12px; margin-bottom: 25px; }
        .bank-item { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #bbf7d0; padding-bottom: 10px; }
        .bank-item:last-child { border: none; }
        .bank-label { color: #166534; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; }
        .bank-value { color: #064e3b; font-weight: 700; font-family: monospace; font-size: 1.1rem; }
        
        .security-badge { display: flex; align-items: center; gap: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 12px; margin-top: 30px; font-size: 0.85rem; color: #64748b; }
        .summary-card { background: white; border: 1px solid var(--border-color); border-radius: 20px; padding: 30px; height: fit-content; text-align: left; }
        
        .gateway-instruction { text-align: center; padding: 20px 0; }
        .btn-large { width: 100%; padding: 18px; font-size: 1.1rem; border-radius: 14px; margin-top: 20px; cursor: pointer; font-weight: 700; transition: 0.3s; border: none; display: block; text-align: center; }
        
        #card-fields-removed-notice { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; font-weight: 500; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container checkout-container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
            <h1>Upgrade Your Career</h1>
            <div style="color: #64748b; font-weight: 600; font-size: 0.9rem;">
                Step 2 of 2: <span style="color: var(--primary-color);">Secure Payment</span>
            </div>
        </div>

        <div id="card-fields-removed-notice">
            🛡️ <strong>Security Update:</strong> For your safety, we no longer collect card numbers directly. Payments are now processed via secured bank redirects or direct transfers.
        </div>

        <div class="checkout-grid">
            <div class="payment-method-card">
                <div class="payment-tabs">
                    <button class="tab-btn active" onclick="switchTab(this, 'bank')">🏦 Bank Transfer</button>
                    <button class="tab-btn" onclick="switchTab(this, 'card')">💳 Secure Card Gateway</button>
                </div>

                <!-- Bank Tab -->
                <div class="tab-content active" id="bank">
                    <h3>Direct Bank Deposit/Transfer</h3>
                    <p style="color: #64748b; margin-bottom: 25px;">Please transfer the amount to our official bank account and click confirm. Our team will verify it within 1 hour.</p>
                    
                    <div class="bank-details">
                        <div class="bank-item">
                            <span class="bank-label">Bank Name</span>
                            <span class="bank-value">Bank of Ceylon (BOC)</span>
                        </div>
                        <div class="bank-item">
                            <span class="bank-label">Account Name</span>
                            <span class="bank-value">Smart Resume PVT LTD</span>
                        </div>
                        <div class="bank-item">
                            <span class="bank-label">Account Number</span>
                            <span class="bank-value">887-2234-990</span>
                        </div>
                        <div class="bank-item">
                            <span class="bank-label">Branch</span>
                            <span class="bank-value">Colombo Fort</span>
                        </div>
                    </div>

                    <form method="POST">
                        <div class="form-group" style="margin-top: 20px;">
                            <label>Transaction Reference / Name used in Transfer</label>
                            <input type="text" placeholder="e.g. TRX-998271" style="width: 100%; padding: 14px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                        </div>
                        <button type="submit" name="confirm_payment" class="btn-large btn-primary">I've Sent the Money - Confirm Upgrade</button>
                        <p style="text-align: center; font-size: 0.8rem; color: #94a3b8; margin-top: 15px;">By clicking, you confirm that you have made the transfer.</p>
                    </form>
                </div>

                <!-- Card Tab -->
                <div class="tab-content" id="card">
                    <div class="gateway-instruction">
                        <div style="font-size: 4rem; margin-bottom: 20px;">🔒</div>
                        <h3>Redirecting to Secure Gateway</h3>
                        <p style="color: #64748b; margin-top: 10px;">You will be redirected to our PCI-DSS compliant partner (PayHere/Stripe) to complete the payment using your Visa, Mastercard, or AMEX.</p>
                        
                        <div style="display: flex; gap: 15px; justify-content: center; margin: 30px 0;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="20" alt="Visa">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="25" alt="Mastercard">
                        </div>

                        <form method="POST">
                            <button type="submit" name="confirm_payment" class="btn-large" style="background: #1e293b; color: white;">Pay with Secure Gateway ($<?php echo $price; ?>)</button>
                        </form>
                    </div>
                </div>

                <div class="security-badge">
                    <span style="font-size: 1.2rem;">🔒</span>
                    <span><strong>SSL Encrypted Connection:</strong> Your payment choice is protected by 256-bit encryption for maximum security.</span>
                </div>
            </div>

            <div class="summary-card">
                <h3>Order Summary</h3>
                <div style="margin-top: 25px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: #64748b;">Plan:</span>
                        <span style="font-weight: 700;"><?php echo $plan_name; ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: #64748b;">Duration:</span>
                        <span><?php echo ($plan == 'yearly') ? '12 Months' : '1 Month'; ?></span>
                    </div>
                    <div style="border-top: 1px solid #eee; margin: 20px 0; padding-top: 20px; display: flex; justify-content: space-between;">
                        <span style="font-weight: 800; font-size: 1.1rem;">Total Amount</span>
                        <span style="font-weight: 800; font-size: 1.25rem; color: var(--primary-color);">$<?php echo $price; ?>.00</span>
                    </div>
                </div>
                
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-top: 20px;">
                    <h4 style="font-size: 0.9rem; margin-bottom: 10px; color: #1e293b;">Why upgrade to Pro?</h4>
                    <ul style="font-size: 0.85rem; color: #64748b; padding-left: 15px;">
                        <li>Unlock 6+ Premium Templates</li>
                        <li>Unlimited Resume Generation</li>
                        <li>Central Bank Verified Badge support</li>
                        <li>AI-Powered Job Recommendations</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script>
        function switchTab(btn, tabId) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            btn.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
