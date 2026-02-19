<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ticker'])) {
    add_breaking(trim($_POST['ticker']));
    header('Location: breaking.php'); exit;
}

if (isset($_GET['del'])) {
    delete_breaking((int)$_GET['del']);
    header('Location: breaking.php'); exit;
}

if (isset($_GET['up'])) {
    move_breaking_up((int)$_GET['up']);
    header('Location: breaking.php'); exit;
}

$breaking = load_breaking_rows();
$pageTitle  = 'Breaking News';
$activePage = 'breaking';
include __DIR__ . '/layout.php';
?>

<div class="max-w-2xl space-y-8">
  <div class="bg-white rounded-2xl border border-slate-100 p-7 shadow-sm">
    <h2 class="font-display text-lg font-bold text-gray-900 mb-5">Add Ticker</h2>
    <form method="POST" class="flex gap-3">
      <input type="text" name="ticker" required placeholder="BREAKING: ..." class="form-input flex-1"/>
      <button type="submit" class="btn-primary">+ Add</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
      <h2 class="font-display font-bold">Live Tickers</h2>
      <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?= count($breaking) ?> Total</span>
    </div>
    <ul class="divide-y divide-slate-50">
      <?php foreach ($breaking as $i => $b): ?>
      <li class="flex items-center gap-4 px-6 py-4">
        <span class="text-[10px] font-bold text-red-500 bg-red-50 h-6 w-6 rounded flex items-center justify-center"><?= $i+1 ?></span>
        <span class="flex-1 font-medium text-gray-700"><?= htmlspecialchars($b['ticker']) ?></span>
        <div class="flex gap-2">
          <?php if ($i > 0): ?><a href="?up=<?= $b['id'] ?>" class="text-xs p-1 hover:bg-slate-100 rounded">↑</a><?php endif; ?>
          <a href="?del=<?= $b['id'] ?>" onclick="return confirm('Remove?')" class="text-xs text-red-500 font-bold p-1 hover:bg-red-50 rounded">✕</a>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
