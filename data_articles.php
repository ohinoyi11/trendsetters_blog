<?php
/**
 * Global data loader — TrendSetters News
 * Previously JSON-based, now reads from MySQL with fallback.
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/ts-manager/helpers.php';

// Detect base dir for routing/assets
$_subdir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$base = $_subdir === '' ? '' : $_subdir;

try {
    $pdo = get_pdo();

    // ── Site Settings ──────────────────────────────────────────
    $siteSettings = [];
    foreach ($pdo->query("SELECT `key`,`value` FROM settings")->fetchAll() as $row) {
        $siteSettings[$row['key']] = $row['value'];
    }
    $siteName = $siteSettings['site_name'] ?? 'TrendSetters News';
    $siteTagline = $siteSettings['tagline'] ?? 'Your daily dose of trending stories';

    // ── Categories ─────────────────────────────────────────────
    $categories = array_column($pdo->query("SELECT name FROM categories ORDER BY name")->fetchAll(), 'name');

    // ── Breaking News ──────────────────────────────────────────
    $breakingNews = array_column($pdo->query("SELECT ticker FROM breaking_news ORDER BY sort_order ASC")->fetchAll(), 'ticker');

    // ── Articles ───────────────────────────────────────────────
    // Load all published articles for the frontend
    $stmt = $pdo->query(
        "SELECT a.*, c.name AS category, u.name AS author_name, u.role AS author_role
         FROM articles a
         LEFT JOIN categories c ON a.category_id = c.id
         LEFT JOIN users u ON a.author_id = u.id
         WHERE a.status = 'published'
         ORDER BY a.id DESC"
    );
    $articles = [];
    foreach ($stmt->fetchAll() as $row) {
        $articles[] = [
            'id'       => $row['id'],
            'title'    => $row['title'],
            'excerpt'  => $row['excerpt'],
            'content'  => $row['content'],
            'image'    => $row['image'],
            'category' => $row['category'],
            'author'   => [
                'name' => $row['author_name'],
                'role' => $row['author_role'] === 'super_admin' ? 'Chief Editor' : 'Correspondent',
                'avatar' => null // Will be generated via initials() helper in templates
            ],
            'date'     => $row['date'],
            'readTime' => $row['read_time'],
            'featured' => (bool)$row['featured'],
            'trending' => (bool)$row['trending'],
            'views'    => (int)$row['views'],
            'comments' => (int)$row['comment_count'],
        ];
    }

} catch (PDOException $e) {
    // If DB isn't setup yet, fallback to empty arrays to avoid fatal errors
    $siteName = 'TrendSetters News';
    $siteTagline = 'Your daily dose of trending stories';
    $categories = [];
    $breakingNews = [];
    $articles = [];
}

/**
 * Find article by ID
 */
function findArticleById($id, $articles) {
    foreach ($articles as $article) {
        if ($article['id'] == $id) return $article;
    }
    return null;
}
