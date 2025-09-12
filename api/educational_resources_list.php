<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

ob_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Only GET method allowed');
    }

    require_once __DIR__ . '/../config/database.php';
    /** @var PDO $db */
    $db = $database->getConnection();
    if (!$db) {
        throw new Exception('Database connection failed');
    }

    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';
    $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 12;
    $offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;

    $where = [];
    $params = [];
    if ($type !== '') {
        $where[] = 'type = :type';
        $params[':type'] = $type;
    }
    if ($search !== '') {
        $where[] = '(title LIKE :q OR description LIKE :q)';
        $params[':q'] = '%' . $search . '%';
    }
    $whereClause = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

    switch ($sort) {
        case 'popular':
            $orderBy = 'ORDER BY download_count DESC, created_at DESC';
            break;
        case 'title':
            $orderBy = 'ORDER BY title ASC';
            break;
        default:
            $orderBy = 'ORDER BY created_at DESC';
    }

    $countSql = "SELECT COUNT(*) as total FROM educational_resources $whereClause";
    $countStmt = $db->prepare($countSql);
    foreach ($params as $k => $v) { $countStmt->bindValue($k, $v); }
    $countStmt->execute();
    $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Note: Some MySQL drivers don't accept bound params for LIMIT/OFFSET when emulation is off
    $limit = (int)$limit; $offset = (int)$offset;
    $sql = "SELECT id, title, description, type, file_path, download_count, created_by, created_at, updated_at
            FROM educational_resources
            $whereClause
            $orderBy
            LIMIT $limit OFFSET $offset";
    $stmt = $db->prepare($sql);
    foreach ($params as $k => $v) { $stmt->bindValue($k, $v); }
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ob_clean();
    echo json_encode([
        'success' => true,
        'data' => $rows,
        'total' => $total,
    ]);
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


