<?php
// Start session to access user data (only if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Contact form processing API endpoint
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Start output buffering to prevent any output before JSON
ob_start();

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    // Include database connection
    require_once __DIR__ . '/config/database.php';
    
    // Get database connection
    /** @var PDO $db */
    $db = $database->getConnection();
    
    // Check if database connection is successful
    if (!$db) {
        throw new Exception('Database connection failed');
    }

    // Get form data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST; // Fallback to POST data
    }

    // Validate required fields
    $required_fields = ['firstName', 'lastName', 'email', 'subject', 'message'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field '$field' is required");
        }
    }

    // Sanitize and validate input
    $firstName = trim($input['firstName']);
    $lastName = trim($input['lastName']);
    $email = trim($input['email']);
    $subject = trim($input['subject']);
    $message = trim($input['message']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Validate subject is one of the allowed values
    $allowed_subjects = ['general', 'support', 'feedback', 'partnership', 'other'];
    if (!in_array($subject, $allowed_subjects)) {
        throw new Exception('Invalid subject selected');
    }

    // Get user_id if user is logged in
    $userId = null;
    if (isset($_SESSION['user_id'])) {
        $userId = (int)$_SESSION['user_id'];
    }

    // Prepare SQL statement
    $sql = "INSERT INTO contact_messages (user_id, first_name, last_name, email, subject, message) 
            VALUES (:user_id, :first_name, :last_name, :email, :subject, :message)";
    
    $stmt = $db->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);

    // Execute the statement
    if ($stmt->execute()) {
        // Clear output buffer
        ob_clean();
        
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Your message has been sent successfully! We\'ll get back to you soon.'
        ]);
    } else {
        throw new Exception('Failed to save message to database');
    }

} catch (Exception $e) {
    // Clear output buffer
    ob_clean();
    
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (Error $e) {
    // Clear output buffer
    ob_clean();
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again.'
    ]);
}

// End output buffering
ob_end_flush();
?>
