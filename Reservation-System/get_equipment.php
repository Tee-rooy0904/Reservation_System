<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['pmo_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$query = "SELECT name, status, last_updated FROM equipment ORDER BY name";
$result = $conn->query($query);

$equipment = [];
while ($row = $result->fetch_assoc()) {
    $equipment[] = $row;
}

echo json_encode(['success' => true, 'equipment' => $equipment]);
?>