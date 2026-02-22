<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

$admin      = current_admin();
$statusFilter = $_GET['status'] ?? null;
$articles   = load_articles(is_super_admin() ? null : $admin['id'], $statusFilter);
$categories = load_categories_with_ids();

// Handle Actions
if (isset($_GET['approve']) && is_super_admin()) {
    update_article_status((int)$_GET['approve'], 'published');
    header('Location: articles.php?flash=approved'); exit;
}

$pageTitle  = 'Manage Articles';
$activePage = 'articles';
include __DIR__ . '/layout.php';
?>

<div class="flex items-center justify-between mb-8">
  <div class="flex items-center gap-4">
    <h1 class="font-display text-2xl font-bold text-gray-900">Articles</h1>
    <div class="flex bg-slate-100 p-1 rounded-xl">
        <a href="articles.php" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition <?= $statusFilter === null ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">All</a>
        <a href="articles.php?status=pending_approval" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition <?= $statusFilter === 'pending_approval' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">Pending Review</a>
    </div>
  </div>
  <a href="article-edit.php" class="btn-primary">+ New Article</a>
</div>

<?php if (isset($_GET['flash']) && $_GET['flash'] === 'approved'): ?>
    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-sm font-medium text-green-700">✅ Article approved and published globally.</div>
<?php endif; ?>

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
            <?php 
              $statusBadge = 'bg-slate-100 text-slate-500';
              $statusText  = $a['status'];
              if ($a['status'] === 'published') { $statusBadge = 'bg-green-100 text-green-700'; }
              if ($a['status'] === 'pending_approval') { $statusBadge = 'bg-amber-100 text-amber-700'; $statusText = 'Pending Review'; }
              if ($a['status'] === 'rejected') { $statusBadge = 'bg-red-100 text-red-700'; }
            ?>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $statusBadge ?>">
              <?= $statusText ?>
            </span>
            <div class="mt-1 flex gap-1">
              <?php if ($a['featured']): ?><span class="inline-block text-[9px] font-bold text-amber-500">⭐ Featured</span><?php endif; ?>
              <?php if ($a['trending']): ?><span class="inline-block text-[9px] font-bold text-red-500">🔥 Trending</span><?php endif; ?>
            </div>
          </td>
          <td class="px-6 py-4 text-center">
            <div class="flex items-center justify-center gap-3 text-gray-400 text-xs font-medium">
              <span>👁️ <?= number_format($a['views']) ?></span>
              <span>💬 <?= number_format($a['comment_count']) ?></span>
            </div>
          </td>
          <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-2">
              <?php if (is_super_admin() && $a['status'] === 'pending_approval'): ?>
                <a href="?approve=<?= $a['id'] ?>" class="px-3 py-1.5 rounded-lg bg-green-600 text-white text-xs font-bold hover:bg-green-700 transition">Approve</a>
              <?php endif; ?>
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
