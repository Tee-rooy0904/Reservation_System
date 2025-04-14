<?php
header('Content-Type: application/json');
require_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $requiredFields = ['user_id_number', 'fullName', 'role', 'sex', 'phone', 'date_of_use', 'reservation_time', 'event_type', 'venue'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: " . $field);
            }
        }

        // Validate date and time
        $today = new DateTime();
        $minDate = new DateTime();
        $minDate->modify('+3 days');

        $dateOfUse = new DateTime($_POST['date_of_use']);
        $timeOfUse = $_POST['reservation_time'];

        // Check if date is at least 3 days in advance
        if ($dateOfUse < $minDate) {
            throw new Exception("Booking must be made at least 3 days in advance");
        }

        // Handle equipment array
        if (empty($_POST['equipment'])) {
            throw new Exception("Please select at least one equipment");
        }
        $equipment = implode(',', (array)$_POST['equipment']);
        
        // First, check if user exists and update if necessary
        $stmt = $conn->prepare("INSERT INTO users (user_id_number, fullName, role, sex, phone) 
                              VALUES (?, ?, ?, ?, ?) 
                              ON DUPLICATE KEY UPDATE 
                              fullName = VALUES(fullName), 
                              role = VALUES(role), 
                              sex = VALUES(sex), 
                              phone = VALUES(phone)");
        
        $stmt->bind_param("sssss", 
            $_POST['user_id_number'],
            $_POST['fullName'],
            $_POST['role'],
            $_POST['sex'],
            $_POST['phone']
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating user information: " . $stmt->error);
        }

        // Check for existing pending or approved reservation
        $check_stmt = $conn->prepare("SELECT id FROM reservations WHERE user_id_number = ? AND (status = 'pending' OR status = 'approved') AND date_of_use >= CURRENT_DATE()");
        $check_stmt->bind_param("s", $_POST['user_id_number']);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'You already have an active reservation'
            ]);
            exit;
        }

        // Update the SQL query to match database columns
        // Check for equipment availability at the same date and time
        $check_equipment_stmt = $conn->prepare("
            SELECT equipment 
            FROM reservations 
            WHERE date_of_use = ? 
            AND reservation_time = ? 
            AND (status = 'pending' OR status = 'approved')");
            
        $check_equipment_stmt->bind_param("ss", 
            $_POST['date_of_use'],
            $_POST['reservation_time']
        );
        
        $check_equipment_stmt->execute();
        $equipment_result = $check_equipment_stmt->get_result();
        
        // Check if any requested equipment is already booked
        $requested_equipment = (array)$_POST['equipment'];
        $booked_equipment = [];
        
        while ($row = $equipment_result->fetch_assoc()) {
            $booked_items = explode(',', $row['equipment']);
            $booked_equipment = array_merge($booked_equipment, $booked_items);
        }
        
        $conflict = array_intersect($requested_equipment, $booked_equipment);
        
        if (!empty($conflict)) {
            echo json_encode([
                'success' => false,
                'message' => 'Some equipment is already booked for this time slot: ' . implode(', ', $conflict)
            ]);
            exit;
        }

        // Continue with the reservation insert
        $insertReservation = $conn->prepare("INSERT INTO reservations (user_id_number, date_of_use, reservation_time, event_type, venue, equipment, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        
        if (!$insertReservation) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        $insertReservation->bind_param("ssssss", 
            $_POST['user_id_number'],
            $_POST['date_of_use'],
            $_POST['reservation_time'],
            $_POST['event_type'],
            $_POST['venue'],
            $equipment
        );

        if ($insertReservation->execute()) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error creating reservation: " . $insertReservation->error);
        }

    } catch (Exception $e) {
        error_log("Error in user.php: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>

