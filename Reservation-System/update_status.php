<?php
header('Content-Type: application/json');
require_once('config.php');

try {
    // Get POST data
    $user_id_number = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    
    if (!$user_id_number || !$status) {
        throw new Exception('Missing required parameters');
    }

    // Validate status values
    $allowed_statuses = ['pending', 'approved', 'rejected'];
    if (!in_array(strtolower($status), $allowed_statuses)) {
        throw new Exception('Invalid status value');
    }

    // Check if reservation exists
    $check_stmt = $conn->prepare("SELECT id FROM reservations WHERE user_id_number = ? ORDER BY id DESC LIMIT 1");
    $check_stmt->bind_param("s", $user_id_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Reservation not found');
    }

    // Update reservation status
    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE user_id_number = ? AND id = (SELECT id FROM (SELECT id FROM reservations WHERE user_id_number = ? ORDER BY id DESC LIMIT 1) as temp)");
    $stmt->bind_param("sss", $status, $user_id_number, $user_id_number);
    
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