<?php
require_once('../User/config.php');
header('Content-Type: application/json');

try {
    // Get current date and time
    $currentDateTime = date('Y-m-d H:i:s');
    
    // Get all equipment with their booking status
    $query = "SELECT e.name, e.status, e.last_updated, r.date_of_use, r.reservation_time,
              GROUP_CONCAT(r.reservation_time) as booked_times
              FROM equipment e 
              LEFT JOIN reservations r ON FIND_IN_SET(e.name, r.equipment) > 0 
                   AND r.date_of_use = CURDATE()
                   AND r.status = 'APPROVED'
                   
              GROUP BY e.name";
              
    $result = $conn->query($query);
    $equipment = array();
    
    while($row = $result->fetch_assoc()) {
        $status = $row['status'];
        
        // Check if equipment is booked within 3-hour window
        if ($row['booked_times']) {
            $booked_times = explode(',', $row['booked_times']);
            $current_time = strtotime('now');
            
            foreach ($booked_times as $booked_time) {
                $booking_time = strtotime($row['date_of_use'] . ' ' . $booked_time);
                $time_diff = abs($current_time - $booking_time) / 3600; // Convert to hours
                
                // If booking is within 3 hours window
                if ($time_diff <= 3 && $row['status'] != 'Maintenance') {
                    $status = 'Used';
                    break;
                }
            }
        }
        
        $equipment[] = array(
            'name' => $row['name'],
            'status' => $status,
            'last_updated' => $row['last_updated']
        );
    }
    
    echo json_encode([
        'success' => true,
        'equipment' => $equipment
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>