<?php
/**
 * TrendSetters News — Database Installer
 * Run once at: http://localhost/php/setup/install.php
 * Creates the `trendsetters` database + all tables, seeds admin account,
 * and migrates any existing JSON data.
 */

// ── Config ─────────────────────────────────────────────────
define('DB_HOST',    '127.0.0.1');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_NAME',    'trendsetters');
define('DB_CHARSET', 'utf8mb4');

$ROOT = dirname(__DIR__);

function log_step(string $msg, bool $ok = true): void {
    $icon = $ok ? '✅' : '❌';
    echo "<p style='margin:4px 0;font-family:monospace'>{$icon} {$msg}</p>\n";
    flush();
}

// ── Connect (no DB selected yet) ───────────────────────────
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("<b>Cannot connect to MySQL:</b> " . htmlspecialchars($e->getMessage()) . "<br>Make sure XAMPP MySQL is running.");
}

// ── Create database ─────────────────────────────────────────
$pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `" . DB_NAME . "`");
log_step("Database `" . DB_NAME . "` ready");

// ── Schema ──────────────────────────────────────────────────
$tables = [];

$tables['settings'] = "
CREATE TABLE IF NOT EXISTS `settings` (
  `key`   varchar(100) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$tables['users'] = "
CREATE TABLE IF NOT EXISTS `users` (
  `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          varchar(120) NOT NULL,
  `email`         varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role`          enum('super_admin','correspondent','user') NOT NULL DEFAULT 'user',
  `bio`           text,
  `status`        enum('active','suspended') NOT NULL DEFAULT 'active',
  `created_at`    datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$tables['categories'] = "
CREATE TABLE IF NOT EXISTS `categories` (
  `id`   int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$tables['articles'] = "
CREATE TABLE IF NOT EXISTS `articles` (
  `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`       varchar(255) NOT NULL,
  `excerpt`     text,
  `content`     longtext,
  `image`       varchar(255) DEFAULT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `author_id`   int(11) UNSIGNED DEFAULT NULL,
  `date`        varchar(50) DEFAULT NULL,
  `read_time`   varchar(30) DEFAULT '5 min read',
  `featured`    tinyint(1) NOT NULL DEFAULT 0,
  `trending`    tinyint(1) NOT NULL DEFAULT 0,
  `views`       int(11) NOT NULL DEFAULT 0,
  `comment_count` int(11) NOT NULL DEFAULT 0,
  `status`      enum('published','draft') NOT NULL DEFAULT 'published',
  `created_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `author_id` (`author_id`),
  KEY `featured` (`featured`),
  KEY `trending` (`trending`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$tables['breaking_news'] = "
CREATE TABLE IF NOT EXISTS `breaking_news` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticker`     varchar(500) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$tables['comments'] = "
CREATE TABLE IF NOT EXISTS `comments` (
  `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `article_id` int(11) UNSIGNED NOT NULL,
  `user_id`    int(11) UNSIGNED NOT NULL,
  `content`    text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$tables['reactions'] = "
CREATE TABLE IF NOT EXISTS `reactions` (
  `article_id` int(11) UNSIGNED NOT NULL,
  `user_id`    int(11) UNSIGNED NOT NULL,
  `type`       enum('like','love','fire') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

foreach ($tables as $name => $sql) {
    $pdo->exec($sql);
    log_step("Table `{$name}` ready");
}

// ── Seed settings ───────────────────────────────────────────
$settingsFile = $ROOT . '/data/settings.json';
$settingsData = file_exists($settingsFile) ? json_decode(file_get_contents($settingsFile), true) : [];
$defaults = [
    'site_name'   => 'TrendSetters News',
    'tagline'     => 'Your daily dose of trending stories',
    'description' => 'Delivering trusted news and stories that matter.',
];
$merged = array_merge($defaults, $settingsData);
$stmtS = $pdo->prepare("INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)");
foreach ($merged as $k => $v) {
    if (in_array($k, ['site_name','tagline','description'])) {
        $stmtS->execute([$k, $v]);
    }
}
log_step("Settings seeded");

// ── Seed/migrate super admin ────────────────────────────────
$adminUser = $settingsData['admin_username'] ?? 'admin';
$adminHash = $settingsData['admin_password_hash'] ?? '';
if (empty($adminHash)) {
    $adminHash = password_hash('admin123', PASSWORD_DEFAULT);
}

$exists = $pdo->prepare("SELECT id FROM users WHERE role='super_admin' LIMIT 1");
$exists->execute();
if (!$exists->fetch()) {
    $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,'super_admin')")
        ->execute([$adminUser, 'admin@trendsettersnews.com', $adminHash]);
    log_step("Super admin account created (username: {$adminUser})");
} else {
    log_step("Super admin already exists — skipped");
}

// ── Migrate categories ──────────────────────────────────────
$catFile = $ROOT . '/data/categories.json';
if (file_exists($catFile)) {
    $cats = json_decode(file_get_contents($catFile), true) ?? [];
    $stmtC = $pdo->prepare("INSERT IGNORE INTO categories (name) VALUES (?)");
    foreach ($cats as $c) { $stmtC->execute([trim($c)]); }
    log_step("Categories migrated (" . count($cats) . ")");
}

// ── Migrate breaking news ───────────────────────────────────
$brkFile = $ROOT . '/data/breaking.json';
if (file_exists($brkFile)) {
    $tickers = json_decode(file_get_contents($brkFile), true) ?? [];
    $checkB = $pdo->query("SELECT COUNT(*) FROM breaking_news")->fetchColumn();
    if ($checkB == 0) {
        $stmtB = $pdo->prepare("INSERT INTO breaking_news (ticker,sort_order) VALUES (?,?)");
        foreach ($tickers as $i => $t) { $stmtB->execute([$t, $i]); }
        log_step("Breaking news migrated (" . count($tickers) . ")");
    } else {
        log_step("Breaking news already present — skipped");
    }
}

// ── Migrate articles ────────────────────────────────────────
$artFile  = $ROOT . '/data/articles.json';
if (file_exists($artFile)) {
    $articles = json_decode(file_get_contents($artFile), true) ?? [];
    if (!empty($articles)) {
        $checkA = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
        if ($checkA == 0) {
            $superAdminId = $pdo->query("SELECT id FROM users WHERE role='super_admin' LIMIT 1")->fetchColumn();
            $catMap = [];
            foreach ($pdo->query("SELECT id,name FROM categories") as $r) {
                $catMap[strtolower($r['name'])] = $r['id'];
            }
            $stmtA = $pdo->prepare("INSERT INTO articles
                (id,title,excerpt,content,image,category_id,author_id,date,read_time,featured,trending,views,comment_count)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            foreach ($articles as $a) {
                $catId = $catMap[strtolower($a['category'] ?? '')] ?? null;
                $stmtA->execute([
                    (int)($a['id'] ?? 0), $a['title']??'', $a['excerpt']??'',
                    $a['content']??'', $a['image']??'',
                    $catId, $superAdminId,
                    $a['date']??date('M j, Y'), $a['readTime']??'5 min read',
                    !empty($a['featured'])?1:0, !empty($a['trending'])?1:0,
                    (int)($a['views']??0), (int)($a['comments']??0),
                ]);
            }
            log_step(count($articles) . " articles migrated");
        } else {
            log_step("Articles already present — skipped");
        }
    } else {
        log_step("No articles to migrate (JSON is empty)");
    }
}

// ── Done ────────────────────────────────────────────────────
echo "<hr style='margin:24px 0'>";
echo "<h2 style='font-family:monospace;color:green'>✅ Installation complete!</h2>";
echo "<p style='font-family:monospace'>Default admin login: <b>admin@trendsettersnews.com</b> / <b>admin123</b><br>
      <b>Next:</b> Delete or protect <code>setup/install.php</code> in production.</p>";
echo "<p style='font-family:monospace'><a href='../ts-manager/'>→ Go to Admin Panel</a> &nbsp;|&nbsp; <a href='../'>→ Go to Site</a></p>";
