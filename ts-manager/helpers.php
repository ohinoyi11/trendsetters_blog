<?php
/**
 * Admin helpers — TrendSetters News
 * All data access now goes through MySQL (PDO).
 */

require_once dirname(__DIR__) . '/config/db.php';

// ── Session ──────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();

function admin_auth_guard(): void {
    if (empty($_SESSION['admin_user_id'])) {
        header('Location: login.php'); exit;
    }
}

function is_super_admin(): bool {
    return ($_SESSION['admin_role'] ?? '') === 'super_admin';
}

function current_admin(): array {
    return [
        'id'     => $_SESSION['admin_user_id'] ?? 0,
        'name'   => $_SESSION['admin_name']    ?? 'Admin',
        'role'   => $_SESSION['admin_role']    ?? '',
        'avatar' => $_SESSION['admin_avatar']  ?? null,
    ];
}

function admin_verify_login(string $username, string $password): bool {
    $pdo = get_pdo();
    // username can be email OR name
    $stmt = $pdo->prepare(
        "SELECT id, name, password_hash, role, status, avatar
         FROM users
         WHERE (email=? OR name=?) AND role IN ('super_admin','correspondent')
         LIMIT 1"
    );
    $stmt->execute([$username, $username]);
    $row = $stmt->fetch();
    if (!$row || $row['status'] !== 'active') return false;
    if (!password_verify($password, $row['password_hash'])) return false;
    $_SESSION['admin_user_id'] = $row['id'];
    $_SESSION['admin_name']    = $row['name'];
    $_SESSION['admin_role']    = $row['role'];
    $_SESSION['admin_avatar']  = $row['avatar'];
    return true;
}

// ── Articles ─────────────────────────────────────────────────
function load_articles(?int $author_id = null, ?string $status = null): array {
    $pdo = get_pdo();
    $sql = "SELECT a.*, c.name AS category, u.name AS author_name
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN users u ON a.author_id = u.id";
    $params = [];
    $where = [];

    if ($author_id !== null) {
        $where[] = "a.author_id=?";
        $params[] = $author_id;
    }
    if ($status !== null) {
        $where[] = "a.status=?";
        $params[] = $status;
    }

    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY a.id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_article(int $id): ?array {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare(
        "SELECT a.*, c.name AS category, u.name AS author_name, u.avatar AS author_avatar, u.bio AS author_bio
         FROM articles a
         LEFT JOIN categories c ON a.category_id = c.id
         LEFT JOIN users u ON a.author_id = u.id
         WHERE a.id=? LIMIT 1"
    );
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function save_article(array $data, bool $isEdit): int {
    $pdo = get_pdo();
    $catId = get_or_create_category($data['category'] ?? '');
    
    // Default status logic: Correspondents go to 'pending_approval', Admins to 'published'
    // Unless status is explicitly provided (e.g. from an approval action)
    if (!isset($data['status'])) {
        $admin = current_admin();
        $data['status'] = ($admin['role'] === 'super_admin') ? 'published' : 'pending_approval';
    }

    if ($isEdit) {
        $pdo->prepare("UPDATE articles SET title=?,excerpt=?,content=?,image=?,category_id=?,author_id=?,
                       date=?,read_time=?,featured=?,trending=?,views=?,comment_count=?,status=? WHERE id=?")
            ->execute([
                $data['title'], $data['excerpt'], $data['content'], $data['image'],
                $catId, $data['author_id'], $data['date'], $data['read_time'],
                $data['featured']?1:0, $data['trending']?1:0, $data['views'], $data['comment_count'],
                $data['status'], $data['id']
            ]);
        return (int)$data['id'];
    } else {
        $pdo->prepare("INSERT INTO articles
            (title,excerpt,content,image,category_id,author_id,date,read_time,featured,trending,views,comment_count,status)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)")
            ->execute([
                $data['title'], $data['excerpt'], $data['content'], $data['image'],
                $catId, $data['author_id'], $data['date'], $data['read_time'],
                $data['featured']?1:0, $data['trending']?1:0, $data['views'], $data['comment_count'],
                $data['status']
            ]);
        return (int)$pdo->lastInsertId();
    }
}

function update_article_status(int $id, string $status): void {
    get_pdo()->prepare("UPDATE articles SET status=? WHERE id=?")->execute([$status, $id]);
}

function delete_article(int $id): void {
    get_pdo()->prepare("DELETE FROM articles WHERE id=?")->execute([$id]);
}

// ── Categories ───────────────────────────────────────────────
function load_categories(): array {
    return array_column(get_pdo()->query("SELECT id,name FROM categories ORDER BY name")->fetchAll(), 'name');
}

function load_categories_with_ids(): array {
    return get_pdo()->query(
        "SELECT c.id, c.name, COUNT(a.id) as article_count 
         FROM categories c 
         LEFT JOIN articles a ON c.id = a.category_id 
         GROUP BY c.id 
         ORDER BY c.name"
    )->fetchAll();
}

function get_or_create_category(string $name): ?int {
    if (empty(trim($name))) return null;
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name=? LIMIT 1");
    $stmt->execute([trim($name)]);
    $row = $stmt->fetch();
    if ($row) return (int)$row['id'];
    $pdo->prepare("INSERT INTO categories (name) VALUES (?)")->execute([trim($name)]);
    return (int)$pdo->lastInsertId();
}

function add_category(string $name): bool {
    try {
        get_pdo()->prepare("INSERT IGNORE INTO categories (name) VALUES (?)")->execute([trim($name)]);
        return true;
    } catch (PDOException) { return false; }
}

function delete_category(int $id): void {
    get_pdo()->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
}

// ── Breaking news ─────────────────────────────────────────────
function load_breaking(): array {
    return array_column(
        get_pdo()->query("SELECT id,ticker FROM breaking_news ORDER BY sort_order,id")->fetchAll(),
        'ticker', 'id'
    );
}

function load_breaking_rows(): array {
    return get_pdo()->query("SELECT id,ticker,sort_order FROM breaking_news ORDER BY sort_order,id")->fetchAll();
}

function add_breaking(string $ticker): void {
    $max = get_pdo()->query("SELECT COALESCE(MAX(sort_order),0)+1 FROM breaking_news")->fetchColumn();
    get_pdo()->prepare("INSERT INTO breaking_news (ticker,sort_order) VALUES (?,?)")->execute([$ticker, $max]);
}

function delete_breaking(int $id): void {
    get_pdo()->prepare("DELETE FROM breaking_news WHERE id=?")->execute([$id]);
}

function move_breaking_up(int $id): void {
    $pdo   = get_pdo();
    $rows  = $pdo->query("SELECT id,sort_order FROM breaking_news ORDER BY sort_order,id")->fetchAll();
    $ids   = array_column($rows, 'id');
    $idx   = array_search($id, $ids);
    if ($idx !== false && $idx > 0) {
        $prevId  = $ids[$idx - 1];
        $thisOrd = $rows[$idx]['sort_order'];
        $prevOrd = $rows[$idx - 1]['sort_order'];
        $pdo->prepare("UPDATE breaking_news SET sort_order=? WHERE id=?")->execute([$prevOrd, $id]);
        $pdo->prepare("UPDATE breaking_news SET sort_order=? WHERE id=?")->execute([$thisOrd, $prevId]);
    }
}

// ── Settings ─────────────────────────────────────────────────
function load_settings(): array {
    $rows = get_pdo()->query("SELECT `key`,`value` FROM settings")->fetchAll();
    $out  = ['site_name'=>'TrendSetters News','tagline'=>'Your daily dose of trending stories','description'=>''];
    foreach ($rows as $r) { $out[$r['key']] = $r['value']; }
    return $out;
}

function save_setting(string $key, string $value): void {
    get_pdo()->prepare("INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=?")
             ->execute([$key, $value, $value]);
}

function save_settings(array $data): void {
    foreach ($data as $k => $v) {
        if (!in_array($k, ['admin_username','admin_password_hash'])) {
            save_setting($k, (string)$v);
        }
    }
}

// ── Correspondents / Users ────────────────────────────────────
function load_all_users(): array {
    return get_pdo()->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
}

function load_correspondents(): array {
    return get_pdo()->query(
        "SELECT u.*, COUNT(a.id) AS article_count
         FROM users u
         LEFT JOIN articles a ON a.author_id = u.id
         WHERE u.role='correspondent'
         GROUP BY u.id ORDER BY u.created_at DESC"
    )->fetchAll();
}

function get_user_by_id(int $id): ?array {
    $stmt = get_pdo()->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function create_correspondent(array $data): bool {
    try {
        $role = $data['role'] ?? 'correspondent';
        get_pdo()->prepare(
            "INSERT INTO users (name,email,password_hash,role,bio,avatar,status) VALUES (?,?,?,'$role',?,?,?)"
        )->execute([
            $data['name'], $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['bio'] ?? '', $data['avatar'] ?? null, $data['status'] ?? 'active',
        ]);
        return true;
    } catch (PDOException) { return false; }
}

function update_user_profile(int $id, array $data): void {
    get_pdo()->prepare("UPDATE users SET name=?, bio=?, avatar=? WHERE id=?")
             ->execute([$data['name'], $data['bio'] ?? '', $data['avatar'] ?? null, $id]);
}

function update_user_role(int $id, string $role): void {
    get_pdo()->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role, $id]);
}

function update_correspondent(int $id, array $data): void {
    get_pdo()->prepare("UPDATE users SET name=?,email=?,bio=?,status=? WHERE id=?")
             ->execute([$data['name'], $data['email'], $data['bio']??'', $data['status']??'active', $id]);
}

function reset_correspondent_password(int $id, string $newPassword): void {
    get_pdo()->prepare("UPDATE users SET password_hash=? WHERE id=?")
             ->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
}

function delete_user(int $id): void {
    get_pdo()->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
}

// ── Admin account management ──────────────────────────────────
function update_admin_credentials(int $userId, string $newUsername, ?string $newPassword): void {
    $pdo = get_pdo();
    $pdo->prepare("UPDATE users SET name=? WHERE id=?")->execute([$newUsername, $userId]);
    if ($newPassword) {
        $pdo->prepare("UPDATE users SET password_hash=? WHERE id=?")
            ->execute([password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
    }
    $_SESSION['admin_name'] = $newUsername;
}

// ── Image upload ─────────────────────────────────────────────
function handle_image_upload(string $fieldName): ?string {
    if (empty($_FILES[$fieldName]['tmp_name'])) return null;
    $f    = $_FILES[$fieldName];
    $ext  = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];
    if (!in_array($ext, $allowed) || $f['size'] > 5 * 1024 * 1024) return null;
    $dir  = dirname(__DIR__) . '/assets/uploads/';
    if (!is_dir($dir)) mkdir($dir, 0775, true);
    $name = uniqid('img_', true) . '.' . $ext;
    if (move_uploaded_file($f['tmp_name'], $dir . $name)) {
        return 'assets/uploads/' . $name;
    }
    return null;
}

// ── Public user helpers ───────────────────────────────────────
function get_site_user(): ?array {
    if (empty($_SESSION['site_user_id'])) return null;
    $stmt = get_pdo()->prepare("SELECT id,name,email,status FROM users WHERE id=? AND role='user' LIMIT 1");
    $stmt->execute([$_SESSION['site_user_id']]);
    return $stmt->fetch() ?: null;
}

function site_user_logged_in(): bool {
    return !empty($_SESSION['site_user_id']);
}

function initials(string $name): string {
    $words = array_filter(explode(' ', trim($name)));
    if (count($words) >= 2) return strtoupper(mb_substr($words[0],0,1) . mb_substr(end($words),0,1));
    return strtoupper(mb_substr($name,0,min(2,mb_strlen($name))));
}

function verified_badge(): string {
    return '<svg class="inline-block w-4 h-4 text-blue-500 fill-current ml-1 mb-0.5" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>';
}
