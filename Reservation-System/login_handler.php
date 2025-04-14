<?php
session_start();
require_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_name'] = $row['name']; // Add admin name to session
            
            echo json_encode([
                'success' => true,
                'adminName' => $row['name']
            ]);
            exit;
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}
?>