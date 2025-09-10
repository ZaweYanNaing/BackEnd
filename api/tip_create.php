<?php
// Tip creation API endpoint
session_start();
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

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User must be logged in to create tips');
    }

    // Include database connection
    require_once __DIR__ . '/../config/database.php';
    
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
    $required_fields = ['title', 'content'];
    foreach ($required_fields as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field '$field' is required");
        }
    }

    // Sanitize and validate input
    $title = trim($input['title']);
    $content = trim($input['content']);
    $prepTime = !empty($input['prep_time']) ? (int)$input['prep_time'] : null;
    $userId = (int)$_SESSION['user_id'];

    // Prepare SQL statement
    $sql = "INSERT INTO cooking_tips (title, content, user_id, prep_time) 
            VALUES (:title, :content, :user_id, :prep_time)";
    
    $stmt = $db->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':prep_time', $prepTime, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        // Clear output buffer
        ob_clean();
        
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Cooking tip created successfully!'
        ]);
    } else {
        throw new Exception('Failed to create cooking tip');
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
