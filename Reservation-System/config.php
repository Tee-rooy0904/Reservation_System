<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'reservation_system';

try {
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set the character set to utf8
    $conn->set_charset("utf8");
    
    // Disable strict mode for this session
    $conn->query("SET SESSION sql_mode = ''");
    
} catch (Exception $e) {
    if (php_sapi_name() !== 'cli') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
    throw $e;
}

// Make connection available globally
global $conn;
?>
