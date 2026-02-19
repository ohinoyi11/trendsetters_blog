<aside class="space-y-8">
  <div class="rounded-3xl bg-card p-8 shadow-sm border border-border/50">
    <div class="mb-6 flex items-center gap-3">
      <div class="h-8 w-1 bg-primary rounded-full"></div>
      <h3 class="font-display text-lg font-bold text-foreground">Trending Now</h3>
    </div>
    <div class="space-y-1">
      <?php $trending = array_values(array_filter($articles, fn($a)=>!empty($a['trending']))); $trending = array_slice($trending,0,5); foreach ($trending as $i=>$t): ?>
        <a href="<?= $base ?>/article/<?php echo $t['id']; ?>" class="group flex gap-4 py-4 transition-all hover:bg-secondary/50 rounded-xl px-2">
          <span class="font-display text-3xl font-bold text-primary/10 transition-colors group-hover:text-primary/30"><?php echo str_pad($i+1,2,'0',STR_PAD_LEFT); ?></span>
          <div class="flex-1">
            <h4 class="text-sm font-bold leading-tight text-foreground group-hover:text-primary transition-colors"><?php echo htmlspecialchars($t['title']); ?></h4>
            <div class="mt-1 text-[10px] font-bold uppercase tracking-wider text-muted-foreground"><?php echo htmlspecialchars($t['date']); ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="rounded-3xl bg-primary p-8 text-primary-foreground shadow-lg shadow-primary/20 relative overflow-hidden">
    <div class="relative z-10">
      <h3 class="font-display text-xl font-bold">Stay Informed</h3>
      <p class="mt-2 text-sm text-primary-foreground/80 leading-relaxed">Get the world's most important stories delivered to your inbox every morning.</p>
      <div class="mt-6 flex flex-col gap-3">
        <input id="newsletter-email" type="email" placeholder="Email address" class="w-full rounded-2xl bg-white/10 border border-white/20 px-4 py-3 text-sm text-white placeholder:text-white/50 focus:outline-none focus:ring-2 focus:ring-white/30" />
        <button id="subscribe-btn" class="w-full rounded-2xl bg-white py-3 text-sm font-bold text-primary shadow-lg transition-transform hover:scale-[1.02] active:scale-[0.98]">Subscribe Now</button>
      </div>
    </div>
    <!-- Decorative element -->
    <div class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-white/5 blur-3xl"></div>
  </div>

  <div class="rounded-3xl bg-card p-8 shadow-sm border border-border/50">
    <h3 class="mb-6 font-display text-lg font-bold text-foreground">Explore Topics</h3>
    <div class="flex flex-wrap gap-2">
      <?php foreach ($categories as $cat): $slug = strtolower(str_replace(' & ', '-', $cat)); ?>
        <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="rounded-full border border-border bg-secondary/30 px-4 py-2 text-xs font-bold text-muted-foreground transition-all hover:border-primary hover:text-primary hover:bg-white"><?php echo htmlspecialchars($cat); ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</aside>
