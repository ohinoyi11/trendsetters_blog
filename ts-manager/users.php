<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();

// Super Admin only
if (!is_super_admin()) { header('Location: index.php'); exit; }

$flash = $_GET['flash'] ?? '';
$error = $_GET['error'] ?? '';

// Handle Role Updates
if (isset($_GET['promote']) && isset($_GET['role'])) {
    $id = (int)$_GET['promote'];
    $role = $_GET['role'];
    if (in_array($role, ['user', 'correspondent', 'super_admin'])) {
        update_user_role($id, $role);
        header('Location: users.php?flash=role_updated'); exit;
    }
}

// Handle Deletion
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    if ($id == $admin['id']) {
        header('Location: users.php?error=cannot_delete_self'); exit;
    }
    delete_user($id);
    header('Location: users.php?flash=user_deleted'); exit;
}

$users = load_all_users();
$pageTitle  = 'Site Users';
$activePage = 'users';
include __DIR__ . '/layout.php';
?>

<div class="flex items-center justify-between mb-8">
  <h1 class="font-display text-2xl font-bold text-gray-900">Registered Users</h1>
  <div class="text-xs font-bold text-gray-400 uppercase tracking-widest bg-slate-50 px-4 py-2 rounded-xl">
    Total: <?= count($users) ?>
  </div>
</div>

<?php if ($flash === 'role_updated'): ?><div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-sm font-medium text-green-700">✅ User role updated successfully.</div><?php endif; ?>
<?php if ($flash === 'user_deleted'): ?><div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-3 text-sm font-medium text-green-700">✅ User removed from system.</div><?php endif; ?>
<?php if ($error === 'cannot_delete_self'): ?><div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-3 text-sm font-medium text-red-700">⚠️ Suicide is not an option. You cannot delete your own account.</div><?php endif; ?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm min-h-[400px]">
  <table class="w-full text-sm">
    <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-gray-400">
      <tr>
        <th class="px-6 py-4 text-left">User</th>
        <th class="px-6 py-4 text-left">Role</th>
        <th class="px-6 py-4 text-left">Joined</th>
        <th class="px-6 py-4 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
      <?php foreach ($users as $u): ?>
      <tr class="hover:bg-slate-50/50 transition">
        <td class="px-6 py-4">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400">
              <?= initials($u['name']) ?>
            </div>
            <div>
              <div class="font-semibold text-gray-800"><?= htmlspecialchars($u['name']) ?></div>
              <div class="text-[10px] text-gray-400"><?= htmlspecialchars($u['email']) ?></div>
            </div>
          </div>
        </td>
        <td class="px-6 py-4">
          <?php 
            $roleClass = 'bg-slate-100 text-slate-500';
            if ($u['role'] === 'super_admin') $roleClass = 'bg-indigo-100 text-indigo-700';
            if ($u['role'] === 'correspondent') $roleClass = 'bg-blue-100 text-blue-700';
          ?>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $roleClass ?>">
            <?= str_replace('_', ' ', $u['role']) ?>
          </span>
        </td>
        <td class="px-6 py-4 text-gray-400 text-xs">
          <?= date('M j, Y', strtotime($u['created_at'])) ?>
        </td>
        <td class="px-6 py-4 text-right overflow-visible">
          <div class="relative inline-block text-left group">
            <button class="btn-secondary py-1.5 text-xs">Manage ▿</button>
            <div class="absolute right-0 top-full mt-2 w-48 rounded-xl bg-white border border-slate-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 p-2 text-left">
              <p class="px-3 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Change Role To</p>
              <a href="?promote=<?= $u['id'] ?>&role=user" class="block px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-slate-50 rounded-lg">Reader (User)</a>
              <a href="?promote=<?= $u['id'] ?>&role=correspondent" class="block px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-slate-50 rounded-lg">Correspondent</a>
              <a href="?promote=<?= $u['id'] ?>&role=super_admin" class="block px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-slate-50 rounded-lg">Super Admin</a>
              <div class="my-1 border-t border-slate-50"></div>
              <a href="?del=<?= $u['id'] ?>" onclick="return confirm('Permanently remove this user?')" class="block px-3 py-2 text-xs font-bold text-red-500 hover:bg-red-50 rounded-lg">Delete User</a>
            </div>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
