<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

try {
    global $db;

    $limit = max(1, min(50, (int)($_GET['limit'] ?? 10)));
    $offset = max(0, (int)($_GET['offset'] ?? 0));

    $stmt = $db->prepare("SELECT e.*, u.firstName, u.lastName FROM events e JOIN users u ON e.created_by = u.id ORDER BY e.event_date ASC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $countStmt = $db->query("SELECT COUNT(*) as total FROM events");
    $total = (int)($countStmt->fetch()['total'] ?? 0);

    echo json_encode(['success' => true, 'data' => $rows, 'total' => $total]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>


