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
    $firstName = sanitizeInput($_POST['firstName'] ?? '');
    $lastName = sanitizeInput($_POST['lastName'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Please fill in all required fields.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
        exit;
    }

    if (strlen($password) < 6) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters long.']);
        exit;
    }

    if ($password !== $confirmPassword) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Passwords do not match.']);
        exit;
    }

    // Check if email already exists
    $existingUser = getUserByEmail($email);
    if ($existingUser) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'An account with this email already exists.']);
        exit;
    }

    // Create user data
    $userData = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'bio' => '',
        'location' => '',
        'website' => '',
        'profile_image' => ''
    ];

    // Register user
    global $db;
    if (!$db) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Database connection failed. Please try again later.']);
        exit;
    }
    
    $stmt = $db->prepare("INSERT INTO users (firstName, lastName, email, password, bio, location, website, profile_image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $result = $stmt->execute([
        $userData['firstName'],
        $userData['lastName'],
        $userData['email'],
        $userData['password'],
        $userData['bio'],
        $userData['location'],
        $userData['website'],
        $userData['profile_image']
    ]);
    
    if ($result) {
        // Get the new user ID
        $userId = $db->lastInsertId();
        
        // Set session
        $_SESSION['user_id'] = $userId;
        
        ob_clean();
        echo json_encode([
            'success' => true, 
            'message' => 'Account created successfully!',
            'user_id' => $userId
        ]);
    } else {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Failed to create account. Please try again.']);
    }
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'An error occurred while creating your account: ' . $e->getMessage()]);
}
?>
