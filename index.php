<?php
require_once __DIR__ . '/data_articles.php';

// Simple router
// Detect base path for robust asset linking
$scriptName = $_SERVER['SCRIPT_NAME']; // e.g. /php/index.php
$base = rtrim(dirname($scriptName), '/\\');
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Extract the path relative to the project root
if (strpos($requestUri, $base) === 0) {
    $path = substr($requestUri, strlen($base));
} else {
    $path = $requestUri;
}
$path = trim($path, '/');

if ($path === '' || $path === 'index.php') {
  $page = 'home';
} elseif (preg_match('#^article/([0-9]+)$#', $path, $m)) {
  $_GET['id'] = $m[1];
  $page = 'article';
} elseif (preg_match('#^category/([a-z0-9\-]+)$#', $path, $m)) {
  $_GET['slug'] = $m[1];
  $page = 'category';
} elseif ($path === 'about') {
  $page = 'about';
} elseif ($path === 'login' || $path === 'register') {
  $page = 'auth';
} elseif ($path === 'logout') {
  $page = 'user-logout';
} elseif ($path === 'search') {
  $page = 'search';
} else {
  $page = '404';
}

ob_start();
include __DIR__ . '/pages/' . $page . '.php';
$content = ob_get_clean();

include __DIR__ . '/templates/layout.php';
