<?php
header('Content-Type: application/json');
require_once('../config.php');

try {
    $stmt = $conn->prepare("
        SELECT date_of_use as date, reservation_time as time, equipment, status 
        FROM reservations 
        WHERE (status = 'pending' OR status = 'approved')
        AND date_of_use >= CURRENT_DATE()
    ");
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reservations = [];
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'reservations' => $reservations
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>