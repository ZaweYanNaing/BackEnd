<?php
// Tip like/unlike API endpoint
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
        throw new Exception('User must be logged in to like tips');
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

    // Check if tip exists
    $checkQuery = "SELECT id FROM cooking_tips WHERE id = :tip_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':tip_id', $tipId);
    $checkStmt->execute();
    
    if (!$checkStmt->fetch()) {
        throw new Exception('Cooking tip not found');
    }

    // Check if already liked
    $likeQuery = "SELECT id FROM tip_likes WHERE user_id = :user_id AND tip_id = :tip_id";
    $likeStmt = $db->prepare($likeQuery);
    $likeStmt->bindParam(':user_id', $userId);
    $likeStmt->bindParam(':tip_id', $tipId);
    $likeStmt->execute();
    
    $existingLike = $likeStmt->fetch();

    if ($existingLike) {
        // Remove like
        $deleteQuery = "DELETE FROM tip_likes WHERE user_id = :user_id AND tip_id = :tip_id";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindParam(':user_id', $userId);
        $deleteStmt->bindParam(':tip_id', $tipId);
        
        if ($deleteStmt->execute()) {
            // Get updated like count
            $countQuery = "SELECT COUNT(*) as like_count FROM tip_likes WHERE tip_id = :tip_id";
            $countStmt = $db->prepare($countQuery);
            $countStmt->bindParam(':tip_id', $tipId);
            $countStmt->execute();
            $likeCount = $countStmt->fetch()['like_count'];
            
            // Clear output buffer
            ob_clean();
            
            echo json_encode([
                'success' => true,
                'message' => 'Tip unliked',
                'isLiked' => false,
                'like_count' => (int)$likeCount
            ]);
        } else {
            throw new Exception('Failed to unlike tip');
        }
    } else {
        // Add like
        $insertQuery = "INSERT INTO tip_likes (user_id, tip_id) VALUES (:user_id, :tip_id)";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bindParam(':user_id', $userId);
        $insertStmt->bindParam(':tip_id', $tipId);
        
        if ($insertStmt->execute()) {
            // Get updated like count
            $countQuery = "SELECT COUNT(*) as like_count FROM tip_likes WHERE tip_id = :tip_id";
            $countStmt = $db->prepare($countQuery);
            $countStmt->bindParam(':tip_id', $tipId);
            $countStmt->execute();
            $likeCount = $countStmt->fetch()['like_count'];
            
            // Clear output buffer
            ob_clean();
            
            echo json_encode([
                'success' => true,
                'message' => 'Tip liked',
                'isLiked' => true,
                'like_count' => (int)$likeCount
            ]);
        } else {
            throw new Exception('Failed to like tip');
        }
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
