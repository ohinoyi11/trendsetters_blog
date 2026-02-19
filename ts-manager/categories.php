<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

// Categories are now global, but management is super_admin only if you prefer
// Let's allow everyone to manage categories for now, but restrict if needed.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    add_category($_POST['name']);
    header('Location: categories.php'); exit;
}

if (isset($_GET['del'])) {
    delete_category((int)$_GET['del']);
    header('Location: categories.php'); exit;
}

$categories = load_categories_with_ids();
$pageTitle  = 'Categories';
$activePage = 'categories';
include __DIR__ . '/layout.php';
?>

<div class="max-w-2xl">
  <div class="bg-white rounded-2xl border border-slate-100 p-7 shadow-sm mb-8">
    <h2 class="font-display text-lg font-bold text-gray-900 mb-5">Add Category</h2>
    <form method="POST" class="flex gap-3">
      <input type="text" name="name" required placeholder="Technology, Life, etc." class="form-input flex-1"/>
      <button type="submit" class="btn-primary">+ Add</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
    <div class="px-6 py-5 border-b border-slate-100"><h2 class="font-display font-bold">Existing Categories</h2></div>
    <ul class="divide-y divide-slate-50">
      <?php foreach ($categories as $c): ?>
      <li class="flex items-center justify-between px-6 py-4">
        <span class="font-medium text-gray-700"><?= htmlspecialchars($c['name']) ?></span>
        <a href="?del=<?= $c['id'] ?>" onclick="return confirm('Delete this category?')" class="text-red-500 hover:scale-110 transition">✕</a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
