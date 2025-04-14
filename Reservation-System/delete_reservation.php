<?php
header('Content-Type: application/json');
require_once '../User/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : '';
    
    if (empty($reservation_id)) {
        throw new Exception('Reservation ID is required');
    }

    $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Reservation deleted successfully'
        ]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>