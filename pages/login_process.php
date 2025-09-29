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
    
    if ($user) {
        // Check if account is locked using MySQL time for consistency
        if ($user['account_locked_until']) {
            $stmt = $db->prepare("SELECT NOW() as mysql_time, ? as lockout_time, NOW() < ? as is_locked");
            $stmt->execute([$user['account_locked_until'], $user['account_locked_until']]);
            $timeCheck = $stmt->fetch();
            
            if ($timeCheck['is_locked']) {
                $lockoutTime = date('H:i:s', strtotime($user['account_locked_until']));
                ob_clean();
                echo json_encode([
                    'success' => false, 
                    'error' => "Account is locked due to multiple failed login attempts. Please try again after {$lockoutTime}."
                ]);
                exit;
            } else {
                // Lockout has expired, reset failed attempts to 0
                $stmt = $db->prepare("UPDATE users SET failed_login_attempts = 0, account_locked_until = NULL WHERE id = ?");
                $stmt->execute([$user['id']]);
                $user['failed_login_attempts'] = 0; // Update local variable
            }
        }
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Reset failed attempts on successful login
            $stmt = $db->prepare("UPDATE users SET failed_login_attempts = 0, last_failed_login = NULL, account_locked_until = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            
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
            // Increment failed attempts, but cap at 3
            $currentAttempts = $user['failed_login_attempts'] ?? 0;
            $failedAttempts = min($currentAttempts + 1, 3);
            
            // Lock account after 3 failed attempts for 3 minutes
            if ($failedAttempts >= 3) {
                // Use MySQL's DATE_ADD to ensure timezone consistency
                $stmt = $db->prepare("UPDATE users SET failed_login_attempts = 3, last_failed_login = NOW(), account_locked_until = DATE_ADD(NOW(), INTERVAL 3 MINUTE) WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                ob_clean();
                echo json_encode([
                    'success' => false, 
                    'error' => "Account locked due to multiple failed login attempts. Please try again in 3 minutes."
                ]);
            } else {
                $stmt = $db->prepare("UPDATE users SET failed_login_attempts = ?, last_failed_login = NOW(), account_locked_until = NULL WHERE id = ?");
                $stmt->execute([$failedAttempts, $user['id']]);
                
                $remainingAttempts = 3 - $failedAttempts;
                ob_clean();
                echo json_encode([
                    'success' => false, 
                    'error' => "Invalid email or password. {$remainingAttempts} attempt(s) remaining."
                ]);
            }
        }
    } else {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Invalid email or password.']);
    }
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => 'An error occurred during login: ' . $e->getMessage()]);
}
?>
