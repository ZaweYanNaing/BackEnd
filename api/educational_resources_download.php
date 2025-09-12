<?php
// Stream a resource file and increment download_count
session_start();

ob_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Only GET method allowed');
    }
    if (!isset($_GET['id'])) {
        throw new Exception('Resource id is required');
    }

    require_once __DIR__ . '/../config/database.php';
    /** @var PDO $db */
    $db = $database->getConnection();

    $id = (int)$_GET['id'];
    $stmt = $db->prepare('SELECT file_path, title FROM educational_resources WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        throw new Exception('Resource not found');
    }

    // Increment counter
    $db->prepare('UPDATE educational_resources SET download_count = download_count + 1 WHERE id = :id')->execute([':id' => $id]);

    $publicPath = $row['file_path']; // like /uploads/educational-resources/xxx.ext
    $filePath = realpath(__DIR__ . '/..' . $publicPath);
    if (!$filePath || !is_file($filePath)) {
        throw new Exception('File not found');
    }

    $fileName = basename($filePath);
    $mime = mime_content_type($filePath) ?: 'application/octet-stream';

    // Clean any output and stream
    ob_end_clean();
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: no-cache');
    readfile($filePath);
    exit;
} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} catch (Error $e) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Unexpected server error']);
}

?>


