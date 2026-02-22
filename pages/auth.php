<?php
require_once __DIR__ . '/../data_articles.php';
require_once __DIR__ . '/../ts-manager/helpers.php';

$error = '';
$isRegister = isset($_GET['reg']) || isset($_POST['register']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo   = get_pdo();
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (isset($_POST['register'])) {
        if (empty($name) || empty($email) || strlen($pass) < 6) {
            $_SESSION['flash_error'] = 'Please fill all fields. Password must be min. 6 chars.';
            header('Location: ' . $_SERVER['HTTP_REFERER']); exit;
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, 'user', 'active')");
                $stmt->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
                $_SESSION['site_user_id'] = $pdo->lastInsertId();
                $_SESSION['site_user_name'] = $name;
                $_SESSION['flash_success'] = "Welcome to the family, $name!";
                header('Location: ' . ($base ?: '/')); exit;
            } catch (PDOException $e) { 
                $_SESSION['flash_error'] = 'Email already registered or system error.'; 
                header('Location: ' . $_SERVER['HTTP_REFERER']); exit;
            }
        }
    } else {
        // Login Logic
        $stmt = $pdo->prepare("SELECT id, name, password_hash FROM users WHERE email=? AND role='user' AND status='active' LIMIT 1");
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        if ($row && password_verify($pass, $row['password_hash'])) {
            $_SESSION['site_user_id']   = $row['id'];
            $_SESSION['site_user_name'] = $row['name'];
            $_SESSION['flash_success'] = "Welcome back, " . explode(' ', $row['name'])[0] . "!";
            header('Location: ' . ($base ?: '/')); exit;
        } else { 
            $_SESSION['flash_error'] = 'Invalid email or password.';
            header('Location: ' . $_SERVER['HTTP_REFERER']); exit;
        }
    }
}

// If we reached here via GET but it was supposed to be a modal, we can still show the page as fallback
?>

<div class="container py-20 min-h-[60vh] flex items-center justify-center">
  <div class="bg-white rounded-3xl p-10 shadow-xl shadow-slate-100 border border-slate-100 w-full max-w-md">
    <div class="text-center mb-8">
      <h1 class="font-display text-3xl font-bold text-slate-900"><?= $isRegister ? 'Create Account' : 'Welcome Back' ?></h1>
      <p class="text-slate-500 mt-2"><?= $isRegister ? 'Join TrendSetters News today' : 'Sign in to engage with stories' ?></p>
    </div>

    <?php if ($error): ?><div class="mb-6 rounded-2xl bg-red-50 border border-red-200 px-5 py-3 text-sm font-medium text-red-700">⚠️ <?= $error ?></div><?php endif; ?>

    <form method="POST" class="space-y-5">
      <?php if ($isRegister): ?>
      <div>
        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Full Name</label>
        <input type="text" name="name" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10" placeholder="David Okilo"/>
      </div>
      <?php endif; ?>
      <div>
        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Email Address</label>
        <input type="email" name="email" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10" placeholder="your@email.com"/>
      </div>
      <div>
        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Password</label>
        <input type="password" name="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/10" placeholder="••••••••"/>
      </div>

      <button type="submit" name="<?= $isRegister ? 'register' : 'login' ?>" class="w-full rounded-2xl bg-primary px-6 py-5 text-sm font-bold text-white shadow-lg shadow-primary/20 transition hover:bg-primary/90 hover:shadow-xl active:scale-[0.98]">
        <?= $isRegister ? 'Sign Up' : 'Sign In' ?>
      </button>
    </form>

    <div class="mt-8 pt-8 border-t border-slate-100 text-center">
      <p class="text-xs font-semibold text-slate-400">
        <?= $isRegister ? 'Already have an account?' : 'Don\'t have an account?' ?>
        <a href="?<?= $isRegister ? '' : 'reg=1' ?>" class="text-primary hover:underline ml-1">
          <?= $isRegister ? 'Sign In' : 'Sign Up Free' ?>
        </a>
      </p>
    </div>
  </div>
</div>
