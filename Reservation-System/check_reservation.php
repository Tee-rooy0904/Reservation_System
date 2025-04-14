<?php
header('Content-Type: application/json');
require_once('../config.php');

if (isset($_GET['user_id'])) {
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as count 
                               FROM reservations 
                               WHERE user_id_number = ? 
                               AND (status = 'pending' OR status = 'approved') 
                               AND date_of_use >= CURRENT_DATE()");
        
        $stmt->bind_param("s", $_GET['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        echo json_encode([
            'success' => true,
            'hasActiveReservation' => $row['count'] > 0
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'User ID not provided'
    ]);
}
?>