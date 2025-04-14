<?php
header('Content-Type: application/json');
require_once('../User/config.php');

try {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $status = $_POST['status'] ?? null;
    
    if (!$reservation_id || !$status) {
        throw new Exception('Missing required parameters');
    }

    $allowed_statuses = ['pending', 'approved', 'rejected'];
    if (!in_array(strtolower($status), $allowed_statuses)) {
        throw new Exception('Invalid status value');
    }

    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $reservation_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $status
        ]);
    } else {
        throw new Exception('Failed to update status');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>