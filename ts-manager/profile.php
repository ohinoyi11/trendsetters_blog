<?php
require_once __DIR__ . '/helpers.php';
admin_auth_guard();
$admin = current_admin();
$pdo   = get_pdo();

$error = '';
$flash = '';

$user = get_user_by_id($admin['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $bio  = trim($_POST['bio'] ?? '');
    
    $avatarPath = $user['avatar'];
    $newAvatar  = handle_image_upload('avatar');
    if ($newAvatar) {
        $avatarPath = $newAvatar;
        $_SESSION['admin_avatar'] = $avatarPath;
    }
    
    if (empty($name)) {
        $error = 'Name is required.';
    } else {
        update_user_profile($admin['id'], [
            'name'   => $name,
            'bio'    => $bio,
            'avatar' => $avatarPath
        ]);
        $_SESSION['admin_name'] = $name;
        $flash = 'Profile updated successfully.';
        $user = get_user_by_id($admin['id']); // Refresh
    }
}

$pageTitle  = 'My Profile';
$activePage = 'profile';
include __DIR__ . '/layout.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-6 mb-10">
        <?php if ($user['avatar']): ?>
            <img src="<?= '../' . $user['avatar'] ?>" class="h-24 w-24 rounded-3xl object-cover shadow-xl ring-4 ring-white" alt="Avatar"/>
        <?php else: ?>
            <div class="flex h-24 w-24 items-center justify-center rounded-3xl bg-indigo-600 text-2xl font-bold text-white shadow-xl ring-4 ring-white">
                <?= initials($user['name']) ?>
            </div>
        <?php endif; ?>
        <div>
            <h1 class="font-display text-3xl font-bold text-gray-900"><?= htmlspecialchars($user['name']) ?></h1>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-500 mt-1"><?= $user['role'] === 'super_admin' ? 'Chief Editor' : 'Correspondent' ?></p>
        </div>
    </div>

    <?php if ($flash): ?><div class="mb-8 rounded-2xl bg-green-50 border border-green-200 px-6 py-4 text-sm font-medium text-green-700 flex items-center gap-3"><span>✅</span> <?= $flash ?></div><?php endif; ?>
    <?php if ($error): ?><div class="mb-8 rounded-2xl bg-red-50 border border-red-200 px-6 py-4 text-sm font-medium text-red-700 flex items-center gap-3"><span>⚠️</span> <?= $error ?></div><?php endif; ?>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
        <form method="POST" enctype="multipart/form-data" class="space-y-8">
            <div class="grid gap-8 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="form-label">Profile Picture</label>
                    <div class="mt-2 flex items-center gap-5">
                        <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer"/>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-400 italic">Recommended size: 500x500px. Max 5MB.</p>
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="form-input text-base py-3" placeholder="Your professional name"/>
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">Professional Bio</label>
                    <textarea name="bio" class="form-input h-32 text-sm leading-relaxed" placeholder="Tell your readers about your background, expertise, and the stories you cover..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50">
                <button type="submit" class="btn-primary px-10 py-3.5 text-sm uppercase tracking-widest">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/layout-footer.php'; ?>
