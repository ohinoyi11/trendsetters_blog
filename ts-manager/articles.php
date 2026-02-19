<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

$admin      = current_admin();
$articles   = load_articles(is_super_admin() ? null : $admin['id']);
$categories = load_categories_with_ids();

$pageTitle  = 'Manage Articles';
$activePage = 'articles';
include __DIR__ . '/layout.php';
?>

<div class="flex items-center justify-between mb-8">
  <h1 class="font-display text-2xl font-bold text-gray-900">Articles</h1>
  <a href="article-edit.php" class="btn-primary">+ New Article</a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-gray-400">
      <tr>
        <th class="px-6 py-4 text-left">Title</th>
        <th class="px-6 py-4 text-left">Category</th>
        <?php if (is_super_admin()): ?>
          <th class="px-6 py-4 text-left">Author</th>
        <?php endif; ?>
        <th class="px-6 py-4 text-left">Status</th>
        <th class="px-6 py-4 text-center">Engagement</th>
        <th class="px-6 py-4 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
      <?php if (empty($articles)): ?>
        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No articles found.</td></tr>
      <?php else: ?>
        <?php foreach ($articles as $a): ?>
        <tr class="hover:bg-slate-50/50 transition">
          <td class="px-6 py-4">
            <div class="font-semibold text-gray-800"><?= htmlspecialchars($a['title']) ?></div>
            <div class="text-[10px] text-gray-400 mt-0.5"><?= $a['date'] ?></div>
          </td>
          <td class="px-6 py-4">
            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500">
               <?= htmlspecialchars($a['category'] ?? 'Uncategorized') ?>
            </span>
          </td>
          <?php if (is_super_admin()): ?>
            <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($a['author_name'] ?? '—') ?></td>
          <?php endif; ?>
          <td class="px-6 py-4">
            <?php if ($a['featured']): ?><span class="inline-block text-[10px] font-bold bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full mr-1">⭐ Featured</span><?php endif; ?>
            <?php if ($a['trending']): ?><span class="inline-block text-[10px] font-bold bg-red-50 text-red-500 px-2 py-0.5 rounded-full">🔥 Trending</span><?php endif; ?>
          </td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center gap-3 text-gray-400 text-xs font-medium">
              <span>👁️ <?= number_format($a['views']) ?></span>
              <span>💬 <?= number_format($a['comment_count']) ?></span>
            </div>
          </td>
          <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-2">
              <a href="article-edit.php?id=<?= $a['id'] ?>" class="btn-secondary py-1.5 text-xs">Edit</a>
              <a href="article-delete.php?id=<?= $a['id'] ?>" onclick="return confirm('Delete permanently?')" class="btn-danger py-1.5 text-xs">Del</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
