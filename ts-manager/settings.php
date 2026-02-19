<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

// Settings is super_admin ONLY
if (!is_super_admin()) { header('Location: index.php'); exit; }

$settings = load_settings();
$admin    = current_admin();
$flash    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'site') {
        save_settting('site_name', trim($_POST['site_name']));
        save_setting('tagline',   trim($_POST['tagline']));
        save_setting('description', trim($_POST['description']));
        $flash = 'Site updated.';
    }

    if ($action === 'account') {
        $username = trim($_POST['admin_username']);
        $newPass  = !empty($_POST['new_password']) ? $_POST['new_password'] : null;
        update_admin_credentials($admin['id'], $username, $newPass);
        $flash = 'Account updated.';
    }
}

$pageTitle  = 'Settings';
$activePage = 'settings';
include __DIR__ . '/layout.php';
?>

<div class="max-w-2xl space-y-8">
  <?php if ($flash): ?><div class="rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-sm font-medium text-green-700">✅ <?= $flash ?></div><?php endif; ?>

  <div class="bg-white rounded-2xl border border-slate-100 p-7 shadow-sm">
    <h2 class="font-display text-lg font-bold text-gray-900 mb-5">Identity</h2>
    <form method="POST" class="space-y-5">
      <input type="hidden" name="action" value="site"/>
      <div><label class="form-label">Site Name</label><input type="text" name="site_name" class="form-input" value="<?= htmlspecialchars($settings['site_name']) ?>"/></div>
      <div><label class="form-label">Tagline</label><input type="text" name="tagline" class="form-input" value="<?= htmlspecialchars($settings['tagline']) ?>"/></div>
      <div><label class="form-label">Description</label><textarea name="description" class="form-textarea" rows="2"><?= htmlspecialchars($settings['description']) ?></textarea></div>
      <button type="submit" class="btn-primary">Save Site</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-100 p-7 shadow-sm">
    <h2 class="font-display text-lg font-bold text-gray-900 mb-5">Admin Account</h2>
    <form method="POST" class="space-y-5">
      <input type="hidden" name="action" value="account"/>
      <div><label class="form-label">Admin Username</label><input type="text" name="admin_username" class="form-input" value="<?= htmlspecialchars($admin['name']) ?>"/></div>
      <div><label class="form-label">New Password (leave empty to keep)</label><input type="password" name="new_password" class="form-input"/></div>
      <button type="submit" class="btn-primary text-indigo-600 bg-white border border-indigo-200 hover:bg-indigo-50">Update Account</button>
    </form>
  </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
