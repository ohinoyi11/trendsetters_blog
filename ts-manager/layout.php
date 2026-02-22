<?php
// Admin shared layout — include at top of each admin page
// Usage: define $pageTitle, $activePage before including this file
// Then close the layout at the bottom of each page
if (!defined('ADMIN_LAYOUT')) define('ADMIN_LAYOUT', true);
$admin = current_admin();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — TrendSetters News Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: {
        fontFamily: { display: ['Outfit','sans-serif'], sans: ['Inter','sans-serif'] },
        colors: {
          primary: { DEFAULT: '#4f46e5', 50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 600: '#4f46e5', 700: '#4338ca' },
          sidebar: '#0f172a',
        }
      }}
    }
  </script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    #sidebar { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 260px; min-width: 260px; overflow: hidden; }
    .sidebar-collapsed #sidebar { width: 80px; min-width: 80px; }
    
    .sidebar-link { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:10px; font-size:14px; font-weight:500; color:#94a3b8; text-decoration:none; transition:all .2s; white-space: nowrap; }
    .sidebar-link:hover { background:rgba(255,255,255,.06); color:#f1f5f9; }
    .sidebar-link.active { background:#4f46e5; color:#fff; box-shadow:0 4px 12px rgba(79,70,229,.35); }
    
    .sidebar-collapsed .sidebar-text, .sidebar-collapsed .sidebar-header-text { opacity: 0; visibility: hidden; width: 0; pointer-events: none; }
    .sidebar-text { transition: opacity 0.2s ease; }
    
    .sidebar-collapsed .px-6 { padding-left: 1.25rem; padding-right: 1.25rem; }
    .sidebar-collapsed .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
    
    .stat-card { background:white; border-radius:16px; padding:24px; border:1px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:9999px; font-size:11px; font-weight:700; letter-spacing:.05em; text-transform:uppercase; }
    .badge-indigo { background:#eef2ff; color:#4f46e5; }
    .badge-green  { background:#f0fdf4; color:#16a34a; }
    .badge-amber  { background:#fffbeb; color:#d97706; }
    .badge-red    { background:#fef2f2; color:#dc2626; }
    .badge-gray   { background:#f8fafc; color:#64748b; }
    .btn-primary { display:inline-flex; align-items:center; gap:6px; padding:9px 20px; border-radius:10px; background:#4f46e5; color:#fff; font-size:13px; font-weight:700; border:none; cursor:pointer; text-decoration:none; transition:all .2s; }
    .btn-primary:hover { background:#4338ca; box-shadow:0 4px 12px rgba(79,70,229,.3); }
    .btn-secondary { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:10px; background:#f8fafc; color:#374151; font-size:13px; font-weight:600; border:1px solid #e5e7eb; cursor:pointer; text-decoration:none; transition:all .2s; }
    .btn-secondary:hover { background:#f1f5f9; }
    .btn-danger { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:8px; background:#fef2f2; color:#dc2626; font-size:12px; font-weight:700; border:1px solid #fecaca; cursor:pointer; text-decoration:none; transition:all .2s; }
    .btn-danger:hover { background:#fee2e2; }
    .table-row:hover { background:#f8fafc; }
    .form-label { display:block; font-size:12px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:#64748b; margin-bottom:6px; }
    .form-input { width:100%; border:1px solid #e2e8f0; border-radius:10px; padding:10px 14px; font-size:14px; font-family:inherit; outline:none; transition:all .2s; background:#fff; }
    .form-input:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); }
    .form-textarea { width:100%; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; font-size:14px; font-family:inherit; outline:none; resize:vertical; min-height:120px; transition:all .2s; }
    .form-textarea:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
      if (isCollapsed) document.body.classList.add('sidebar-collapsed');
    });
    function toggleSidebar() {
      document.body.classList.toggle('sidebar-collapsed');
      localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
    }
  </script>
</head>
<body class="bg-slate-50">
<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside id="sidebar" style="background:#0f172a;" class="flex flex-col">
    <div class="px-6 py-7 border-b border-white/5 overflow-hidden">
      <a href="../" class="font-display text-xl font-bold text-white flex items-center gap-3">
        <span class="text-2xl">🌍</span>
        <span class="sidebar-header-text">TrendSetters<span class="text-indigo-400">News</span></span>
      </a>
      <p class="mt-0.5 text-[10px] font-bold uppercase tracking-widest text-slate-500 sidebar-header-text">Admin Panel</p>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-2">
      <a href="index.php"       class="sidebar-link <?= ($activePage??'')==='dashboard' ? 'active' : '' ?>" title="Dashboard"><span>📊</span> <span class="sidebar-text">Dashboard</span></a>
      <a href="articles.php"    class="sidebar-link <?= ($activePage??'')==='articles'  ? 'active' : '' ?>" title="Articles"><span>📰</span> <span class="sidebar-text">Articles</span></a>
      <a href="categories.php"  class="sidebar-link <?= ($activePage??'')==='categories'? 'active' : '' ?>" title="Categories"><span>🏷️</span> <span class="sidebar-text">Categories</span></a>
      <a href="breaking.php"    class="sidebar-link <?= ($activePage??'')==='breaking'  ? 'active' : '' ?>" title="Breaking News"><span>📢</span> <span class="sidebar-text">Breaking News</span></a>
      
      <?php if (is_super_admin()): ?>
        <a href="correspondents.php" class="sidebar-link <?= ($activePage??'')==='correspondents' ? 'active' : '' ?>" title="Team Management"><span>👷</span> <span class="sidebar-text">Team Management</span></a>
        <a href="users.php" class="sidebar-link <?= ($activePage??'')==='users' ? 'active' : '' ?>" title="User Management"><span>👥</span> <span class="sidebar-text">User Management</span></a>
      <?php endif; ?>
      <a href="profile.php"     class="sidebar-link <?= ($activePage??'')==='profile'   ? 'active' : '' ?>" title="My Profile"><span>👤</span> <span class="sidebar-text">My Profile</span></a>
      <a href="settings.php"    class="sidebar-link <?= ($activePage??'')==='settings'  ? 'active' : '' ?>" title="Settings"><span>⚙️</span> <span class="sidebar-text">Settings</span></a>
    </nav>

    <div class="px-3 pb-6 space-y-2 border-t border-white/5 pt-6">
      <a href="../" target="_blank" class="sidebar-link" title="View Site"><span>🌐</span> <span class="sidebar-text">View Site</span></a>
      <a href="logout.php" class="sidebar-link" style="color:#f87171;" title="Logout"><span>🚪</span> <span class="sidebar-text">Logout</span></a>
    </div>
  </aside>

  <!-- Main area -->
  <div class="flex-1 flex flex-col overflow-hidden">
    <!-- Topbar -->
    <header class="bg-white border-b border-slate-100 px-8 py-4 flex items-center justify-between">
      <div class="flex items-center gap-6">
        <button onclick="toggleSidebar()" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-all shadow-sm border border-slate-100">
          <span class="text-lg">☰</span>
        </button>
        <h1 class="font-display text-xl font-bold text-gray-900" style="letter-spacing:-0.02em"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
      </div>
      
      <div class="flex items-center gap-4">
        <a href="profile.php" class="flex items-center gap-3 transition-all hover:bg-slate-50 p-1.5 rounded-xl border border-transparent hover:border-slate-100">
          <div class="text-right hidden sm:block">
            <p class="text-[11px] font-bold text-gray-900 leading-none mb-1 flex items-center justify-end"><?= htmlspecialchars($admin['name'] ?? 'Admin') ?><?= verified_badge() ?></p>
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none"><?= $admin['role'] === 'super_admin' ? 'Chief Editor' : 'Correspondent' ?></p>
          </div>
          <?php if ($admin['avatar']): ?>
            <img src="<?= '../' . $admin['avatar'] ?>" class="h-9 w-9 rounded-full object-cover shadow-sm ring-2 ring-white" alt="Avatar"/>
          <?php else: ?>
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-600 text-[11px] font-bold text-white shadow-sm ring-2 ring-white">
              <?= initials($admin['name']) ?>
            </div>
          <?php endif; ?>
        </a>
      </div>
    </header>

    <!-- Page content -->
    <main class="flex-1 overflow-auto p-8">
