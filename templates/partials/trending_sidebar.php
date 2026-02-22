<aside class="space-y-8">
  <!-- Most Read Rankings -->
  <div class="rounded-3xl bg-card p-8 shadow-sm border border-border/50">
    <div class="mb-6 flex items-center gap-3">
      <div class="h-8 w-1 bg-primary rounded-full"></div>
      <h3 class="font-display text-lg font-bold text-foreground">Most Read</h3>
    </div>
    <div class="space-y-6">
      <?php 
        $mostRead = $articles; 
        usort($mostRead, fn($a, $b) => ($b['views'] ?? 0) <=> ($a['views'] ?? 0));
        $mostRead = array_slice($mostRead, 0, 4);
        foreach ($mostRead as $i => $t): 
      ?>
        <a href="<?= $base ?>/article/<?php echo $t['id']; ?>" class="group relative flex items-start gap-4 transition-all">
          <span class="rank-number absolute -left-4 -top-2 text-primary group-hover:opacity-20 transition-opacity"><?php echo $i + 1; ?></span>
          <div class="pl-8 flex-1">
            <h4 class="text-sm font-bold leading-tight text-foreground group-hover:text-primary transition-colors line-clamp-2"><?php echo htmlspecialchars($t['title']); ?></h4>
            <div class="mt-2 flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">
              <span class="px-1.5 py-0.5 rounded bg-secondary"><?php echo htmlspecialchars($t['category']); ?></span>
              <span><?php echo number_format($t['views'] ?? 0); ?> views</span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Newsletter -->
  <div class="rounded-3xl bg-primary p-8 text-primary-foreground shadow-lg shadow-primary/20 relative overflow-hidden">
    <div class="relative z-10">
      <h3 class="font-display text-xl font-bold">Stay Informed</h3>
      <p class="mt-2 text-sm text-primary-foreground/80 leading-relaxed">Top stories delivered to your inbox every morning.</p>
      <div class="mt-6 flex flex-col gap-3">
        <input id="newsletter-email" type="email" placeholder="Email" class="w-full rounded-2xl bg-white/10 border border-white/20 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-white/30" />
        <button id="subscribe-btn" class="w-full rounded-2xl bg-white py-3 text-sm font-bold text-primary shadow-lg transition-transform hover:scale-[1.02]">Subscribe</button>
      </div>
    </div>
    <div class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-white/5 blur-3xl"></div>
  </div>

  <!-- Our Correspondents -->
  <div class="rounded-3xl bg-card p-8 shadow-sm border border-border/50">
    <h3 class="mb-6 font-display text-lg font-bold text-foreground line-clamp-1">Our Correspondents</h3>
    <div class="grid grid-cols-4 gap-4">
      <?php 
        $authors = [];
        foreach($articles as $a) {
            if(!isset($authors[$a['author']['name']])) {
                $authors[$a['author']['name']] = $a['author'];
            }
        }
        $authors = array_slice(array_values($authors), 0, 8);
        foreach ($authors as $auth):
      ?>
        <div class="group relative" title="<?php echo htmlspecialchars($auth['name']); ?> (Verified)">
          <?php if (!empty($auth['avatar'])): ?>
            <img src="<?= $base ?>/<?= $auth['avatar'] ?>" class="aspect-square h-full w-full rounded-full object-cover shadow-sm ring-2 ring-white transition-transform group-hover:scale-110" alt="<?= htmlspecialchars($auth['name']) ?>"/>
          <?php else: ?>
            <div class="aspect-square rounded-full bg-secondary flex items-center justify-center text-xs font-bold text-primary transition-all group-hover:bg-primary group-hover:text-white border border-border/50">
                <?= initials($auth['name']) ?>
            </div>
          <?php endif; ?>
          <div class="absolute -right-1 -top-1 rounded-full bg-white p-0.5 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
            <?= verified_badge() ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Topics -->
  <div class="rounded-3xl bg-card p-8 shadow-sm border border-border/50">
    <h3 class="mb-6 font-display text-lg font-bold text-foreground">Explore Topics</h3>
    <div class="flex flex-wrap gap-2">
      <?php foreach ($categories as $cat): 
        $slug = strtolower(str_replace(' & ', '-', $cat));
        $count = count(array_filter($articles, fn($a) => $a['category'] === $cat));
      ?>
        <a href="<?= $base ?>/category/<?php echo $slug; ?>" class="rounded-xl border border-border bg-secondary/30 px-3 py-1.5 text-[10px] font-bold text-muted-foreground transition-all hover:border-primary hover:text-primary hover:bg-white">
          <?php echo htmlspecialchars($cat); ?> 
          <span class="ml-1 opacity-50"><?php echo $count; ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</aside>
