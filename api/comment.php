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
$content    = trim($_POST['content'] ?? '');

if (!$article_id || empty($content)) {
    echo json_encode(['error' => 'Invalid data']); exit;
}

try {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("INSERT INTO comments (article_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$article_id, $user['id'], $content]);

    // Update comment count on article cache
    $pdo->prepare("UPDATE articles SET comment_count = comment_count + 1 WHERE id=?")->execute([$article_id]);

    $id = $pdo->lastInsertId();
    $date = date('M j, Y, g:i a');

    // Return HTML fragment for immediate insertion
    ob_start();
    ?>
    <div class="flex gap-4 p-5 animate-in fade-in slide-in-from-top-2 duration-300">
      <div class="h-10 w-10 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
        <?= initials($user['name']) ?>
      </div>
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="text-sm font-bold text-slate-900"><?= htmlspecialchars($user['name']) ?></span>
          <span class="text-[10px] text-slate-400 font-medium"><?= $date ?></span>
        </div>
        <p class="text-sm text-slate-600 leading-relaxed"><?= nl2br(htmlspecialchars($content)) ?></p>
      </div>
    </div>
    <?php
    $html = ob_get_clean();

    echo json_encode(['success' => true, 'html' => $html]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
