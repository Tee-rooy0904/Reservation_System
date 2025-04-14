<?php
session_start();
require_once('../User/config.php');

header('Content-Type: application/json');

$equipment = $_POST['equipment'] ?? '';
$status = $_POST['status'] ?? '';

try {
    $stmt = $conn->prepare("UPDATE equipment SET status = ?, last_updated = NOW() WHERE name = ?");
    $stmt->bind_param("ss", $status, $equipment);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    } else {
        throw new Exception("Failed to update status");
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();
?>