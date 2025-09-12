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

    $stats = [
        'total_resources' => 0,
        'documents' => 0,
        'infographics' => 0,
        'videos' => 0,
        'presentations' => 0,
        'guides' => 0,
        'total_downloads' => 0,
        'avg_downloads' => 0,
    ];

    $total = $db->query("SELECT COUNT(*) as c FROM educational_resources")->fetch(PDO::FETCH_ASSOC)['c'];
    $stats['total_resources'] = (int)$total;

    $byType = $db->query("SELECT type, COUNT(*) as c FROM educational_resources GROUP BY type")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($byType as $row) {
        $key = $row['type'] . 's';
        if (isset($stats[$key])) $stats[$key] = (int)$row['c'];
    }

    $totalDownloads = $db->query("SELECT COALESCE(SUM(download_count),0) as d FROM educational_resources")->fetch(PDO::FETCH_ASSOC)['d'];
    $stats['total_downloads'] = (int)$totalDownloads;
    $stats['avg_downloads'] = $stats['total_resources'] > 0 ? round($stats['total_downloads'] / $stats['total_resources'], 1) : 0;

    ob_clean();
    echo json_encode(['success' => true, 'data' => $stats]);
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


