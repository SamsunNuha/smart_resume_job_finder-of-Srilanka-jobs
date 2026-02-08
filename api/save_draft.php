<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = $_POST;

// Extract standard fields
$full_name = $data['full_name'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$website = $data['website'] ?? '';
$portfolio = $data['portfolio'] ?? '';
$address = $data['address'] ?? '';
$summary = $data['summary'] ?? '';
$education = json_encode($data['edu'] ?? []);
$experience = json_encode($data['exp'] ?? []);
$skills = $data['skills'] ?? '';
$projects = json_encode($data['proj'] ?? []);
$certifications = json_encode($data['cert'] ?? []);
$template_id = $data['template_id'] ?? 1;
$bank_name = $data['bank_name'] ?? '';
$branch_name = $data['branch_name'] ?? '';
$acc_no = $data['acc_no'] ?? '';
$acc_name = $data['acc_name'] ?? '';
$extra_details = json_encode($data['extra'] ?? []);

try {
    // Check if resume exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM resumes WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $sql = "UPDATE resumes SET full_name=?, email=?, phone=?, website=?, portfolio=?, address=?, summary=?, education=?, experience=?, skills=?, projects=?, certifications=?, template_id=?, bank_name=?, branch_name=?, acc_no=?, acc_name=?, extra_details=?, updated_at=NOW() WHERE user_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$full_name, $email, $phone, $website, $portfolio, $address, $summary, $education, $experience, $skills, $projects, $certifications, $template_id, $bank_name, $branch_name, $acc_no, $acc_name, $extra_details, $user_id]);
    } else {
        // Allow draft creation even without full name to prevent data loss
        if (true) { 
            $sql = "INSERT INTO resumes (user_id, full_name, email, phone, website, portfolio, address, summary, education, experience, skills, projects, certifications, template_id, bank_name, branch_name, acc_no, acc_name, extra_details) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $full_name, $email, $phone, $website, $portfolio, $address, $summary, $education, $experience, $skills, $projects, $certifications, $template_id, $bank_name, $branch_name, $acc_no, $acc_name, $extra_details]);
        }
    }
    echo json_encode(['status' => 'success', 'timestamp' => date('H:i:s')]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
