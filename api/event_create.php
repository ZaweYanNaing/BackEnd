<?php
// JSON API to create a community cooking event
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
        exit;
    }

    // Accept JSON or form-encoded
    $payload = [];
    $raw = file_get_contents('php://input');
    if (!empty($raw) && ($json = json_decode($raw, true)) && is_array($json)) {
        $payload = $json;
    } else {
        $payload = $_POST;
    }

    $title = trim($payload['title'] ?? '');
    $description = trim($payload['description'] ?? '');
    $eventDate = trim($payload['event_date'] ?? ''); // ISO or "YYYY-MM-DD HH:MM"
    $location = trim($payload['location'] ?? '');
    $maxParticipants = (int)($payload['max_participants'] ?? 0);

    if ($title === '' || $description === '' || $eventDate === '' || $location === '') {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Handle image upload
    $imageUrl = null;
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['event_image'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            // Validate file size (max 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] <= $maxSize) {
                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'event_' . $_SESSION['user_id'] . '_' . time() . '_' . uniqid() . '.' . $extension;
                $uploadPath = __DIR__ . '/../uploads/' . $filename;
                
                // Create uploads directory if it doesn't exist
                if (!is_dir(__DIR__ . '/../uploads/')) {
                    mkdir(__DIR__ . '/../uploads/', 0755, true);
                }
                
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $imageUrl = $filename;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Image too large. Maximum size is 5MB.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.']);
            exit;
        }
    }

    // Normalize event date to MySQL DATETIME
    $ts = strtotime($eventDate);
    if ($ts === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid event date/time.']);
        exit;
    }
    $eventDateMysql = date('Y-m-d H:i:s', $ts);

    global $db;
    $stmt = $db->prepare("INSERT INTO events (title, description, event_date, location, max_participants, current_participants, image_url, created_by) VALUES (?, ?, ?, ?, ?, 0, ?, ?)");
    $ok = $stmt->execute([
        $title,
        $description,
        $eventDateMysql,
        $location,
        max(0, $maxParticipants),
        $imageUrl,
        $_SESSION['user_id']
    ]);

    if (!$ok) {
        echo json_encode(['success' => false, 'message' => 'Failed to create event']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Event created', 'event_id' => $db->lastInsertId()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>


