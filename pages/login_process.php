<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON first
header('Content-Type: application/json');

// Start output buffering to catch any unwanted output
ob_start();

session_start();

try {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../includes/functions.php';
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'Server configuration error: ' . $e->getMessage()]);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

try {
    // Get form data
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($email) || empty($password)) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Please fill in all fields.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
        exit;
    }

    // Check if user exists and verify password
    global $db;
    if (!$db) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Database connection failed. Please try again later.']);
        exit;
    }
    
    $user = getUserByEmail($email);
    
    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        
        ob_clean();
        echo json_encode([
            'success' => true, 
            'message' => 'Login successful!',
            'user_id' => $user['id'],
            'user_name' => $user['firstName'] . ' ' . $user['lastName']
        ]);
    } else {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Invalid email or password.']);
    }
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'An error occurred during login: ' . $e->getMessage()]);
}
?>
