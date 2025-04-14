<?php
require_once('config.php');
header('Content-Type: application/json');

$selectedDate = $_POST['date'] ?? date('Y-m-d');
$selectedTime = $_POST['time'] ?? date('H:i:s');

try {
    $query = "SELECT e.name, e.status, e.last_updated,
              (
                  SELECT COUNT(*) > 0 
                  FROM reservations r 
                  WHERE FIND_IN_SET(e.name, r.equipment) > 0
                  AND r.date_of_use = ?
                  AND r.status = 'APPROVED'
                  AND (
                      ABS(
                          TIME_TO_SEC(TIMEDIFF(r.reservation_time, ?))
                      ) <= 10800
                  )
              ) as timeConflict,
              (
                  SELECT GROUP_CONCAT(r.reservation_time)
                  FROM reservations r
                  WHERE FIND_IN_SET(e.name, r.equipment) > 0
                  AND r.date_of_use = ?
                  AND r.status = 'APPROVED'
              ) as conflicting_times
              FROM equipment e";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $selectedDate, $selectedTime, $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $equipment = array();
    while($row = $result->fetch_assoc()) {
        $isConflict = false;
        if ($row['conflicting_times']) {
            $times = explode(',', $row['conflicting_times']);
            foreach ($times as $time) {
                $timeDiff = abs(strtotime($selectedTime) - strtotime($time));
                if ($timeDiff <= 10800) { // 3 hours in seconds
                    $isConflict = true;
                    break;
                }
            }
        }

        $equipment[] = array(
            'name' => $row['name'],
            'status' => $row['status'],
            'last_updated' => $row['last_updated'],
            'timeConflict' => $isConflict,
            'conflicting_times' => $row['conflicting_times']
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