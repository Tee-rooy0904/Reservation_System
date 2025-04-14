<?php
session_start();
header('Content-Type: application/json');
require_once('../User/config.php');

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

try {
    // Check database connection
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Check if table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'pmo'");
    if ($tableCheck->num_rows === 0) {
        throw new Exception("PMO table not found");
    }

    $stmt = $conn->prepare("SELECT * FROM pmo WHERE username = ?");
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['pmo_user'] = $username;
            $_SESSION['user_type'] = 'pmo';
            $_SESSION['pmo_logged_in'] = true; // Added this for extra verification
            echo json_encode([
                'success' => true,
                'redirect' => 'pmo_dashboard.php'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

if (isset($conn)) {
    $conn->close();
}
?>