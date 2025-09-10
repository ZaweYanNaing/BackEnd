<?php
// Tip update API endpoint
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Start output buffering to prevent any output before JSON
ob_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User must be logged in to edit tips');
    }

    require_once __DIR__ . '/../config/database.php';

    /** @var PDO $db */
    $db = $database->getConnection();
    if (!$db) {
        throw new Exception('Database connection failed');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }

    if (empty($input['tip_id'])) {
        throw new Exception('Tip ID is required');
    }

    $tipId = (int)$input['tip_id'];
    $title = isset($input['title']) ? trim($input['title']) : '';
    $content = isset($input['content']) ? trim($input['content']) : '';
    $prepTime = isset($input['prep_time']) && $input['prep_time'] !== '' ? (int)$input['prep_time'] : null;
    $userId = (int)$_SESSION['user_id'];

    if ($title === '' || $content === '') {
        throw new Exception('Title and content are required');
    }

    // Ensure the tip exists and belongs to the user
    $check = $db->prepare('SELECT user_id FROM cooking_tips WHERE id = :id');
    $check->bindParam(':id', $tipId, PDO::PARAM_INT);
    $check->execute();
    $tip = $check->fetch();
    if (!$tip) {
        throw new Exception('Cooking tip not found');
    }
    if ((int)$tip['user_id'] !== $userId) {
        throw new Exception('You can only edit your own tips');
    }

    $sql = 'UPDATE cooking_tips 
            SET title = :title, content = :content, prep_time = :prep_time
            WHERE id = :id';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':prep_time', $prepTime, $prepTime === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':id', $tipId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        ob_clean();
        echo json_encode(['success' => true, 'message' => 'Cooking tip updated successfully']);
    } else {
        throw new Exception('Failed to update cooking tip');
    }
} catch (Exception $e) {
    ob_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (Error $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred. Please try again.']);
}

ob_end_flush();
?>


