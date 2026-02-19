<?php
// Seed script - run once to generate articles.json from the PHP data
require __DIR__ . '/../data_articles.php';
$clean = [];
foreach ($articles as $a) {
    $clean[] = [
        'id'       => (string)$a['id'],
        'title'    => $a['title'],
        'excerpt'  => $a['excerpt'] ?? '',
        'content'  => $a['content'] ?? '',
        'image'    => $a['image'],
        'category' => $a['category'],
        'author'   => $a['author'],
        'date'     => $a['date'],
        'readTime' => $a['readTime'],
        'featured' => (bool)($a['featured'] ?? false),
        'trending' => (bool)($a['trending'] ?? false),
        'views'    => (int)($a['views'] ?? 0),
        'comments' => (int)($a['comments'] ?? 0),
    ];
}
file_put_contents(__DIR__ . '/../data/articles.json', json_encode($clean, JSON_PRETTY_PRINT));
echo 'Done. Articles: ' . count($clean);
