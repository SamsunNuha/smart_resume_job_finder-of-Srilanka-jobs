<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';

$success_msg = '';
$error_msg = '';

// Fetch existing data first to check if resume exists
$stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$resume = $stmt->fetch();
$existing = $resume;

// Fetch user account type
$stmtUser = $pdo->prepare("SELECT account_type FROM users WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user_account = $stmtUser->fetch();
$is_pro = ($user_account && $user_account['account_type'] == 'pro');

// Check lock status
$is_locked = ($resume['is_locked'] ?? 0) == 1;

if ($is_locked && !$is_pro) {
    // If locked and not pro, prevent editing
    // We will render a locked view later in the HTML or exit here
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_resume'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $website = $_POST['website'] ?? null;
    $portfolio = $_POST['portfolio'] ?? null;
    $address = $_POST['address'] ?? null;
    $summary = $_POST['summary'];
    $skills = $_POST['skills'];
    $template_id = 1; // Default to Template 1
    
    // Bank details (Pro only)
    $bank_name = $_POST['bank_name'] ?? null;
    $branch_name = $_POST['branch_name'] ?? null;
    $acc_no = $_POST['acc_no'] ?? null;
    $acc_name = $_POST['acc_name'] ?? null;
    
    // Check template permission
    if (!$is_pro && $template_id > 2) {
        $error_msg = "Templates 3-6 are only available for Pro users. Please upgrade!";
    }
    
    // Handle photo upload
    $photo_filename = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed) && $_FILES['photo']['size'] < 2000000) { // 2MB max
            $photo_filename = uniqid('resume_photo_') . '.' . $ext;
            $upload_path = '../uploads/resumes/' . $photo_filename;
            move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path);
        } else {
            $error_msg = "Invalid photo file. Only JPG, PNG, GIF, WEBP under 2MB allowed.";
        }
    }
    
    $extra_details = json_encode($_POST['extra'] ?? []);
    
    // Process Arrays
    $education = json_encode($_POST['edu'] ?? []);
    $experience = json_encode($_POST['exp'] ?? []);
    $projects = json_encode($_POST['proj'] ?? []);
    $certifications = json_encode($_POST['cert'] ?? []);

    if (!$error_msg) {
        if ($existing) {
            // Keep old photo if no new one uploaded
            if (!$photo_filename && isset($existing['photo']) && $existing['photo']) {
                $photo_filename = $existing['photo'];
            }
            
            $stmt = $pdo->prepare("UPDATE resumes SET full_name=?, email=?, phone=?, website=?, portfolio=?, address=?, summary=?, photo=?, education=?, experience=?, skills=?, projects=?, certifications=?, template_id=?, bank_name=?, branch_name=?, acc_no=?, acc_name=?, extra_details=? WHERE user_id=?");
            $res = $stmt->execute([$full_name, $email, $phone, $website, $portfolio, $address, $summary, $photo_filename, $education, $experience, $skills, $projects, $certifications, $template_id, $bank_name, $branch_name, $acc_no, $acc_name, $extra_details, $_SESSION['user_id']]);
        } else {
            // Free user limit check
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM resumes WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $resume_count = $stmt->fetchColumn();
            
            if (!$is_pro && $resume_count >= 1) {
                $error_msg = "Free users can only create 1 resume. Upgrade to Pro for unlimited resumes!";
            } else {
            $stmt = $pdo->prepare("INSERT INTO resumes (user_id, full_name, email, phone, website, portfolio, address, summary, photo, education, experience, skills, projects, certifications, template_id, bank_name, branch_name, acc_no, acc_name, extra_details) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $res = $stmt->execute([$_SESSION['user_id'], $full_name, $email, $phone, $website, $portfolio, $address, $summary, $photo_filename, $education, $experience, $skills, $projects, $certifications, $template_id, $bank_name, $branch_name, $acc_no, $acc_name, $extra_details]);
            }
        }

        if ($res) {
            $success_msg = "Resume saved successfully!";
            // Refresh resume data to update UI
            $stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $resume = $stmt->fetch();
        } else {
            $error_msg = "Error saving resume.";
        }
    }
}

$edu = $resume ? json_decode($resume['education'], true) : [];
$exp = $resume ? json_decode($resume['experience'], true) : [];
$proj = $resume ? json_decode($resume['projects'], true) : [];
$cert = $resume ? json_decode($resume['certifications'], true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Builder - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .step-container { margin-top: 40px; }
        .step { display: none; }
        .step.active { display: block; }
        .step-header { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid var(--border-color); padding-bottom: 15px; }
        .step-item { color: #9CA3AF; font-weight: 600; cursor: pointer; }
        .step-item.active { color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 13px; margin-bottom: -17px; }
        .multi-row { background: #F3F4F6; padding: 20px; border-radius: 12px; margin-bottom: 15px; position: relative; }
        .btn-remove { position: absolute; top: 10px; right: 10px; color: var(--error-color); cursor: pointer; border: none; background: none; font-weight: bold; }
        .builder-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start; }
        .preview-pane { position: sticky; top: 100px; background: white; border: 1px solid #ddd; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 100%; height: 842px; overflow: hidden; transform: scale(0.8); transform-origin: top left; }
        
        /* Full-Width Form Styling */
        .form-group { margin-bottom: 24px; }
        .form-group label { 
            display: block; 
            font-weight: 600; 
            margin-bottom: 8px; 
            color: var(--text-color);
            font-size: 0.95rem;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: white;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1);
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .step h3 {
            color: var(--primary-color);
            margin-bottom: 24px;
            font-size: 1.5rem;
        }
        .multi-row input {
            padding: 12px 14px;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .template-option:hover .view-hd {
            opacity: 1;
        }
        .template-option {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .template-option:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .view-hd {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(13, 148, 136, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            opacity: 0;
            transition: 0.3s;
            pointer-events: none;
            z-index: 5;
        }
        .template-option:hover img {
            transform: scale(1.05);
            filter: brightness(0.8);
        }
        /* Premium PRO Badge */
        @keyframes star-glow {
            0% { box-shadow: 0 0 5px #fbbf24; border-color: #fbbf24; }
            50% { box-shadow: 0 0 15px #f59e0b, 0 0 20px rgba(245, 158, 11, 0.4); border-color: #fef3c7; }
            100% { box-shadow: 0 0 5px #fbbf24; border-color: #fbbf24; }
        }
        .pro-badge-celestial {
            background: #0f172a !important;
            color: #fbbf24 !important;
            font-size: 0.65rem !important;
            font-weight: 900 !important;
            padding: 2px 10px !important;
            border-radius: 20px !important;
            border: 1px solid #fbbf24 !important;
            letter-spacing: 1.5px !important;
            animation: star-glow 3s infinite ease-in-out;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        /* Selected Badge */
        .selected-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #0d9488;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: 2px solid white;
            display: none;
            z-index: 20;
        }
        .template-option.active .selected-badge {
            display: flex;
        }
        .template-option.active {
            border-color: var(--primary-color) !important;
            background: rgba(13, 148, 136, 0.05);
        }
        /* Fire & Star Prestige Pro */
        .template-option.locked {
            background: #020617 !important; /* Deeper Dark Background */
            border: 1px dashed #334155 !important;
            overflow: hidden;
        }
        .template-option.locked img {
            filter: grayscale(80%) brightness(0.9);
            transition: 0.5s;
        }
        .template-option.locked::after {
            content: "🔒 PRO";
            position: absolute;
            top: 10px;
            right: 10px;
            transform: none;
            color: #fbbf24;
            font-size: 0.8rem;
            font-weight: 900;
            z-index: 6;
            background: rgba(15, 23, 42, 0.9);
            padding: 4px 8px;
            border: 1px solid #fbbf24;
            border-radius: 4px;
        }
        .template-option.locked:hover img {
            filter: grayscale(0%) brightness(1);
        }
        /* Modal Styles */
        .hd-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .hd-modal img {
            max-width: 90%;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 0 50px rgba(255,255,255,0.1);
        }
        .close-modal {
            position: absolute;
            top: 30px;
            right: 40px;
            color: white;
            font-size: 40px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="container">
        <?php if (isset($is_locked) && $is_locked && !$is_pro): ?>
            <div style="text-align: center; padding: 60px 20px; max-width: 600px; margin: 50px auto; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div style="font-size: 5rem; margin-bottom: 20px;">🔒</div>
                <h1 style="color: #1e293b; margin-bottom: 15px;">Resume Locked</h1>
                <p style="color: #64748b; font-size: 1.1rem; line-height: 1.6; margin-bottom: 30px;">
                    You have already downloaded your resume. Free users can only create and download one resume.
                    <br><strong>Upgrade to Pro to unlock unlimited edits and creations.</strong>
                </p>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="pricing.php" class="btn btn-primary" style="padding: 15px 35px; font-size: 1.1rem;">🚀 Upgrade to Pro</a>
                    <a href="resume_preview.php" class="btn btn-secondary">📄 View Existing Resume</a>
                </div>
            </div>
            <?php exit(); // Stop rendering the builder ?>
        <?php endif; ?>

        <h1>Resume Builder</h1>
        
        <?php if ($error_msg): ?>
            <div class="alert alert-danger" style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
                ⚠️ <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <?php if ($success_msg): ?>
            <div class="success-wow" style="background: linear-gradient(135deg, #0d9488, #0f766e); color: white; padding: 40px; border-radius: 16px; text-align: center; margin-bottom: 40px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); animation: slideUp 0.5s ease-out;">
                <div style="font-size: 4rem; margin-bottom: 20px;">🎊</div>
                <h2 style="color: white; margin-bottom: 15px; font-weight: 800;"><?php echo $success_msg; ?></h2>
                <p style="opacity: 0.9; margin-bottom: 30px; font-size: 1.1rem;">Your professional resume is ready! Use the button below to see the final PDF.</p>
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <a href="resume_preview.php" class="btn" style="background: white; color: var(--primary-color); font-weight: 700; padding: 14px 35px; border-radius: 50px; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.2s;">
                        📄 View PDF & Download
                    </a>
                    <button onclick="this.parentElement.parentElement.remove()" class="btn" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 50px; padding: 14px 25px;">
                        Dismiss
                    </button>
                </div>
            </div>
            <style>
                @keyframes slideUp {
                    from { opacity: 0; transform: translateY(30px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        <?php endif; ?>

        <div class="step-container">
            <div class="step-header">
                <div class="step-item active" onclick="showStep(1)">1. Personal</div>
                <div class="step-item" onclick="showStep(2)">2. Education</div>
                <div class="step-item" onclick="showStep(3)">3. Experience</div>
                <div class="step-item" onclick="showStep(4)">4. Skills & Projects</div>
                <div class="step-item" onclick="showStep(5)">5. Bank Details</div>
            </div>

            <form id="resumeForm" method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="active_step" id="active_step_input" value="<?php echo $_POST['active_step'] ?? 1; ?>">
                <input type="hidden" name="template_id" value="1">
                <!-- Step 1: Personal Details -->
                <div class="step active" id="step1">
                    <h3>Personal Information</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?php echo $resume['full_name'] ?? ''; ?>" placeholder="e.g. Elara Vance" required>
                        </div>
                        <div class="form-group">
                            <label>Target Job Title</label>
                            <input type="text" name="extra[job_title]" value="<?php echo $extra['job_title'] ?? ''; ?>" placeholder="e.g. Full Stack Developer" required>
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Professional Gmail</label>
                            <input type="email" name="email" value="<?php echo $resume['email'] ?? ''; ?>" placeholder="username@gmail.com" required>
                        </div>
                        <div class="form-group">
                            <label>Current Highest Degree</label>
                            <input type="text" name="extra[highest_degree]" value="<?php echo $extra['highest_degree'] ?? ''; ?>" placeholder="e.g. B.Sc. in Computer Science">
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Phone Number (Strict Format)</label>
                            <input type="text" name="phone" id="phoneInput" value="<?php echo (isset($resume['phone']) && $resume['phone'] != '') ? $resume['phone'] : '+94'; ?>" maxlength="12" placeholder="+94 77 123 4567" oninput="formatPhone(this)">
                            <small style="color: #64748b;">Must start with +94 followed by 9 digits.</small>
                        </div>
                        <div class="form-group">
                            <label>Office/Home Location</label>
                            <select name="address" style="width: 100%; padding: 14px 16px; border: 2px solid var(--border-color); border-radius: 8px; font-size: 1rem; font-family: 'Inter', sans-serif; background: white; transition: all 0.2s; outline: none;">
                                <option value="">Select District</option>
                                <?php
                                $districts = [
                                    "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha", 
                                    "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala", 
                                    "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya", 
                                    "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
                                ];
                                foreach ($districts as $district) {
                                    $selected = ($resume['address'] ?? '') == $district ? 'selected' : '';
                                    echo "<option value=\"$district\" $selected>$district</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Website / Blog (Optional)</label>
                            <input type="text" name="website" value="<?php echo $resume['website'] ?? ''; ?>" placeholder="yoursite.com">
                        </div>
                        <div class="form-group">
                            <label>Portfolio URL (Optional)</label>
                            <input type="text" name="portfolio" value="<?php echo $resume['portfolio'] ?? ''; ?>" placeholder="behance.net/you">
                        </div>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>GitHub Account (Optional)</label>
                            <input type="text" name="extra[github]" value="<?php echo $extra['github'] ?? ''; ?>" placeholder="github.com/username">
                        </div>
                        <div class="form-group">
                            <label>LinkedIn Profile (Optional)</label>
                            <input type="text" name="extra[linkedin]" value="<?php echo $extra['linkedin'] ?? ''; ?>" placeholder="linkedin.com/in/username">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Professional Summary</label>
                        <textarea name="summary" rows="4" placeholder="e.g. Results-driven Software Developer with 5+ years of experience in full-stack development, cloud computing, and agile methodologies..."><?php echo $resume['summary'] ?? ''; ?></textarea>
                        <small style="color: #64748b;">Tip: Focus on high-impact keywords and your core strengths.</small>
                    </div>
                    
                    <!-- Photo Upload -->
                    <div class="form-group">
                        <label>📸 Professional Photo (Optional)</label>
                        <input type="file" name="photo" accept="image/*" id="photoInput" style="padding: 10px;">
                        <small style="color: #666; display: block; margin-top: 5px;">JPG, PNG, GIF, or WEBP. Max 2MB. Recommended for professional resumes.</small>
                        <?php if (isset($resume['photo']) && $resume['photo']): ?>
                            <div style="margin-top: 10px;">
                                <img src="../uploads/resumes/<?php echo $resume['photo']; ?>" alt="Current Photo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--border-color);">
                                <p style="font-size: 0.85rem; color: var(--secondary-color); margin-top: 5px;">Current photo (upload new to replace)</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="btn btn-primary" style="width: auto;" onclick="showStep(2)">Next Step →</button>
                </div>

                <!-- Step 2: Education -->
                <div class="step" id="step2">
                    <h3>Education</h3>
                    <div id="edu-container">
                        <?php if (!empty($edu)): foreach ($edu as $index => $e): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="edu[<?php echo $index; ?>][school]" value="<?php echo $e['school']; ?>" placeholder="University/School Name" style="width:100%; margin-bottom:10px">
                                <input type="text" name="edu[<?php echo $index; ?>][degree]" value="<?php echo $e['degree']; ?>" placeholder="Degree/Qualification" style="width:100%; margin-bottom:10px">
                                <div class="grid-3" style="margin-bottom:10px">
                                    <input type="text" name="edu[<?php echo $index; ?>][year]" value="<?php echo $e['year'] ?? ''; ?>" placeholder="Year (e.g. 2024)" style="width:100%;">
                                    <input type="text" name="edu[<?php echo $index; ?>][gpa]" value="<?php echo $e['gpa'] ?? ''; ?>" placeholder="GPA (e.g. 3.8/4.0)" style="width:100%;">
                                    <input type="text" name="edu[<?php echo $index; ?>][subjects]" value="<?php echo $e['subjects'] ?? ''; ?>" placeholder="Key Subjects (comma separated)" style="width:100%;">
                                </div>
                                <small style="color: #64748b; display: block; margin-top: -5px; margin-bottom: 10px;">Hint: List at least 4 key subjects related to your degree.</small>
                                <textarea name="edu[<?php echo $index; ?>][desc]" placeholder="Additional info (optional)" style="width:100%; height:60px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"><?php echo $e['desc'] ?? ''; ?></textarea>
                            </div>
                        <?php endforeach; endif; ?>
                         <button type="button" class="btn btn-secondary" onclick="addEdu()">+ Add Education</button>
                    </div>
                    <div style="margin-top:20px; display:flex; gap:10px">
                        <button type="button" class="btn btn-secondary" style="width: auto;" onclick="showStep(1)">← Back</button>
                        <button type="button" class="btn btn-primary" style="width: auto;" onclick="showStep(3)">Next Step →</button>
                    </div>
                </div>

                <!-- Step 3: Experience -->
                <div class="step" id="step3">
                    <h3>Work Experience</h3>
                    <div id="exp-container">
                        <?php if (!empty($exp)): foreach ($exp as $index => $x): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="exp[<?php echo $index; ?>][company]" value="<?php echo $x['company']; ?>" placeholder="Company (e.g. Google)" style="width:100%; margin-bottom:10px">
                                <div class="grid-2" style="margin-bottom:10px">
                                    <input type="text" name="exp[<?php echo $index; ?>][role]" value="<?php echo $x['role']; ?>" placeholder="Job Title (e.g. Senior Software Engineer)" style="width:100%;">
                                    <input type="text" name="exp[<?php echo $index; ?>][duration]" value="<?php echo $x['duration'] ?? ''; ?>" placeholder="Duration (e.g. Jan 2021 - Present)" style="width:100%;">
                                </div>
                                <textarea name="exp[<?php echo $index; ?>][desc]" placeholder="• Spearheaded the migration of legacy systems to microservices architecture.
• Developed and maintained multiple client-facing web applications..." style="width:100%; height:120px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"><?php echo $x['desc'] ?? ''; ?></textarea>
                                <small style="color: #64748b;">Tip: Use bullet points (•) for professional impact and ATS clarity.</small>
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addExp()">+ Add Experience</button>
                    </div>
                    <div style="margin-top:20px; display:flex; gap:10px">
                        <button type="button" class="btn btn-secondary" style="width: auto;" onclick="showStep(2)">← Back</button>
                        <button type="button" class="btn btn-primary" style="width: auto;" onclick="showStep(4)">Next Step →</button>
                    </div>
                </div>

                <!-- Step 4: Skills & Projects -->
                <div class="step" id="step4">
                    <h3>Skills & Other Info</h3>
                    <div class="form-group">
                        <label>Core Skills & Job Categories (comma separated)</label>
                        <input type="text" name="skills" value="<?php 
                            $raw_skills = $resume['skills'] ?? '';
                            $clean_skills = array_filter(array_map('trim', explode(',', $raw_skills)), function($s) {
                                return !empty($s) && $s != 'Skill 1' && $s != 'Skill 2';
                            });
                            echo implode(', ', $clean_skills);
                        ?>" placeholder="e.g. Full Stack Development, Frontend Engineering, PHP, JavaScript">
                        <small style="color: #64748b;">Important: Include your main job categories as skills for better search matching.</small>
                    </div>
                    <div id="proj-container">
                        <label style="display:block; margin-bottom:10px; font-weight:700">Notable Projects</label>
                        <?php if (!empty($proj)): foreach ($proj as $index => $p): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="proj[<?php echo $index; ?>][name]" value="<?php echo $p['name']; ?>" placeholder="Project Name" style="width:100%; margin-bottom:10px">
                                <textarea name="proj[<?php echo $index; ?>][description]" placeholder="Short description" style="width:100%; height:60px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"><?php echo $p['description'] ?? ''; ?></textarea>
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addProj()" style="margin-bottom: 20px;">+ Add Project</button>
                    </div>

                    <div id="cert-container">
                        <label style="display:block; margin-bottom:10px; font-weight:700">Certifications</label>
                        <?php if (!empty($cert)): foreach ($cert as $index => $c): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="cert[<?php echo $index; ?>][name]" value="<?php echo $c['name']; ?>" placeholder="Certification Name" style="width:100%; margin-bottom:10px">
                                <div class="grid-2">
                                    <input type="text" name="cert[<?php echo $index; ?>][issuer]" value="<?php echo $c['issuer'] ?? ''; ?>" placeholder="Issuer" style="width:100%; margin-bottom:10px">
                                    <input type="text" name="cert[<?php echo $index; ?>][year]" value="<?php echo $c['year'] ?? ''; ?>" placeholder="Year (e.g. 2023)" style="width:100%; margin-bottom:10px">
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addCert()" style="margin-bottom: 20px;">+ Add Certification</button>
                    </div>

                    <div id="achievement-container">
                        <label style="display:block; margin-bottom:10px; font-weight:700">Key Achievements</label>
                        <?php 
                        $achievements = $extra['achievements'] ?? [];
                        if (!empty($achievements)): foreach ($achievements as $index => $a): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="extra[achievements][<?php echo $index; ?>]" value="<?php echo htmlspecialchars($a); ?>" placeholder="e.g. Reduced server costs by 40%" style="width:100%;">
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addAchievement()" style="margin-bottom: 20px;">+ Add Achievement</button>
                    </div>

                    <div id="language-container">
                        <label style="display:block; margin-bottom:10px; font-weight:700">Languages</label>
                        <?php 
                        $langs = $extra['languages'] ?? [];
                        if (!empty($langs)): foreach ($langs as $index => $l): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <div class="grid-2">
                                    <select name="extra[languages][<?php echo $index; ?>][name]" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px">
                                        <option value="English" <?php echo (($l['name'] ?? '') == 'English') ? 'selected' : ''; ?>>English</option>
                                        <option value="Tamil" <?php echo (($l['name'] ?? '') == 'Tamil') ? 'selected' : ''; ?>>Tamil</option>
                                        <option value="Sinhala" <?php echo (($l['name'] ?? '') == 'Sinhala') ? 'selected' : ''; ?>>Sinhala</option>
                                    </select>
                                    <select name="extra[languages][<?php echo $index; ?>][level]" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px">
                                        <option value="Fluent" <?php echo (($l['level'] ?? '') == 'Fluent') ? 'selected' : ''; ?>>Fluent</option>
                                        <option value="Medium" <?php echo (($l['level'] ?? '') == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                        <option value="Poor" <?php echo (($l['level'] ?? '') == 'Poor') ? 'selected' : ''; ?>>Poor</option>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addLanguage()" style="margin-bottom: 20px;">+ Add Language</button>
                    </div>

                    <div id="academic-proj-container">
                        <label style="display:block; margin-bottom:10px; font-weight:700">Academic Projects</label>
                        <?php 
                        $acad_proj = $extra['academic_projects'] ?? [];
                        if (!empty($acad_proj)): foreach ($acad_proj as $index => $ap): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="extra[academic_projects][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($ap['title'] ?? ''); ?>" placeholder="Academic Project Title" style="width:100%; margin-bottom:10px">
                                <input type="text" name="extra[academic_projects][<?php echo $index; ?>][year]" value="<?php echo htmlspecialchars($ap['year'] ?? ''); ?>" placeholder="Year (e.g. 2024)" style="width:100%; margin-bottom:10px">
                                <textarea name="extra[academic_projects][<?php echo $index; ?>][desc]" placeholder="Project details & outcomes" style="width:100%; height:60px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"><?php echo htmlspecialchars($ap['desc'] ?? ''); ?></textarea>
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addAcademicProj()" style="margin-bottom: 20px;">+ Add Academic Project</button>
                    </div>

                    <div id="tech-skills-container">
                        <label style="display:block; margin-bottom:10px; font-weight:700">Technical Skills (Detailed)</label>
                        <?php 
                        $tech_skills = $extra['tech_skills'] ?? [];
                        if (!empty($tech_skills)): foreach ($tech_skills as $index => $ts): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="extra[tech_skills][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($ts['title'] ?? ''); ?>" placeholder="Skill Title (e.g. Tools)" style="width:100%; margin-bottom:10px">
                                <input type="text" name="extra[tech_skills][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($ts['desc'] ?? ''); ?>" placeholder="Details" style="width:100%;">
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addTechSkill()" style="margin-bottom: 20px;">+ Add Technical Skill Section</button>
                    </div>

                    <div id="abilities-container">
                        <label style="display:block; margin-bottom:10px; font-weight:700">Abilities</label>
                        <?php 
                        $abilities = $extra['abilities'] ?? [];
                        if (!empty($abilities)): foreach ($abilities as $index => $ab): ?>
                            <div class="multi-row">
                                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                                <input type="text" name="extra[abilities][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($ab['title'] ?? ''); ?>" placeholder="Ability Title" style="width:100%; margin-bottom:10px">
                                <input type="text" name="extra[abilities][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($ab['desc'] ?? ''); ?>" placeholder="Brief description" style="width:100%;">
                            </div>
                        <?php endforeach; endif; ?>
                        <button type="button" class="btn btn-secondary" onclick="addAbility()" style="margin-bottom: 20px;">+ Add Ability</button>
                    </div>
                    <div style="margin-top:20px; display:flex; gap:10px">
                        <button type="button" class="btn btn-secondary" style="width: auto;" onclick="showStep(3)">← Back</button>
                        <button type="button" class="btn btn-primary" style="width: auto;" onclick="showStep(5)">Next Step →</button>
                    </div>
                </div>

                <!-- Step 5: Dynamic Extra Details -->
                <div class="step" id="step5">
                    <h3 id="extra-title">Additional Details</h3>
                    
                    <?php if ($success_msg): ?>
                        <div class="download-ready-card" style="background: linear-gradient(135deg, #0d9488, #0f766e); color: white; padding: 30px; border-radius: 16px; text-align: center; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(13,148,136,0.2);">
                            <div style="font-size: 2.5rem; margin-bottom: 15px;">🏁 Ready to Download!</div>
                            <p style="margin-bottom: 20px; opacity: 0.9;">Your resume is saved and your professional PDF is generated.</p>
                            <div style="display: flex; gap: 15px; justify-content: center;">
                                <a href="resume_preview.php" target="_blank" class="btn" style="background: #fff; color: var(--primary-color); font-weight: 700; padding: 12px 30px; display: inline-flex; align-items: center; gap: 10px; text-decoration: none; border-radius: 50px;">
                                    👁️ View Final Resume
                                </a>
                                <a href="resume_preview.php?download=1" class="btn" style="background: rgba(255,255,255,0.2); color: white; font-weight: 700; padding: 12px 30px; display: inline-flex; align-items: center; gap: 10px; text-decoration: none; border-radius: 50px; border: 1px solid white;">
                                    📥 Download PDF
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div id="dynamic-extra-fields">
                        <!-- Fields populated via JavaScript based on template selection -->
                    </div>

                    <div style="margin-top:20px; display:flex; gap:10px">
                        <button type="button" class="btn btn-secondary" style="width: auto;" onclick="showStep(4)">← Back</button>
                        <div style="display:flex; gap:10px; width:100%">
                            <button type="submit" name="save_resume" class="btn btn-primary" style="flex:1">Save & Finish</button>
                            <?php if($resume): ?>
                                <a href="resume_preview.php" class="btn btn-secondary" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px">
                                    📄 Preview & Download PDF
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        const isPro = <?php echo $is_pro ? 'true' : 'false'; ?>;
        const existingData = <?php echo json_encode(($resume && !empty($resume['extra_details'])) ? json_decode($resume['extra_details'], true) : new stdClass()); ?>;

        function showStep(step) {
            // Instant Save on step change
            if(typeof autoSave === 'function') {
                autoSave();
            }

            document.getElementById('active_step_input').value = step;
            
            // Update step indicators
            document.querySelectorAll('.step-item').forEach((item, index) => {
                const stepNum = index + 1;
                if(stepNum === step) item.classList.add('active');
                else item.classList.remove('active');
            });

            // Update visible steps
            document.querySelectorAll('.step').forEach((s, index) => {
                const stepNum = index + 1;
                if(stepNum === step) s.classList.add('active');
                else s.classList.remove('active');
            });

            // Update fields before showing Step 5
            if(step === 5) renderExtraFields();

            // Scroll to top
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        window.addEventListener('DOMContentLoaded', () => {
            const activeStep = parseInt(document.getElementById('active_step_input').value);
            showStep(activeStep);
        });

        async function asyncChooseTemplate() {
            const btn = document.getElementById('chooseContinueBtn');
            const checkedInput = document.querySelector('input[name="template_id"]:checked');
            
            if(!checkedInput) {
                showStep(1); // Default to Step 1 if nothing selected
                return;
            }

            const templateId = checkedInput.value;
            const originalText = btn.innerText;
            
            btn.disabled = true;
            btn.innerText = "Saving Selection...";

            try {
                const response = await fetch('../api/save_template.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({template_id: templateId})
                });
                const data = await response.json();
                if(data.success) {
                    console.log("Template saved");
                }
            } catch (e) {
                console.error("Failed to save template selection");
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
                showStep(1);
            }
        }

        // Add dynamically more fields (Education, Exp, etc.) - simple version
        function selectTemplate(element, id) {
            if(element.classList.contains('locked')) return;
            
            const radio = element.querySelector('input');
            
            // If already selected, show HD Preview
            if(radio.checked) {
                const img = element.querySelector('img').src;
                document.getElementById('modal-img').src = img;
                document.getElementById('hd-modal').style.display = 'flex';
                return;
            }

            // Update UI
            document.querySelectorAll('.template-option').forEach(el => {
                el.classList.remove('active');
                el.style.borderColor = '#ddd';
                el.querySelector('input').checked = false;
            });
            
            element.classList.add('active');
            element.style.borderColor = 'var(--primary-color)';
            element.querySelector('input').checked = true;
            
            // Update extra fields if we are already on step 5
            if(document.getElementById('step5').classList.contains('active')) {
                renderExtraFields();
            }
        }

        function renderExtraFields() {
            const checkedInput = document.querySelector('input[name="template_id"]:checked');
            if(!checkedInput) return;
            const templateId = checkedInput.value;
            const container = document.getElementById('dynamic-extra-fields');
            const title = document.getElementById('extra-title');
            
            container.innerHTML = ''; // Clear existing
            
            if(templateId == 1 || templateId == 4) {
                title.innerText = "🏦 Central Bank Approved Details";
                if(!isPro) {
                    container.innerHTML = `
                        <div style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                            <h4 style="color: #334155; margin-bottom: 15px; font-size: 1rem;">🎯 Target Role & Markets</h4>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>Target Job Title</label>
                                <input type="text" name="extra[job_title]" value="${existingData.job_title || ''}" placeholder="e.g. Senior Full Stack Engineer">
                            </div>
                            <div class="grid-2">
                                <div class="form-group">
                                    <label>Primary Target Market</label>
                                    <select name="extra[market1]" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px">
                                        <option value="">Select Market / Preference</option>
                                        <optgroup label="Work Mode">
                                            <option value="Remote" ${existingData.market1 === 'Remote' ? 'selected' : ''}>🏠 Remote</option>
                                            <option value="Onsite" ${existingData.market1 === 'Onsite' ? 'selected' : ''}>🏢 Onsite</option>
                                            <option value="Hybrid" ${existingData.market1 === 'Hybrid' ? 'selected' : ''}>🔁 Hybrid</option>
                                        </optgroup>
                                        <optgroup label="Target Region">
                                            <option value="USA & North America" ${existingData.market1 === 'USA & North America' ? 'selected' : ''}>🇺🇸 USA & North America</option>
                                            <option value="United Kingdom" ${existingData.market1 === 'United Kingdom' ? 'selected' : ''}>🇬🇧 United Kingdom</option>
                                            <option value="Europe" ${existingData.market1 === 'Europe' ? 'selected' : ''}>🇪🇺 Europe</option>
                                            <option value="UAE & Middle East" ${existingData.market1 === 'UAE & Middle East' ? 'selected' : ''}>🇦🇪 UAE & Middle East</option>
                                            <option value="Australia & NZ" ${existingData.market1 === 'Australia & NZ' ? 'selected' : ''}>🇦🇺 Australia & NZ</option>
                                            <option value="Asia / Singapore" ${existingData.market1 === 'Asia / Singapore' ? 'selected' : ''}>🇸🇬 Asia / Singapore</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Secondary Target Market</label>
                                    <select name="extra[market2]" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px">
                                        <option value="">Select Market / Preference</option>
                                        <optgroup label="Work Mode">
                                            <option value="Remote" ${existingData.market2 === 'Remote' ? 'selected' : ''}>🏠 Remote</option>
                                            <option value="Onsite" ${existingData.market2 === 'Onsite' ? 'selected' : ''}>🏢 Onsite</option>
                                            <option value="Hybrid" ${existingData.market2 === 'Hybrid' ? 'selected' : ''}>🔁 Hybrid</option>
                                        </optgroup>
                                        <optgroup label="Target Region">
                                            <option value="USA & North America" ${existingData.market2 === 'USA & North America' ? 'selected' : ''}>🇺🇸 USA & North America</option>
                                            <option value="United Kingdom" ${existingData.market2 === 'United Kingdom' ? 'selected' : ''}>🇬🇧 United Kingdom</option>
                                            <option value="Europe" ${existingData.market2 === 'Europe' ? 'selected' : ''}>🇪🇺 Europe</option>
                                            <option value="UAE & Middle East" ${existingData.market2 === 'UAE & Middle East' ? 'selected' : ''}>🇦🇪 UAE & Middle East</option>
                                            <option value="Australia & NZ" ${existingData.market2 === 'Australia & NZ' ? 'selected' : ''}>🇦🇺 Australia & NZ</option>
                                            <option value="Asia / Singapore" ${existingData.market2 === 'Asia / Singapore' ? 'selected' : ''}>🇸🇬 Asia / Singapore</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div style="background: #fff; border: 2px dashed #e2e8f0; padding: 40px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 3rem; margin-bottom: 20px;">🔒</div>
                            <h4 style="color: #1e293b; margin-bottom: 10px;">Bank Details Locked</h4>
                            <p style="color: #64748b; margin-bottom: 20px;">
                                Adding verified bank details is a <strong>Pro Feature</strong>.<br>
                                Please upgrade to add your banking information.
                            </p>
                            <a href="pricing.php" class="btn btn-primary" style="display:inline-block; width:auto; padding: 12px 30px;">🚀 Upgrade to Pro</a>
                        </div>
                    `;
                    return;
                }
                container.innerHTML = `
                    <div style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
                         <h4 style="color: #334155; margin-bottom: 15px; font-size: 1rem;">🎯 Target Role & Markets</h4>
                         <div class="form-group" style="margin-bottom: 15px;">
                            <label>Target Job Title</label>
                            <input type="text" name="extra[job_title]" value="${existingData.job_title || ''}" placeholder="e.g. Senior Full Stack Engineer">
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                    <select name="extra[market1]" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px">
                                        <option value="">Select Market / Preference</option>
                                        <optgroup label="Work Mode">
                                            <option value="Remote" ${existingData.market1 === 'Remote' ? 'selected' : ''}>🏠 Remote</option>
                                            <option value="Onsite" ${existingData.market1 === 'Onsite' ? 'selected' : ''}>🏢 Onsite</option>
                                            <option value="Hybrid" ${existingData.market1 === 'Hybrid' ? 'selected' : ''}>🔁 Hybrid</option>
                                        </optgroup>
                                        <optgroup label="Target Region">
                                            <option value="USA & North America" ${existingData.market1 === 'USA & North America' ? 'selected' : ''}>🇺🇸 USA & North America</option>
                                            <option value="United Kingdom" ${existingData.market1 === 'United Kingdom' ? 'selected' : ''}>🇬🇧 United Kingdom</option>
                                            <option value="Europe" ${existingData.market1 === 'Europe' ? 'selected' : ''}>🇪🇺 Europe</option>
                                            <option value="UAE & Middle East" ${existingData.market1 === 'UAE & Middle East' ? 'selected' : ''}>🇦🇪 UAE & Middle East</option>
                                            <option value="Australia & NZ" ${existingData.market1 === 'Australia & NZ' ? 'selected' : ''}>🇦🇺 Australia & NZ</option>
                                            <option value="Asia / Singapore" ${existingData.market1 === 'Asia / Singapore' ? 'selected' : ''}>🇸🇬 Asia / Singapore</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Secondary Target Market</label>
                                    <select name="extra[market2]" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:6px">
                                        <option value="">Select Market / Preference</option>
                                        <optgroup label="Work Mode">
                                            <option value="Remote" ${existingData.market2 === 'Remote' ? 'selected' : ''}>🏠 Remote</option>
                                            <option value="Onsite" ${existingData.market2 === 'Onsite' ? 'selected' : ''}>🏢 Onsite</option>
                                            <option value="Hybrid" ${existingData.market2 === 'Hybrid' ? 'selected' : ''}>🔁 Hybrid</option>
                                        </optgroup>
                                        <optgroup label="Target Region">
                                            <option value="USA & North America" ${existingData.market2 === 'USA & North America' ? 'selected' : ''}>🇺🇸 USA & North America</option>
                                            <option value="United Kingdom" ${existingData.market2 === 'United Kingdom' ? 'selected' : ''}>🇬🇧 United Kingdom</option>
                                            <option value="Europe" ${existingData.market2 === 'Europe' ? 'selected' : ''}>🇪🇺 Europe</option>
                                            <option value="UAE & Middle East" ${existingData.market2 === 'UAE & Middle East' ? 'selected' : ''}>🇦🇪 UAE & Middle East</option>
                                            <option value="Australia & NZ" ${existingData.market2 === 'Australia & NZ' ? 'selected' : ''}>🇦🇺 Australia & NZ</option>
                                            <option value="Asia / Singapore" ${existingData.market2 === 'Asia / Singapore' ? 'selected' : ''}>🇸🇬 Asia / Singapore</option>
                                        </optgroup>
                                    </select>
                            </div>
                        </div>
                    </div>
                    <p style="color: #64748b; margin-bottom: 25px;">Enter your bank details. Accounts verified by Central Bank will receive a badge.</p>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Bank Name</label>
                            <select name="bank_name" class="form-control" style="width:100%; padding:14px; border:2px solid #e2e8f0; border-radius:8px">
                                <option value="">Select Bank</option>
                                <option value="BOC">Bank of Ceylon</option>
                                <option value="Sampath">Sampath Bank</option>
                                <option value="Commercial">Commercial Bank</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Account Number</label>
                            <input type="text" name="acc_no" placeholder="e.g. 77123456" onkeyup="verifyBank(this.value)">
                            <div id="verify-status" style="font-size:0.85rem; margin-top:5px; font-weight:600"></div>
                        </div>
                    </div>
                `;
            } else if(templateId == 2) {
                title.innerText = "📋 Career Logistics";
                container.innerHTML = `
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Work Visa Status</label>
                            <input type="text" name="extra[visa]" value="${existingData.visa || ''}" placeholder="e.g. Citizen, H1-B, etc.">
                        </div>
                        <div class="form-group">
                            <label>Notice Period</label>
                            <input type="text" name="extra[notice]" value="${existingData.notice || ''}" placeholder="e.g. Immediate, 1 Month">
                        </div>
                    </div>
                `;
            } else if(templateId == 3) {
                title.innerText = "💻 Digital Presence";
                container.innerHTML = `
                    <div class="form-group">
                        <label>GitHub Profile</label>
                        <input type="url" name="extra[github]" value="${existingData.github || ''}" placeholder="https://github.com/username">
                    </div>
                    <div class="form-group">
                        <label>Behance/Portfolio</label>
                        <input type="url" name="extra[portfolio]" value="${existingData.portfolio || ''}" placeholder="https://behance.net/username">
                    </div>
                `;
            } else if(templateId == 5) {
                title.innerText = "🌎 Language Proficiency (CEFR)";
                container.innerHTML = `
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Primary Language</label>
                            <input type="text" name="extra[lang1]" value="${existingData.lang1 || ''}" placeholder="e.g. English (C2 Proficient)">
                        </div>
                        <div class="form-group">
                            <label>Secondary Language</label>
                            <input type="text" name="extra[lang2]" value="${existingData.lang2 || ''}" placeholder="e.g. French (B2 Upper Intermediate)">
                        </div>
                    </div>
                `;
            } else if(templateId == 6) {
                title.innerText = "👑 Executive & Board Details";
                container.innerHTML = `
                    <div class="form-group">
                        <label>Current Board Memberships</label>
                        <textarea name="extra[board]" rows="3" placeholder="List any corporate boards you serve on...">${existingData.board || ''}</textarea>
                    </div>
                `;
            }
        }

        async function verifyBank(accNo) {
            const statusDiv = document.getElementById('verify-status');
            if(accNo.length < 5) {
                statusDiv.innerHTML = "";
                return;
            }
            
            statusDiv.innerHTML = "⏳ Verifying with Central Bank...";
            statusDiv.style.color = "#64748b";
            
            try {
                const response = await fetch('../api/verify_bank_status.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({acc_no: accNo})
                });
                const data = await response.json();
                
                if(data.verified) {
                    statusDiv.innerHTML = `✅ ${data.message} (${data.approval_code})`;
                    statusDiv.style.color = "#059669";
                } else {
                    statusDiv.innerHTML = `ℹ️ ${data.message}`;
                    statusDiv.style.color = "#d97706";
                }
            } catch (e) {
                statusDiv.innerHTML = "❌ Verification API currently offline";
                statusDiv.style.color = "#dc2626";
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('hd-modal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function addEdu() {
            const container = document.getElementById('edu-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="edu[${index}][school]" placeholder="University Name" class="form-control" style="width:100%; margin-bottom:10px">
                <input type="text" name="edu[${index}][degree]" placeholder="Degree" style="width:100%; margin-bottom:10px">
                <div class="grid-3" style="margin-bottom:10px">
                    <input type="text" name="edu[${index}][year]" placeholder="Year (e.g. 2024)" style="width:100%;">
                    <input type="text" name="edu[${index}][gpa]" placeholder="GPA (e.g. 3.8/4.0)" style="width:100%;">
                    <input type="text" name="edu[${index}][subjects]" placeholder="Key Subjects" style="width:100%;">
                </div>
                <textarea name="edu[${index}][desc]" placeholder="Additional info" style="width:100%; height:60px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"></textarea>
            `;
            container.insertBefore(div, container.querySelector('button[onclick="addEdu()"]'));
        }

        function addExp() {
            const container = document.getElementById('exp-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="exp[${index}][company]" placeholder="Company Name" style="width:100%; margin-bottom:10px">
                <div class="grid-2" style="margin-bottom:10px">
                    <input type="text" name="exp[${index}][role]" placeholder="Job Title" style="width:100%;">
                    <input type="text" name="exp[${index}][duration]" placeholder="Duration (e.g. 2021 - Present)" style="width:100%;">
                </div>
                <textarea name="exp[${index}][desc]" placeholder="Job description (use • for bullets)" style="width:100%; height:100px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"></textarea>
            `;
            container.insertBefore(div, container.lastElementChild);
        }

        function formatPhone(input) {
            let val = input.value;
            if (!val.startsWith('+94')) {
                val = '+94' + val.replace(/[^+0-9]/g, '').replace(/^\+94/, '');
            }
            let digits = val.substring(3).replace(/\D/g, '').substring(0, 9);
            input.value = '+94' + digits;
        }

        function addProj() {
            const container = document.getElementById('proj-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="proj[${index}][name]" placeholder="Project Name" style="width:100%; margin-bottom:10px">
                <textarea name="proj[${index}][description]" placeholder="Short description" style="width:100%; height:60px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"></textarea>
            `;
            container.insertBefore(div, container.querySelector('button[onclick="addProj()"]'));
        }

        function addCert() {
            const container = document.getElementById('cert-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="cert[${index}][name]" placeholder="Certification Name" style="width:100%; margin-bottom:10px">
                <div class="grid-2">
                    <input type="text" name="cert[${index}][issuer]" placeholder="Issuer" style="width:100%; margin-bottom:10px">
                    <input type="text" name="cert[${index}][year]" placeholder="Year" style="width:100%; margin-bottom:10px">
                </div>
            `;
            container.insertBefore(div, container.querySelector('button[onclick="addCert()"]'));
        }

        function addAchievement() {
            const container = document.getElementById('achievement-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="extra[achievements][${index}]" placeholder="Key Achievement Description" style="width:100%;">
            `;
            container.insertBefore(div, container.querySelector('button[onclick="addAchievement()"]'));
        }

        function addAcademicProj() {
            const container = document.getElementById('academic-proj-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="extra[academic_projects][${index}][title]" placeholder="Academic Project Title" style="width:100%; margin-bottom:10px">
                <input type="text" name="extra[academic_projects][${index}][year]" placeholder="Year" style="width:100%; margin-bottom:10px">
                <textarea name="extra[academic_projects][${index}][desc]" placeholder="Project details & outcomes" style="width:100%; height:60px; font-size: 0.9rem; padding: 10px; border-radius: 6px; border: 1px solid #ddd;"></textarea>
            `;
            container.insertBefore(div, container.querySelector('button[onclick="addAcademicProj()"]'));
        }

        function addTechSkill() {
            const container = document.getElementById('tech-skills-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="extra[tech_skills][${index}][title]" placeholder="Skill Title (e.g. Backend)" style="width:100%; margin-bottom:10px">
                <input type="text" name="extra[tech_skills][${index}][desc]" placeholder="Details (e.g. PHP, Node.js, MySQL)" style="width:100%;">
            `;
            container.insertBefore(div, container.querySelector('button[onclick="addTechSkill()"]'));
        }

        function addAbility() {
            const container = document.getElementById('abilities-container');
            const index = container.querySelectorAll('.multi-row').length;
            const div = document.createElement('div');
            div.className = 'multi-row';
            div.innerHTML = `
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">X</button>
                <input type="text" name="extra[abilities][${index}][title]" placeholder="Ability Title" style="width:100%; margin-bottom:10px">
                <input type="text" name="extra[abilities][${index}][desc]" placeholder="Brief description" style="width:100%;">
            `;
            container.insertBefore(div, container.querySelector('button[onclick="addAbility()"]'));
        }
    </script>
    <!-- HD Preview Modal -->
    <div id="hd-modal" class="hd-modal">
        <span class="close-modal" onclick="document.getElementById('hd-modal').style.display='none'">&times;</span>
        <img id="modal-img" src="" alt="HD Preview">
        <p style="position: absolute; bottom: 20px; color: #99f6e4; font-size: 0.9rem;">✨ Click outside images to close</p>
    </div>


    <div id="save-status" style="position: fixed; bottom: 20px; right: 20px; background: rgba(0,0,0,0.8); color: white; padding: 10px 20px; border-radius: 30px; font-size: 0.85rem; display: none; z-index: 1000; display: flex; align-items: center; gap: 8px;">
        <span class="spinner" style="width: 12px; height: 12px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 1s linear infinite; display:none;"></span>
        <span id="save-text">Saved</span>
    </div>
    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>

    <script>
        // Auto Save Logic
        let saveTimeout;
        const form = document.getElementById('resumeForm');
        const statusDiv = document.getElementById('save-status');
        const spinner = statusDiv.querySelector('.spinner');
        const statusText = document.getElementById('save-text');

        function showStatus(msg, loading = false) {
            statusDiv.style.display = 'flex';
            statusText.innerText = msg;
            spinner.style.display = loading ? 'block' : 'none';
        }

        function autoSave() {
            showStatus('Saving Changes...', true);
            const formData = new FormData(form);

            return fetch('../api/save_draft.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    showStatus('All Details Saved ✓ ' + data.timestamp);
                    setTimeout(() => {
                        if(statusText.innerText.includes('Saved ✓')) {
                            statusDiv.style.display = 'none';
                        }
                    }, 3000);
                    return data;
                } else {
                    showStatus('Save Failed', false);
                    throw new Error(data.message);
                }
            })
            .catch(err => {
                showStatus('Error Saving', false);
                console.error(err);
            });
        }

        form.addEventListener('input', () => {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(autoSave, 2000); // 2s debounce
        });

        // Instant save on tab switch or close to prevent data loss
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                autoSave();
            }
        });
        
        window.addEventListener('beforeunload', () => {
           autoSave();
        });
    </script>
</body>
</html>
