<?php
require_once('config.php');
header('Content-Type: application/json');

try {
    $query = "SELECT name, status FROM equipment";
    $result = $conn->query($query);
    
    $equipment = [];
    while ($row = $result->fetch_assoc()) {
        $equipment[] = $row;
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