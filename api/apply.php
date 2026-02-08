<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $user_id = $_SESSION['user_id'];

    // Get user and job details for personalized cover letter
    $stmt = $pdo->prepare("
        SELECT u.name as user_name, j.title as job_title, j.company as company_name 
        FROM users u, jobs j 
        WHERE u.id = ? AND j.id = ?
    ");
    $stmt->execute([$user_id, $job_id]);
    $details = $stmt->fetch();

    // Get current resume ID
    $stmt = $pdo->prepare("SELECT id FROM resumes WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $resume = $stmt->fetch();

    if (!$resume) {
        echo json_encode(['status' => 'error', 'message' => 'Please create a resume first.']);
        exit();
    }

    // Check if already applied
    $stmt = $pdo->prepare("SELECT id FROM applications WHERE job_id = ? AND user_id = ?");
    $stmt->execute([$job_id, $user_id]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'You have already applied for this job.']);
        exit();
    }

    // Generate AI-simulated cover letter
    $user_name = $details['user_name'];
    $company = $details['company_name'];
    $job_title = $details['job_title'];

    $cover_letter = "Dear Hiring Manager at $company,\n\n";
    $cover_letter .= "I am writing to express my strong interest in the $job_title position at $company. ";
    $cover_letter .= "With my background and skills detailed in my attached resume, I am confident that I can contribute effectively to your team.\n\n";
    $cover_letter .= "I look forward to the possibility of discussing how my experience aligns with the needs of $company.\n\n";
    $cover_letter .= "Sincerely,\n$user_name";

    // Apply
    $form_responses = json_encode($_POST['responses'] ?? []);
    
    $stmt = $pdo->prepare("INSERT INTO applications (job_id, user_id, resume_id, cover_letter, form_responses) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$job_id, $user_id, $resume['id'], $cover_letter, $form_responses])) {
        // Add persistent notification
        $notif_msg = "succesfuly appllied your job using lankaResumey for " . $details['job_title'];
        $stmt_notif = $pdo->prepare("INSERT INTO user_notifications (user_id, message) VALUES (?, ?)");
        $stmt_notif->execute([$user_id, $notif_msg]);

        echo json_encode(['status' => 'success', 'message' => $notif_msg]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
}
?>
