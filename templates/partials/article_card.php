<?php
// Usage: set $article, optional $index, optional $variant = 'default'|'compact'|'horizontal'
if (!isset($variant)) $variant = 'default';
if (!isset($index)) $index = 0;
if (empty($article)) return;
?>

<?php if ($variant === 'compact'): ?>
  <a href="<?= $base ?>/article/<?php echo $article['id']; ?>" class="group flex gap-4 py-4 transition-all hover:bg-secondary/50 rounded-xl px-2">
    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl">
      <img src="<?= $base ?>/<?php echo $article['image']; ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"/>
    </div>
    <div class="flex-1">
      <h4 class="line-clamp-2 text-sm font-bold leading-tight text-foreground group-hover:text-primary transition-colors"><?php echo htmlspecialchars($article['title']); ?></h4>
      <p class="mt-1 text-xs text-muted-foreground font-medium"><?php echo htmlspecialchars($article['date']); ?></p>
    </div>
  </a>

<?php elseif ($variant === 'horizontal'): ?>
  <a href="<?= $base ?>/article/<?php echo $article['id']; ?>" class="group flex flex-col sm:flex-row gap-6 rounded-2xl bg-card p-4 shadow-sm transition-all hover:shadow-premium border border-border/50 hover:border-primary/20">
    <div class="aspect-video w-full sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-xl">
      <img src="<?= $base ?>/<?php echo $article['image']; ?>" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"/>
    </div>
    <div class="flex flex-1 flex-col justify-center py-1">
      <span class="mb-2 text-[10px] font-bold uppercase tracking-widest text-primary"><?php echo htmlspecialchars($article['category']); ?></span>
      <h3 class="line-clamp-2 font-display text-lg font-bold leading-tight text-foreground group-hover:text-primary transition-colors"><?php echo htmlspecialchars($article['title']); ?></h3>
      <div class="mt-3 flex items-center gap-3 text-xs text-muted-foreground font-medium">
        <span><?php echo htmlspecialchars($article['author']['name']); ?></span>
        <span class="h-1 w-1 rounded-full bg-border"></span>
        <span><?php echo htmlspecialchars($article['readTime']); ?></span>
      </div>
    </div>
  </a>

<?php else: ?>
  <a href="<?= $base ?>/article/<?php echo $article['id']; ?>" class="group block overflow-hidden rounded-2xl bg-card shadow-sm transition-all hover:shadow-premium border border-border/50 hover:border-primary/20">
    <div class="relative aspect-[16/10] overflow-hidden">
      <img src="<?= $base ?>/<?php echo $article['image']; ?>" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"/>
      <div class="absolute top-4 left-4">
        <span class="rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-foreground backdrop-blur-md"><?php echo htmlspecialchars($article['category']); ?></span>
      </div>
    </div>
    <div class="p-6">
      <h3 class="line-clamp-2 font-display text-xl font-bold leading-tight text-foreground group-hover:text-primary transition-colors"><?php echo htmlspecialchars($article['title']); ?></h3>
      <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-muted-foreground"><?php echo htmlspecialchars($article['excerpt']); ?></p>
      <div class="mt-6 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-[10px] font-bold text-primary"><?php echo htmlspecialchars($article['author']['avatar']); ?></div>
          <span class="text-xs font-bold text-foreground/80"><?php echo htmlspecialchars($article['author']['name']); ?></span>
        </div>
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground"><?php echo htmlspecialchars($article['readTime']); ?></span>
      </div>
    </div>
  </a>
<?php endif; ?>
