<?php
// Tip delete API endpoint
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
        throw new Exception('User must be logged in to delete tips');
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
    if (empty($input['tip_id'])) {
        throw new Exception('Tip ID is required');
    }

    $tipId = (int)$input['tip_id'];
    $userId = (int)$_SESSION['user_id'];

    // Check if tip exists and user owns it
    $checkQuery = "SELECT user_id FROM cooking_tips WHERE id = :tip_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':tip_id', $tipId);
    $checkStmt->execute();
    
    $tip = $checkStmt->fetch();
    if (!$tip) {
        throw new Exception('Cooking tip not found');
    }

    if ($tip['user_id'] != $userId) {
        throw new Exception('You can only delete your own tips');
    }

    // Delete the tip (cascade will handle tip_likes)
    $deleteQuery = "DELETE FROM cooking_tips WHERE id = :tip_id";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bindParam(':tip_id', $tipId);
    
    if ($deleteStmt->execute()) {
        // Clear output buffer
        ob_clean();
        
        echo json_encode([
            'success' => true,
            'message' => 'Cooking tip deleted successfully'
        ]);
    } else {
        throw new Exception('Failed to delete cooking tip');
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

