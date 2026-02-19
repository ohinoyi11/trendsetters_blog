<?php
require_once dirname(__DIR__) . '/ts-manager/helpers.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid method']); exit;
}

$user = get_site_user();
if (!$user) {
    echo json_encode(['error' => 'Unauthorized']); exit;
}

$article_id = (int)($_POST['article_id'] ?? 0);
$type       = $_POST['type'] ?? '';

if (!$article_id || !in_array($type, ['like','love','fire'])) {
    echo json_encode(['error' => 'Invalid data']); exit;
}

try {
    $pdo = get_pdo();
    // Use REPLACE INTO for upsert behavior (user can change their reaction)
    $stmt = $pdo->prepare("REPLACE INTO reactions (article_id, user_id, type) VALUES (?, ?, ?)");
    $stmt->execute([$article_id, $user['id'], $type]);

    // Fetch new totals
    $totals = $pdo->prepare("SELECT type, COUNT(*) as count FROM reactions WHERE article_id=? GROUP BY type");
    $totals->execute([$article_id]);
    $counts = ['like'=>0, 'love'=>0, 'fire'=>0];
    foreach ($totals->fetchAll() as $row) { $counts[$row['type']] = (int)$row['count']; }

    echo json_encode(['success' => true, 'counts' => $counts, 'user_type' => $type]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
