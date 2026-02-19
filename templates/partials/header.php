<header class="sticky top-0 z-50 glass">
  <div class="container flex h-16 items-center justify-between">
    <a href="<?= $base ?>/" class="font-display text-2xl font-bold tracking-tight text-foreground transition-colors hover:text-primary">
      TrendSetters<span class="text-primary">News</span>
    </a>

    <!-- Desktop Nav -->
    <nav class="hidden items-center gap-8 md:flex">
      <a href="<?= $base ?>/" class="text-sm font-medium text-foreground/70 transition-colors hover:text-primary">Home</a>
      <?php foreach (array_slice($categories, 0, 5) as $cat): 
        $slug = strtolower(str_replace(' & ', '-', $cat)); ?>
        <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="text-sm font-medium text-foreground/70 transition-colors hover:text-primary"><?php echo htmlspecialchars($cat); ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="flex items-center gap-4">
      <button id="search-toggle" class="rounded-full p-2 text-foreground/70 transition-colors hover:bg-secondary hover:text-primary">🔍</button>
      
      <div class="hidden h-8 w-px bg-border md:block"></div>

      <?php if ($user = get_site_user()): ?>
        <div class="relative group">
          <button class="flex items-center gap-2 rounded-full hover:bg-secondary p-1 pr-3 transition-colors">
            <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-[11px] font-bold text-white shadow-md">
              <?= initials($user['name']) ?>
            </div>
            <span class="text-sm font-bold text-foreground hidden lg:block"><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></span>
          </button>
          <div class="absolute right-0 top-full mt-2 w-48 rounded-2xl bg-white border border-slate-100 shadow-xl opacity-0 translate-y-2 invisible group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible transition-all z-[60]">
            <div class="p-4 border-b border-slate-50">
              <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Logged in as</p>
              <p class="text-xs font-bold text-slate-900 truncate"><?= htmlspecialchars($user['email']) ?></p>
            </div>
            <div class="p-2">
              <a href="<?= $base ?>/logout" class="flex items-center px-3 py-2 text-xs font-bold text-red-500 hover:bg-red-50 rounded-xl transition-colors">🚪 Logout</a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <a href="<?= $base ?>/login" class="hidden md:flex items-center gap-2 rounded-full bg-primary px-5 py-2 text-sm font-bold text-primary-foreground transition-all hover:bg-primary/90 hover:shadow-lg">
          Sign In
        </a>
      <?php endif; ?>

      <button id="mobile-toggle" class="rounded-full p-2 text-foreground/70 transition-colors hover:bg-secondary hover:text-primary md:hidden">☰</button>
    </div>
  </div>

  <!-- Mobile Menu (Expandable) -->
  <div id="mobile-menu" class="hidden border-t border-border bg-background md:hidden">
    <div class="container flex flex-col gap-4 py-6">
      <a href="<?= $base ?>/" class="text-lg font-bold">Home</a>
      <?php foreach ($categories as $cat): $slug = strtolower(str_replace(' & ', '-', $cat)); ?>
        <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="text-lg font-medium text-muted-foreground"><?php echo htmlspecialchars($cat); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</header>