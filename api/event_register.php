<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
        exit;
    }

    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) $data = $_POST;
    $eventId = (int)($data['event_id'] ?? 0);
    if ($eventId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid event id']);
        exit;
    }

    global $db;
    // Ensure event exists and not full
    $stmt = $db->prepare("SELECT id, max_participants, current_participants FROM events WHERE id = ?");
    $stmt->execute([$eventId]);
    $event = $stmt->fetch();
    if (!$event) {
        echo json_encode(['success' => false, 'message' => 'Event not found']);
        exit;
    }
    if ($event['max_participants'] > 0 && $event['current_participants'] >= $event['max_participants']) {
        echo json_encode(['success' => false, 'message' => 'Event is full']);
        exit;
    }

    // Register (unique per user)
    $db->beginTransaction();
    try {
        $ins = $db->prepare("INSERT INTO event_registrations (event_id, user_id) VALUES (?, ?)");
        $ins->execute([$eventId, $_SESSION['user_id']]);
        $upd = $db->prepare("UPDATE events SET current_participants = current_participants + 1 WHERE id = ?");
        $upd->execute([$eventId]);
        $db->commit();
    } catch (Throwable $e) {
        $db->rollBack();
        // Duplicate registration
        echo json_encode(['success' => false, 'message' => 'Already registered for this event']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Registered successfully']);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>


