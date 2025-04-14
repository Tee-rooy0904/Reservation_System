<?php
header('Content-Type: application/json');
require_once('../config.php');

try {
    // Get all reservations with user details
    $sql = "SELECT r.*, u.fullName, u.role, u.sex, u.phone 
            FROM reservations r 
            LEFT JOIN users u ON r.user_id_number = u.user_id_number 
            ORDER BY r.booking_timestamp DESC";
    
    $result = $conn->query($sql);
    $reservations = [];
    
    while($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
    
    // Get statistics
    $stats = [
        'total' => $conn->query("SELECT COUNT(*) as count FROM reservations")->fetch_assoc()['count'],
        'pending' => $conn->query("SELECT COUNT(*) as count FROM reservations WHERE status = 'pending'")->fetch_assoc()['count'],
        'approved' => $conn->query("SELECT COUNT(*) as count FROM reservations WHERE status = 'approved'")->fetch_assoc()['count']
    ];
    
    echo json_encode([
        'success' => true,
        'reservations' => $reservations,
        'stats' => $stats
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>