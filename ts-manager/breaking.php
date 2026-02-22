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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Left Side: Add Ticker -->
  <div class="lg:col-span-1">
    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm sticky top-8">
      <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-10 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 text-lg">⚡</div>
        <h2 class="font-display text-lg font-bold text-gray-900">Live Ticker</h2>
      </div>
      <p class="text-xs text-gray-400 mb-6 leading-relaxed">Flash updates that scroll across the top of your homepage. Keep them short and punchy.</p>
      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 ml-1">Update Text</label>
          <textarea name="ticker" required placeholder="BREAKING: Major announcement from..." class="form-input text-sm h-24 py-3 leading-relaxed"></textarea>
        </div>
        <button type="submit" class="w-full btn-primary py-4 font-bold text-xs uppercase tracking-widest bg-red-600 hover:bg-red-700 shadow-red-200">+ Launch Update</button>
      </form>
    </div>
  </div>

  <!-- Right Side: Active Tickers -->
  <div class="lg:col-span-2">
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
      <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div>
          <h2 class="font-display font-bold text-gray-900">Current Broadcast</h2>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Live updates in rotation</p>
        </div>
        <div class="flex items-center gap-2">
          <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
          <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest">Live Now</span>
        </div>
      </div>
      
      <div class="divide-y divide-slate-50">
        <?php foreach ($breaking as $i => $b): ?>
        <div class="group flex items-center gap-6 px-8 py-6 hover:bg-slate-50/80 transition-all">
          <div class="flex flex-col items-center gap-1">
            <span class="text-[10px] font-bold text-gray-400 bg-slate-100 h-6 w-6 rounded flex items-center justify-center"><?= $i+1 ?></span>
            <?php if ($i > 0): ?>
              <a href="?up=<?= $b['id'] ?>" class="text-[10px] text-indigo-600 font-bold hover:underline" title="Move Up">UP</a>
            <?php endif; ?>
          </div>
          
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-700 leading-relaxed"><?= htmlspecialchars($b['ticker']) ?></p>
            <div class="mt-2 flex items-center gap-3">
              <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest">Status: Active</span>
              <span class="h-1 w-1 rounded-full bg-slate-200"></span>
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Visibility: Public</span>
            </div>
          </div>

          <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <a href="?del=<?= $b['id'] ?>" 
               onclick="return confirm('Remove this live update?')" 
               class="inline-flex items-center justify-center h-10 w-10 rounded-xl text-red-400 hover:bg-red-50 hover:text-red-500 transition-all active:scale-90"
               title="Remove Update">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <?php if (empty($breaking)): ?>
        <div class="p-20 text-center">
          <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-slate-50 text-2xl mb-4">📢</div>
          <p class="text-slate-400 font-medium italic">No active tickers. Post something to start the news wire!</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
