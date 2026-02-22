<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

// Super Admin only
if (!is_super_admin()) { header('Location: index.php'); exit; }

$error = '';
$flash = '';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $avatarPath = handle_image_upload('avatar');
    
    $data = [
        'name'     => trim($_POST['name'] ?? ''),
        'email'    => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'role'     => $_POST['role'] ?? 'correspondent',
        'bio'      => trim($_POST['bio'] ?? ''),
        'avatar'   => $avatarPath,
        'status'   => $_POST['status'] ?? 'active'
    ];
    
    if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
        $error = 'Name, email, and password are required.';
    } elseif (!$avatarPath) {
        $error = 'A professional profile picture is compulsory for all correspondents.';
    } elseif (create_correspondent($data)) {
        $flash = 'Correspondent added with profile picture.';
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

$correspondents = get_pdo()->query(
    "SELECT u.*, COUNT(a.id) AS article_count
     FROM users u
     LEFT JOIN articles a ON a.author_id = u.id
     WHERE u.role IN ('super_admin', 'correspondent')
     GROUP BY u.id ORDER BY u.role, u.name"
)->fetchAll();

$pageTitle  = 'Team Management';
$activePage = 'correspondents';
include __DIR__ . '/layout.php';
?>

<div class="flex items-center justify-between mb-8">
  <h1 class="font-display text-2xl font-bold text-gray-900">Editorial Team</h1>
  <button onclick="document.getElementById('add-modal').classList.remove('hidden')" class="btn-primary">+ Add Team Member</button>
</div>

<?php if ($flash): ?><div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-sm font-medium text-green-700">✅ <?= $flash ?></div><?php endif; ?>
<?php if ($error): ?><div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-3 text-sm font-medium text-red-700">⚠️ <?= $error ?></div><?php endif; ?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
  <table class="w-full">
    <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-gray-400">
      <tr>
        <th class="px-6 py-4 text-left">Internal Name</th>
        <th class="px-6 py-4 text-left">Role</th>
        <th class="px-6 py-4 text-center">Stories</th>
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
            <div class="flex items-center gap-3">
              <img src="../<?= $c['avatar'] ?>" class="h-9 w-9 rounded-full object-cover ring-2 ring-slate-100"/>
              <div>
                <div class="font-semibold text-gray-800"><?= htmlspecialchars($c['name']) ?></div>
                <div class="text-[10px] text-gray-400"><?= htmlspecialchars($c['email']) ?></div>
              </div>
            </div>
          </td>
          <td class="px-6 py-4">
            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $c['role'] === 'super_admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-blue-100 text-blue-700' ?>">
               <?= str_replace('_', ' ', $c['role']) ?>
            </span>
          </td>
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
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="hidden" name="action" value="create"/>
      <div>
        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">Professional Avatar (Compulsory)</label>
        <div class="relative group">
          <input type="file" name="avatar" required accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all"/>
        </div>
        <p class="mt-2 text-[9px] text-muted-foreground italic font-medium">Headshots only. Max 5MB.</p>
      </div>
      <div>
        <label class="form-label text-xs font-bold text-gray-700">Role</label>
        <select name="role" class="form-input">
          <option value="correspondent">Correspondent</option>
          <option value="super_admin">Super Admin</option>
        </select>
      </div>
      <div><label class="form-label text-xs font-bold text-gray-700">Full Name</label><input type="text" name="name" required class="form-input" placeholder="e.g. David Okilo"/></div>
      <div><label class="form-label text-xs font-bold text-gray-700">Email Address</label><input type="email" name="email" required class="form-input" placeholder="david@trendsettersnews.com"/></div>
      <div><label class="form-label text-xs font-bold text-gray-700">Initial Password</label><input type="password" name="password" required class="form-input" placeholder="••••••••"/></div>
      <div><label class="form-label text-xs font-bold text-gray-700">Brief Bio</label><textarea name="bio" class="form-input h-20" placeholder="A short description of their beat..."></textarea></div>
      <div class="flex gap-4 pt-4">
        <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="btn-secondary flex-1 font-bold text-xs uppercase tracking-widest">Cancel</button>
        <button type="submit" class="btn-primary flex-1 font-bold text-xs uppercase tracking-widest">Create User</button>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
