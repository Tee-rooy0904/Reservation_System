<?php
header('Content-Type: application/json');
require_once('../User/config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($phone) || empty($message)) {
        throw new Exception('Phone number and message are required');
    }

    // Validate phone number format
    if (!preg_match('/^[0-9]{11}$/', $phone)) {
        throw new Exception('Invalid phone number format');
    }

    // Here you would integrate with your SMS gateway
    // For demonstration, we'll simulate success
    $success = true; // Replace with actual SMS sending logic

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'SMS sent successfully'
        ]);
    } else {
        throw new Exception('Failed to send SMS');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>