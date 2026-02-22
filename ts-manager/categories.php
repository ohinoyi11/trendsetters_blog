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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Left Side: Add Category form -->
  <div class="lg:col-span-1">
    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm sticky top-8">
      <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-lg">📁</div>
        <h2 class="font-display text-lg font-bold text-gray-900">New Category</h2>
      </div>
      <p class="text-xs text-gray-400 mb-6 leading-relaxed">Categories help organize your articles and make it easier for readers to find what they're looking for.</p>
      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Category Name</label>
          <input type="text" name="name" required placeholder="e.g. Technology" class="form-input text-base py-3.5"/>
        </div>
        <button type="submit" class="w-full btn-primary py-4 font-bold text-xs uppercase tracking-widest">+ Add Category</button>
      </form>
    </div>
  </div>

  <!-- Right Side: List of Categories -->
  <div class="lg:col-span-2">
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
      <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div>
          <h2 class="font-display font-bold text-gray-900">Desk Organization</h2>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Manage your news desks</p>
        </div>
        <span class="rounded-full bg-slate-100 px-4 py-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest"><?= count($categories) ?> Total</span>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 border-b border-slate-50">
              <th class="px-8 py-5">Name</th>
              <th class="px-8 py-5 text-center">Articles</th>
              <th class="px-8 py-5 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <?php foreach ($categories as $c): ?>
            <tr class="group hover:bg-slate-50/80 transition-colors">
              <td class="px-8 py-5">
                <div class="flex items-center gap-3">
                  <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">#</div>
                  <span class="font-bold text-gray-700"><?= htmlspecialchars($c['name']) ?></span>
                </div>
              </td>
              <td class="px-8 py-5 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold <?= $c['article_count'] > 0 ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-400' ?>">
                  <?= number_format($c['article_count']) ?> Articles
                </span>
              </td>
              <td class="px-8 py-5 text-right">
                <a href="?del=<?= $c['id'] ?>" 
                   onclick="return confirm('Delete this category? All articles in this category will become uncategorized.')" 
                   class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all active:scale-90"
                   title="Remove Category">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      
      <?php if (empty($categories)): ?>
        <div class="p-20 text-center">
          <p class="text-slate-400 font-medium italic">No categories found. Create one to get started!</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
