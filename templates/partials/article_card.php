<?php
// Usage: set $article, optional $index, optional $variant = 'default'|'compact'|'horizontal', optional $extraClass
if (!isset($variant)) $variant = 'default';
if (!isset($index)) $index = 0;
if (!isset($extraClass)) $extraClass = '';
if (empty($article)) return;
?>

<?php if ($variant === 'compact'): ?>
  <a href="<?= $base ?>/article/<?php echo $article['id']; ?>" class="group flex gap-4 py-4 transition-all hover:bg-secondary/50 rounded-xl px-2 <?= $extraClass ?>">
    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl">
      <img src="<?= $base ?>/<?php echo $article['image']; ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy"/>
    </div>
    <div class="flex-1">
      <h4 class="line-clamp-2 text-sm font-bold leading-tight text-foreground group-hover:text-primary transition-colors"><?php echo htmlspecialchars($article['title']); ?></h4>
      <div class="mt-1 flex items-center gap-2 text-[10px] font-bold text-muted-foreground uppercase">
        <span><?php echo htmlspecialchars($article['date']); ?></span>
        <span class="opacity-50">•</span>
        <span class="flex items-center gap-1">👁️ <?php echo number_format($article['views'] ?? 0); ?></span>
      </div>
    </div>
  </a>

<?php elseif ($variant === 'horizontal'): ?>
  <a href="<?= $base ?>/article/<?php echo $article['id']; ?>" class="group flex flex-col sm:flex-row gap-6 rounded-2xl bg-card p-4 shadow-sm transition-all hover:shadow-premium border border-border/50 hover:border-primary/20 <?= $extraClass ?>">
    <div class="aspect-video w-full sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-xl">
      <img src="<?= $base ?>/<?php echo $article['image']; ?>" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy"/>
    </div>
    <div class="flex flex-1 flex-col justify-center py-1">
      <span class="mb-2 text-[10px] font-bold uppercase tracking-widest text-primary"><?php echo htmlspecialchars($article['category']); ?></span>
      <h3 class="line-clamp-2 font-display text-lg font-bold leading-tight text-foreground group-hover:text-primary transition-colors"><?php echo htmlspecialchars($article['title']); ?></h3>
      <div class="mt-3 flex items-center gap-3 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">
        <span class="text-primary flex items-center"><?php echo htmlspecialchars($article['author']['name']); ?><?= verified_badge() ?></span>
        <span class="h-1 w-1 rounded-full bg-border"></span>
        <span><?php echo htmlspecialchars($article['readTime']); ?></span>
        <span class="h-1 w-1 rounded-full bg-border"></span>
        <span class="flex items-center gap-1">👁️ <?php echo number_format($article['views'] ?? 0); ?></span>
      </div>
    </div>
  </a>

<?php else: ?>
  <a href="<?= $base ?>/article/<?php echo $article['id']; ?>" class="group block overflow-hidden rounded-2xl bg-card shadow-sm transition-all hover:shadow-premium border border-border/50 hover:border-primary/20 <?= $extraClass ?>">
    <div class="relative aspect-[16/10] overflow-hidden">
      <img src="<?= $base ?>/<?php echo $article['image']; ?>" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy"/>
      <div class="absolute top-4 left-4">
        <span class="rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-foreground backdrop-blur-md"><?php echo htmlspecialchars($article['category']); ?></span>
      </div>
    </div>
    <div class="p-6">
      <h3 class="line-clamp-2 font-display text-xl font-bold leading-tight text-foreground group-hover:text-primary transition-colors"><?php echo htmlspecialchars($article['title']); ?></h3>
      <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-muted-foreground"><?php echo htmlspecialchars($article['excerpt']); ?></p>
      <div class="mt-3">
        <span class="text-[10px] font-bold uppercase tracking-widest text-primary group-hover:underline">Read More →</span>
      </div>
      <div class="mt-6 flex items-center justify-between border-t border-border/40 pt-4">
        <div class="flex items-center gap-2">
          <?php if (!empty($article['author']['avatar'])): ?>
            <img src="<?= $base ?>/<?= $article['author']['avatar'] ?>" class="h-7 w-7 rounded-full object-cover shadow-sm ring-2 ring-white" alt="Avatar"/>
          <?php else: ?>
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-[10px] font-bold text-primary ring-2 ring-white">
                <?= initials($article['author']['name']) ?>
            </div>
          <?php endif; ?>
          <span class="text-xs font-bold text-foreground/80 flex items-center"><?php echo htmlspecialchars($article['author']['name']); ?><?= verified_badge() ?></span>
        </div>
        <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">
          <span class="flex items-center gap-1">👁️ <?php echo number_format($article['views'] ?? 0); ?></span>
          <span class="flex items-center gap-1">💬 <?php echo number_format($article['comments'] ?? 0); ?></span>
        </div>
      </div>
    </div>
  </a>
<?php endif; ?>
