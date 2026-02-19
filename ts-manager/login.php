<?php
require_once __DIR__ . '/helpers.php';
if (!empty($_SESSION['admin_user_id'])) {
    header('Location: index.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if (admin_verify_login($u, $p)) {
        header('Location: index.php'); exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Admin Login — TrendSetters News</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: { extend: {
        fontFamily: { display: ['Outfit','sans-serif'], sans: ['Inter','sans-serif'] },
        colors: {
          primary: { DEFAULT: 'hsl(243 75% 59%)', foreground: 'hsl(210 40% 98%)', dark: 'hsl(243 75% 50%)' },
          surface: 'hsl(210 40% 98%)',
          card: 'hsl(0 0% 100%)',
          border: 'hsl(214 32% 91%)',
          muted: { DEFAULT: 'hsl(210 40% 94%)', foreground: 'hsl(215 16% 47%)' },
        }
      }}
    }
  </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-violet-50 flex items-center justify-center p-4">

<div class="w-full max-w-md">
  <div class="mb-10 text-center">
    <h1 class="font-display text-4xl font-bold tracking-tight text-gray-900">TrendSetters<span class="text-indigo-600">News</span></h1>
    <p class="mt-2 text-sm font-medium text-gray-500 uppercase tracking-widest">Admin Panel</p>
  </div>

  <div class="rounded-3xl bg-white p-10 shadow-xl shadow-indigo-100/50 border border-gray-100">
    <h2 class="font-display text-2xl font-bold text-gray-900 mb-1">Welcome back</h2>
    <p class="text-sm text-gray-500 mb-8">Sign in to manage your blog</p>

    <?php if ($error): ?>
      <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-medium text-red-700">
        ⚠️ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <div>
        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Username</label>
        <input type="text" name="username" autocomplete="username" required
               class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-3.5 text-sm font-medium text-gray-900 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="admin"/>
      </div>
      <div>
        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2">Password</label>
        <input type="password" name="password" autocomplete="current-password" required
               class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-3.5 text-sm font-medium text-gray-900 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100"
               placeholder="••••••••"/>
      </div>
      <button type="submit"
              class="w-full rounded-2xl bg-indigo-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 hover:shadow-indigo-300 active:scale-[0.98]">
        Sign In →
      </button>
    </form>

    <p class="mt-6 text-center text-xs text-gray-400">Default: <code class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">admin</code> / <code class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">admin123</code></p>
  </div>

  <a href="../" class="mt-6 block text-center text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition">← Back to Site</a>
</div>
</body>
</html>
