<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

if (isset($_GET['id'])) {
    $id    = (int)$_GET['id'];
    $admin = current_admin();

    // Access control: Correspondents can only delete their own articles
    if (!is_super_admin()) {
        $article = get_article($id);
        if ($article && $article['author_id'] != $admin['id']) {
            header('Location: articles.php'); exit;
        }
    }

    delete_article($id);
}

header('Location: articles.php');
exit;
