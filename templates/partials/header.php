<?php
// Use database categories dynamically. 
// We'll show the first 7 in the main nav, others in 'More'
$pinned = array_slice($categories, 0, 7);
$otherCats = array_slice($categories, 7);
?>
<header class="sticky top-0 z-50 glass border-b border-border/40">
  <div class="container h-16 flex items-center justify-between">
    <a href="<?= $base ?>/" class="font-display text-2xl font-bold tracking-tight text-foreground group">
      TrendSetters<span class="text-primary transition-colors group-hover:text-primary/80">News</span>
    </a>

    <!-- Desktop Nav (Legit Style Roof) -->
    <nav class="hidden items-center gap-4 lg:flex xl:gap-6">
      <a href="<?= $base ?>/" class="text-xs font-bold uppercase tracking-widest text-foreground hover:text-primary transition-colors">Home</a>
      
      <!-- Show only 5 items on LG, then full on XL -->
      <?php foreach ($pinned as $i => $cat): 
        $slug = strtolower(str_replace([' & ', ' '], ['-', '-'], $cat)); ?>
        <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="text-[10px] xl:text-xs font-bold uppercase tracking-widest text-foreground/70 hover:text-primary transition-colors <?php echo $i >= 5 ? 'hidden xl:block' : ''; ?>"><?php echo htmlspecialchars($cat); ?></a>
      <?php endforeach; ?>
      
      <?php 
        // Logic for 'More' dropdown
        $visibleOnLg = array_slice($pinned, 0, 5);
        $moreForLg = array_merge(array_slice($pinned, 5), $otherCats);
        $moreForXl = $otherCats;
      ?>
      
      <div class="relative group">
        <button class="text-[10px] xl:text-xs font-bold uppercase tracking-widest text-foreground/70 hover:text-primary flex items-center gap-1">
          More <span>▾</span>
        </button>
        <!-- Dropdown content -->
        <div class="absolute right-0 top-full mt-2 w-48 rounded-2xl bg-white border border-border shadow-xl opacity-0 translate-y-2 invisible group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible transition-all p-2 z-[60]">
          <!-- Items hidden on LG should appear here for LG users -->
          <div class="xl:hidden">
            <?php foreach ($pinned as $i => $cat): 
              if ($i < 5) continue; // Already visible
              $slug = strtolower(str_replace([' & ', ' '], ['-', '-'], $cat)); ?>
              <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="block px-4 py-2 text-[10px] font-bold text-foreground/70 hover:bg-secondary hover:text-primary rounded-xl transition-all"><?php echo htmlspecialchars($cat); ?></a>
            <?php endforeach; ?>
            <div class="my-1 border-t border-border/40"></div>
          </div>
          <!-- Global others -->
          <?php foreach ($otherCats as $cat): 
            $slug = strtolower(str_replace([' & ', ' '], ['-', '-'], $cat)); ?>
            <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="block px-4 py-2 text-[10px] xl:text-xs font-bold text-foreground/70 hover:bg-secondary hover:text-primary rounded-xl transition-all"><?php echo htmlspecialchars($cat); ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>

    <div class="flex items-center gap-3">
      <button id="search-toggle" class="rounded-full p-2.5 text-foreground/70 hover:bg-secondary transition-colors" title="Search">🔍</button>
      <div class="hidden h-6 w-px bg-border/60 md:block"></div>
      
      <?php if ($user = get_site_user()): ?>
        <div class="relative group">
          <button class="flex items-center gap-2 rounded-full hover:bg-secondary p-1 transition-colors">
            <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-[11px] font-bold text-white shadow-md">
              <?= initials($user['name']) ?>
            </div>
            <span class="text-sm font-bold text-foreground hidden xl:block"><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></span>
          </button>
          <div class="absolute right-0 top-full mt-2 w-48 rounded-2xl bg-white border border-border shadow-xl opacity-0 translate-y-2 invisible group-hover:opacity-100 group-hover:translate-y-0 group-hover:visible transition-all z-[60]">
            <div class="p-4 border-b border-border/40">
              <p class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Logged in as</p>
              <p class="text-xs font-bold text-foreground truncate"><?= htmlspecialchars($user['email']) ?></p>
            </div>
            <div class="p-2">
              <a href="<?= $base ?>/logout" class="flex items-center px-4 py-3 text-xs font-bold text-red-500 hover:bg-red-50 rounded-xl transition-colors">🚪 Logout</a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <button data-auth-trigger="login" class="hidden sm:flex items-center gap-2 rounded-full bg-primary px-6 py-2 text-xs font-bold text-primary-foreground hover:bg-primary/90 hover:shadow-lg transition-all active:scale-95">
          Sign In
        </button>
      <?php endif; ?>

      <button id="mobile-toggle" class="rounded-full p-2.5 text-foreground/70 hover:bg-secondary lg:hidden">☰</button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden border-t border-border bg-background lg:hidden max-h-[70vh] overflow-y-auto">
    <div class="container py-8 flex flex-col gap-6">
      <a href="<?= $base ?>/" class="text-sm font-bold uppercase tracking-widest">Home</a>
      <div class="grid grid-cols-2 gap-4">
        <?php foreach ($categories as $cat): 
          $slug = strtolower(str_replace([' & ', ' '], ['-', '-'], $cat)); ?>
          <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="text-sm font-bold text-muted-foreground hover:text-primary transition-colors"><?php echo htmlspecialchars($cat); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</header>