<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

require_once('config.php');

$id = $_GET['id'] ?? '';

if ($id) {
    $stmt = $conn->prepare("SELECT status FROM reservations WHERE user_id_number = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo "data: " . json_encode(['id' => $id, 'status' => $row['status']]) . "\n\n";
    }
}
flush();
?>