<?php
// user/resume_preview.php

session_start();

// Allow public access for Shared Links or Demo Mode
$is_public_access = (isset($_GET['token']) && isset($_GET['uid'])) || (isset($_GET['demo']) && $_GET['demo'] == 'true');

if (!$is_public_access && !isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db.php';
require_once '../includes/functions.php';

$target_user_id = $_SESSION['user_id'] ?? null;
$view_mode = 'private'; // 'private' or 'public'

// 0. Demo Mode (Dummy Data)
if (isset($_GET['demo']) && $_GET['demo'] == 'true') {
    $view_mode = 'public';
    $target_user_id = 0;
    $resume = [
        'id' => 0,
        'user_id' => 0,
        'full_name' => 'Samsun Nuha',
        'email' => 'samsun.nuha@example.com',
        'phone' => '+94 753360265',
        'address' => 'Colombo, Sri Lanka',
        'summary' => 'Innovative Software Engineer with 5+ years of experience in full-stack development. Proven track record of leading teams and delivering high-quality web applications.',
        'education' => json_encode([
            [
                'school' => 'University of Moratuwa', 
                'degree' => 'B.Sc. in Computer Science', 
                'gpa' => '3.8',
                'subjects' => 'Data Structures, Algorithms, AI, Database Systems',
                'desc' => 'Graduated with First Class Honours. Specialized in Artificial Intelligence.'
            ]
        ]),
        'experience' => json_encode([
            ['company' => 'TechCorp Solutions', 'role' => 'Senior Developer', 'desc' => '• Led a team of 5 developers to build a SaaS platform.\n• Optimized database queries, reducing load time by 40%.'],
            ['company' => 'WebStudio', 'role' => 'Frontend Developer', 'desc' => '• Responsible for implementing responsive layouts using Bootstrap framework.\n• Developed responsive UI components using React.\n• Collaborated with UX designers to improve user journey.']
        ]),
        'projects' => json_encode([
            ['name' => 'E-Commerce Platform', 'description' => 'A fully functional online store with payment gateway integration.'],
            ['name' => 'Task Management App', 'description' => 'Real-time task tracker using WebSocket technology.']
        ]),
        'certifications' => json_encode([
            ['name' => 'AWS Certified Solutions Architect', 'issuer' => 'Amazon Web Services', 'year' => '2023'],
            ['name' => 'Google Cloud Associate Engineer', 'issuer' => 'Google Cloud', 'year' => '2022']
        ]),
        'template_id' => $_GET['template_id'] ?? 1,
        'skills' => 'PHP, JavaScript, React, Bootstrap, MySQL, Docker, AWS',
        'extra_details' => json_encode([
            'github' => 'samsunnuha',
            'linkedin' => 'samsunnuha',
            'job_title' => 'Senior Full Stack Engineer',
            'highest_degree' => 'B.Sc. in Computer Science',
            'languages' => [
                ['name' => 'English', 'level' => 'Professional'],
                ['name' => 'Sinhala', 'level' => 'Native']
            ],
            'achievements' => [
                'Employee of the Year 2023 at TechCorp',
                'Won 1st Place in National Hackathon 2022'
            ],
            'academic_projects' => [
                ['title' => 'AI Chatbot for Healthcare', 'desc' => 'Developed a NLP-based chatbot to assist patients with basic medical queries.', 'year' => '2023'],
                ['title' => 'Blockchain Voting System', 'desc' => 'Implemented a secure, decentralized voting platform using Ethereum.', 'year' => '2022']
            ],
            'tech_skills' => [
                ['title' => 'Backend Development', 'desc' => 'Expertise in PHP (Laravel), Node.js, and Python (Django).'],
                ['title' => 'Cloud Infrastructure', 'desc' => 'Managed AWS and Azure environments for scalable applications.']
            ],
            'abilities' => [
                ['title' => 'Technical Leadership', 'desc' => 'Proven ability to mentor junior developers and lead project architecture.'],
                ['title' => 'Rapid Prototyping', 'desc' => 'Fast turnaround for MVP development and UI/UX iterations.']
            ]
        ]),
        'bank_name' => 'Commercial Bank',
        'branch_name' => 'Colombo 7',
        'acc_no' => '8004561230',
        'acc_name' => 'S. Nuha',
        'photo' => 'https://ui-avatars.com/api/?name=Samsun+Nuha&background=0D8ABC&color=fff&size=512'
    ];
    $is_pro = true; // Demo always shows Pro features
}
// 1. Check for Public Access via Token
elseif (isset($_GET['token']) && isset($_GET['uid'])) {
    $uid = $_GET['uid'];
    $token = $_GET['token'];
    $secret_salt = "LankaResumeySecret";
    $expected_token = md5($uid . $secret_salt);
    
    if ($token === $expected_token) {
        $target_user_id = $uid;
        $view_mode = 'public';
        // Fetch resume below
    } else {
        die("Invalid or expired share link.");
    }
} 
// 2. Admin Access
elseif (isset($_SESSION['admin_id']) && isset($_GET['user_id'])) {
    $target_user_id = $_GET['user_id'];
}
// 3. Unauthorized
elseif (!$target_user_id) {
    header("Location: ../login.php");
    exit();
}

// Fetch resume if not in demo mode
if (!isset($resume)) {
    $stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
    $stmt->execute([$target_user_id]);
    $resume = $stmt->fetch();

    if (!$resume) {
        die("Resume not found. Please build it first.");
    }
    
    // Check Pro Status for real users
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT account_type FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_account = $stmt->fetch();
        $is_pro = ($user_account['account_type'] == 'pro');
    } else {
        // Public view of real resume - assume restricted or check owner's status?
        // Ideally we check the OWNER's status, not the viewer's.
        // For now, let's fetch owner status to be correct.
        $stmt = $pdo->prepare("SELECT account_type FROM users WHERE id = ?");
        $stmt->execute([$target_user_id]);
        $owner_account = $stmt->fetch();
        $is_pro = ($owner_account['account_type'] == 'pro');
    }
}

$extra = $resume['extra_details'] ? json_decode($resume['extra_details'], true) : [];
$edu = $resume['education'] ? json_decode($resume['education'], true) : [];
$exp = $resume['experience'] ? json_decode($resume['experience'], true) : [];
$proj = $resume['projects'] ? json_decode($resume['projects'], true) : [];
$cert = $resume['certifications'] ? json_decode($resume['certifications'], true) : [];

require_once '../includes/template_config.php';

// Determine active template (Override via GET for demo)
$curr_template_id = $_GET['template_id'] ?? ($resume['template_id'] ?? 1);
// Ensure we fall back to 1 if ID is invalid
$template_config = getTemplate($curr_template_id);
if (!$template_config) $template_config = getTemplate(1);

// Pro Check
$is_pro_template = ($template_config['type'] === 'Pro');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Preview - <?php echo SITE_NAME; ?></title>
    <!-- Common Styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&family=Roboto+Mono&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        .preview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            background: #f3f4f6;
            min-height: 100vh;
        }

        /* Base A4 Page Wrapper - Templates should inherit/use this */
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden; /* Ensure content doesn't spill out visually */
            color: #333;
            box-sizing: border-box; 
            /* Default Font */
            font-family: 'Inter', sans-serif;
        }

        /* ATS Score Badge */
        .ats-score {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Action Bar */
        .action-bar {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            padding: 12px 30px;
            border-radius: 50px;
            display: flex;
            gap: 25px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            z-index: 1000;
        }
        
        .action-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.2s;
            opacity: 0.9;
        }
        
        .action-btn:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        /* Modal Styles (Reuse existing) */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .preview-container { padding: 0; display: block; }
            .action-bar, .ats-score, .modal, .template-badge, .header-container { display: none !important; }
            .a4-page { margin: 0; box-shadow: none; page-break-after: always; width: 100%; }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="preview-container">
        <!-- ATS Score -->
        <div class="ats-score">
            <span>ATS Score:</span>
            <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px;"><?php echo getResumeScore($resume); ?>%</span>
        </div>

        <?php if ($is_pro_template && !$is_pro): ?>
            <div class="template-badge" style="background: #fffbeb; border: 1px solid #f59e0b; padding: 15px 25px; border-radius: 12px; margin-bottom: 25px; text-align: center; max-width: 600px;">
                <h3 style="color: #b45309; margin: 0 0 5px 0;">💎 Pro Template Preview</h3>
                <p style="color: #92400e; margin: 0; font-size: 0.9rem;">Upgrade to Pro to download resumes using the <strong><?php echo $template_config['name']; ?></strong> template.</p>
                <div style="margin-top: 10px;">
                    <a href="pricing.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9rem;">Upgrade Now</a>
                    <a href="resume_builder.php" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.9rem; margin-left:10px;">Change Template</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Dynamic Template Inclusion -->
        <?php
        $template_file = __DIR__ . '/templates/' . $template_config['file'];
        if (file_exists($template_file)) {
            include $template_file;
        } else {
            // Fallback to Template 1 if file missing
            echo "<div style='color:red; background:white; padding:20px; border-radius:8px; margin-bottom:20px;'>Error: Template file '{$template_config['file']}' not found. Loading fallback.</div>";
            if(file_exists(__DIR__ . '/templates/classic_professional.php')) {
                include __DIR__ . '/templates/classic_professional.php';
            }
        }
        ?>

        <!-- Floating Action Bar -->
        <div class="action-bar">
            <button class="action-btn" onclick="downloadPDF()" style="background: var(--secondary-color); color: white; padding: 10px 20px; border-radius: 50px;">
                Download PDF 📥
            </button>
            <button class="action-btn" onclick="window.print()">
                Print 🖨️
            </button>
            <button class="action-btn" onclick="copyShareLink()">
                Share 🔗
            </button>
            <button class="action-btn" onclick="openEmailModal()">
                Email 📧
            </button>
        </div>
    </div>

    <!-- Email Modal -->
    <div id="emailModal" class="modal">
        <div class="modal-content">
            <h3>Email Details</h3>
            <input type="email" id="recipientEmail" placeholder="Recruiter's Email" class="form-control" style="width:100%; padding: 10px; margin: 15px 0; border:1px solid #ddd; border-radius:8px;">
            <div style="display:flex; gap:10px; justify-content:center;">
                <button onclick="sendEmail()" class="btn btn-primary">Send Now</button>
                <button onclick="closeEmailModal()" class="btn btn-secondary" style="background:#eee; color:#333;">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function downloadPDF() {
            const element = document.querySelector('.a4-page');
            const opt = {
                margin: 0,
                filename: '<?php echo str_replace(" ", "_", $resume["full_name"]); ?>_Resume.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, logging: false },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            // Show loading status if needed
            const btn = document.querySelector('button[onclick="downloadPDF()"]');
            const originalText = btn ? btn.innerHTML : '';
            if(btn) btn.innerHTML = "Generating... ⏳";

            html2pdf().set(opt).from(element).save().then(() => {
                if(btn) btn.innerHTML = originalText;
            });
        }

        function copyShareLink() {
            const url = window.location.href.split('?')[0] + "?uid=<?php echo $resume['user_id']; ?>&token=<?php echo md5($resume['user_id'] . "LankaResumeySecret"); ?>";
            navigator.clipboard.writeText(url).then(() => alert("Public link copied to clipboard!"));
        }
        
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('download') === '1') {
                // Short delay to ensure fonts/images are loaded
                setTimeout(downloadPDF, 1500);
            }
        });

        function openEmailModal() { document.getElementById('emailModal').style.display = 'flex'; }
        function closeEmailModal() { document.getElementById('emailModal').style.display = 'none'; }
        
        function sendEmail() {
            const email = document.getElementById('recipientEmail').value;
            if(!email) return alert("Please enter an email");
            
            // Call backend
            const formData = new FormData();
            formData.append('email', email);
            formData.append('resume_id', <?php echo $resume['id'] ?? 0; ?>);
            
            // Placeholder for email sending logic since file might not exist yet
            alert("Email feature would send to: " + email);
            closeEmailModal();
        }
    </script>
</body>
</html>
