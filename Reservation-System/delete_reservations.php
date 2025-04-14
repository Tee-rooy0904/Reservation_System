<?php
header('Content-Type: application/json');
require_once '../User/config.php';  // Updated path to config.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    $user_id_number = isset($_POST['user_id_number']) ? $_POST['user_id_number'] : '';
    
    if (empty($user_id_number)) {
        throw new Exception('User ID is required');
    }

    // First delete from reservations table
    $stmt = $conn->prepare("DELETE FROM reservations WHERE user_id_number = ?");
    $stmt->bind_param("s", $user_id_number);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>