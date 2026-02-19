<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

// Super Admin only
if (!is_super_admin()) { header('Location: index.php'); exit; }

$error = '';
$flash = '';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $data = [
        'name'     => trim($_POST['name'] ?? ''),
        'email'    => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'bio'      => trim($_POST['bio'] ?? ''),
        'status'   => $_POST['status'] ?? 'active'
    ];
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        $error = 'Name, email, and password are required.';
    } elseif (create_correspondent($data)) {
        $flash = 'Correspondent added.';
    } else {
        $error = 'Failed to add correspondent (email may already be used).';
    }
}

// Handle Status Toggle
if (isset($_GET['toggle_status']) && isset($_GET['current'])) {
    $id = (int)$_GET['toggle_status'];
    $newStatus = $_GET['current'] === 'active' ? 'suspended' : 'active';
    get_pdo()->prepare("UPDATE users SET status=? WHERE id=? AND role='correspondent'")->execute([$newStatus, $id]);
    header('Location: correspondents.php'); exit;
}

// Handle Delete
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    delete_user($id);
    header('Location: correspondents.php?flash=deleted'); exit;
}

$correspondents = load_correspondents();
$pageTitle  = 'Correspondents';
$activePage = 'correspondents';
include __DIR__ . '/layout.php';
?>

<div class="flex items-center justify-between mb-8">
  <h1 class="font-display text-2xl font-bold text-gray-900">Correspondents</h1>
  <button onclick="document.getElementById('add-modal').classList.remove('hidden')" class="btn-primary">+ Add New</button>
</div>

<?php if ($flash): ?><div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-sm font-medium text-green-700">✅ <?= $flash ?></div><?php endif; ?>
<?php if ($error): ?><div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-3 text-sm font-medium text-red-700">⚠️ <?= $error ?></div><?php endif; ?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
  <table class="w-full">
    <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-gray-400">
      <tr>
        <th class="px-6 py-4 text-left">Name</th>
        <th class="px-6 py-4 text-left">Email</th>
        <th class="px-6 py-4 text-center">Articles</th>
        <th class="px-6 py-4 text-center">Status</th>
        <th class="px-6 py-4 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
      <?php if (empty($correspondents)): ?>
        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No correspondents added yet.</td></tr>
      <?php else: ?>
        <?php foreach ($correspondents as $c): ?>
        <tr class="hover:bg-slate-50/50 transition">
          <td class="px-6 py-4">
            <div class="font-semibold text-gray-800"><?= htmlspecialchars($c['name']) ?></div>
            <div class="text-[10px] text-gray-400 font-medium tracking-tight">Joined <?= date('M Y', strtotime($c['created_at'])) ?></div>
          </td>
          <td class="px-6 py-4 text-gray-500 font-medium"><?= htmlspecialchars($c['email']) ?></td>
          <td class="px-6 py-4 text-center font-bold text-gray-400"><?= number_format($c['article_count']) ?></td>
          <td class="px-6 py-4 text-center">
            <a href="?toggle_status=<?= $c['id'] ?>&current=<?= $c['status'] ?>" 
               class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase whitespace-nowrap <?= $c['status']==='active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
               <?= $c['status'] ?>
            </a>
          </td>
          <td class="px-6 py-4 text-right">
            <a href="?del=<?= $c['id'] ?>" onclick="return confirm('Remove account?')" class="btn-danger py-1 text-xs">✕ Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Add Modal -->
<div id="add-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
  <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl border border-slate-100">
    <h3 class="font-display text-xl font-bold text-gray-900 mb-6">New Correspondent</h3>
    <form method="POST" class="space-y-5">
      <input type="hidden" name="action" value="create"/>
      <div><label class="form-label text-xs">Full Name</label><input type="text" name="name" required class="form-input" placeholder="e.g. David Okilo"/></div>
      <div><label class="form-label text-xs">Email Address</label><input type="email" name="email" required class="form-input" placeholder="david@trendsettersnews.com"/></div>
      <div><label class="form-label text-xs">Initial Password</label><input type="password" name="password" required class="form-input" placeholder="••••••••"/></div>
      <div class="flex gap-4 pt-4">
        <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="btn-secondary flex-1">Cancel</button>
        <button type="submit" class="btn-primary flex-1">Create User</button>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
