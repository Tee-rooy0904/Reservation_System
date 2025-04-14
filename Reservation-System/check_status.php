<?php
header('Content-Type: application/json');
require_once('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "SELECT status FROM reservations WHERE user_id_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "status" => $row['status']
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Reservation not found"
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID not provided']);
}
?>
