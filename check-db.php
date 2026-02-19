<?php
require_once __DIR__ . '/config/db.php';
$pdo = get_pdo();
$users = $pdo->query("SELECT id, name, email, role, password_hash FROM users")->fetchAll();
echo "<pre>";
print_r($users);
echo "</pre>";
