<?php
header('Content-Type: application/json');

// This is a MOCK Central Bank API endpoint for demonstration.
// It simulates a secure validation check for bank details.

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data provided']);
    exit();
}

$acc_no = $data['acc_no'] ?? '';
$bank_name = $data['bank_name'] ?? '';

// Mock logic: All accounts starting with '77' are considered "Pre-Approved"
// for this demonstration.
if (str_starts_with($acc_no, '77')) {
    echo json_encode([
        'status' => 'success',
        'verified' => true,
        'approval_code' => 'CB-' . strtoupper(uniqid()),
        'message' => 'Central Bank Approved Account'
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'verified' => false,
        'message' => 'Account pending formal bank approval'
    ]);
}
?>
