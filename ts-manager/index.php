<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();
$admin = current_admin();
$pdo   = get_pdo();

// Stats
$totalArticles  = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$featuredCount  = $pdo->query("SELECT COUNT(*) FROM articles WHERE featured=1")->fetchColumn();
$trendingCount  = $pdo->query("SELECT COUNT(*) FROM articles WHERE trending=1")->fetchColumn();
$totalComments  = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$breakingCount  = $pdo->query("SELECT COUNT(*) FROM breaking_news")->fetchColumn();
$correspondents = $pdo->query("SELECT COUNT(*) FROM users WHERE role='correspondent'")->fetchColumn();

// Recent articles (5)
$recent = $pdo->query(
    "SELECT a.id,a.title,a.featured,a.trending,a.views,a.created_at,c.name AS category,u.name AS author
     FROM articles a
     LEFT JOIN categories c ON a.category_id=c.id
     LEFT JOIN users u ON a.author_id=u.id
     ORDER BY a.id DESC LIMIT 5"
)->fetchAll();

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
include __DIR__ . '/layout.php';
?>

<?php if (isset($_GET['flash'])): ?>
  <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-sm font-medium text-green-700">
    ✅ <?= $_GET['flash']==='cleared' ? 'All articles cleared.' : 'Action completed.' ?>
  </div>
<?php endif; ?>

<!-- Stats grid -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
  <?php
  $stats = [
    ['📰','Articles',  $totalArticles, 'articles.php'],
    ['⭐','Featured',  $featuredCount, 'articles.php'],
    ['🔥','Trending',  $trendingCount, 'articles.php'],
    ['💬','Comments',  $totalComments, '#'],
    ['📢','Breaking',  $breakingCount, 'breaking.php'],
    ['✍️','Correspondents', $correspondents, is_super_admin()?'correspondents.php':'#'],
  ];
  foreach ($stats as [$icon,$label,$val,$link]):
  ?>
  <a href="<?= $link ?>" class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition group">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-3xl mb-2"><?= $icon ?></p>
        <p class="text-2xl font-display font-bold text-gray-900"><?= number_format((int)$val) ?></p>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mt-1"><?= $label ?></p>
      </div>
      <span class="text-gray-300 group-hover:text-primary transition text-xl">→</span>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<!-- Quick actions -->
<div class="flex flex-wrap gap-3 mb-8">
  <a href="article-edit.php" class="btn-primary">✏️ New Article</a>
  <a href="breaking.php" class="btn-secondary">📢 Manage Tickers</a>
  <?php if (is_super_admin()): ?>
  <a href="correspondents.php" class="btn-secondary">👤 Manage Correspondents</a>
  <a href="settings.php" class="btn-secondary">⚙️ Settings</a>
  <?php endif; ?>
</div>

<!-- Recent articles table -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
  <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
    <h2 class="font-display text-base font-bold text-gray-900">Recent Articles</h2>
    <a href="articles.php" class="text-xs font-bold text-primary hover:underline">View All →</a>
  </div>
  <?php if (empty($recent)): ?>
    <div class="px-6 py-14 text-center">
      <p class="text-3xl mb-3">📝</p>
      <p class="text-gray-400 font-medium text-sm mb-4">No articles yet.</p>
      <a href="article-edit.php" class="btn-primary text-sm">Write your first article →</a>
    </div>
  <?php else: ?>
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-gray-400">
        <tr>
          <th class="px-6 py-3 text-left">Title</th>
          <th class="px-6 py-3 text-left hidden md:table-cell">Category</th>
          <th class="px-6 py-3 text-left hidden md:table-cell">Author</th>
          <th class="px-6 py-3 text-left">Badges</th>
          <th class="px-6 py-3 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        <?php foreach ($recent as $a): ?>
        <tr class="hover:bg-slate-50/50 transition">
          <td class="px-6 py-4 font-semibold text-gray-800 max-w-[220px] truncate"><?= htmlspecialchars($a['title']) ?></td>
          <td class="px-6 py-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($a['category']??'—') ?></td>
          <td class="px-6 py-4 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($a['author']??'—') ?></td>
          <td class="px-6 py-4">
            <?php if ($a['featured']): ?><span class="inline-block text-xs font-bold bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full mr-1">⭐</span><?php endif; ?>
            <?php if ($a['trending']): ?><span class="inline-block text-xs font-bold bg-red-50 text-red-500 px-2 py-0.5 rounded-full">🔥</span><?php endif; ?>
          </td>
          <td class="px-6 py-4 text-right">
            <a href="article-edit.php?id=<?= $a['id'] ?>" class="btn-secondary py-1 text-xs mr-1">Edit</a>
            <a href="article-delete.php?id=<?= $a['id'] ?>" onclick="return confirm('Delete?')" class="btn-danger py-1 text-xs">Del</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
