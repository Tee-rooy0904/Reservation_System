<?php
require_once('config.php');
header('Content-Type: application/json');

try {
    $query = "SELECT name, status, last_updated FROM equipment";
    $result = $conn->query($query);
    
    $equipment = array();
    while($row = $result->fetch_assoc()) {
        $equipment[] = array(
            'name' => $row['name'],
            'status' => $row['status'],
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