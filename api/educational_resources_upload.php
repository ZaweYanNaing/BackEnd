<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

ob_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Login required to upload resources');
    }

    require_once __DIR__ . '/../config/database.php';
    /** @var PDO $db */
    $db = $database->getConnection();
    if (!$db) throw new Exception('Database connection failed');

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = trim($_POST['type'] ?? '');
    if ($title === '' || $description === '' || $type === '') {
        throw new Exception('Title, description, and type are required');
    }
    $allowedTypes = ['document','infographic','video','presentation','guide'];
    if (!in_array($type, $allowedTypes, true)) {
        throw new Exception('Invalid type');
    }
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload failed');
    }

    $uploadDir = __DIR__ . '/../uploads/educational-resources/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0775, true)) {
            throw new Exception('Failed to create upload directory');
        }
    }

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $safeExt = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($ext));
    $fileName = uniqid() . '_' . time() . '.' . $safeExt;
    $destPath = $uploadDir . $fileName;
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $destPath)) {
        throw new Exception('Failed to save uploaded file');
    }

    $publicPath = '/uploads/educational-resources/' . $fileName;

    $stmt = $db->prepare("INSERT INTO educational_resources (title, description, type, file_path, created_by) VALUES (:title, :description, :type, :file_path, :created_by)");
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':type', $type);
    $stmt->bindValue(':file_path', $publicPath);
    $stmt->bindValue(':created_by', (int)$_SESSION['user_id'], PDO::PARAM_INT);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert resource');
    }

    ob_clean();
    echo json_encode(['success' => true, 'message' => 'Resource uploaded', 'file_path' => $publicPath]);
} catch (Exception $e) {
    ob_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (Error $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unexpected server error']);
}

ob_end_flush();
?>


