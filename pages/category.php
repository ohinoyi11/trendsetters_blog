<?php
$slug = $_GET['slug'] ?? '';
$categoryName = null;
foreach ($categories as $c) {
  if (strtolower(str_replace(' & ', '-', $c)) === $slug) { $categoryName = $c; break; }
}
$filtered = $categoryName ? array_values(array_filter($articles, fn($a)=>$a['category'] === $categoryName)) : [];
?>

<div class="container py-12">
  <div class="mb-10 flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-muted-foreground">
    <a href="<?= $base ?>/" class="transition-colors hover:text-primary">Home</a>
    <span class="opacity-30">/</span>
    <span class="text-foreground"><?php echo htmlspecialchars($categoryName ?? $slug); ?></span>
  </div>

  <div class="mb-16">
    <h1 class="font-display text-4xl font-bold text-foreground md:text-5xl lg:text-6xl"><?php echo htmlspecialchars($categoryName ?? 'Category'); ?></h1>
    <p class="mt-4 max-w-2xl text-lg text-muted-foreground font-medium">Explore our curated collection of <?php echo htmlspecialchars(strtolower($categoryName ?? '')) ; ?> stories, expert insights, and the latest developments from across the continent.</p>
  </div>

  <div class="grid gap-16 lg:grid-cols-3">
    <div class="lg:col-span-2">
      <?php if (count($filtered) === 0): ?>
        <div class="rounded-3xl bg-secondary/30 p-12 text-center border border-dashed border-border">
          <p class="text-muted-foreground font-medium">No articles found in this category yet.</p>
        </div>
      <?php else: ?>
        <div class="grid gap-8 sm:grid-cols-2">
          <?php foreach ($filtered as $i => $article): $variant='default'; include __DIR__ . '/../templates/partials/article_card.php'; endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="hidden lg:block">
      <?php include __DIR__ . '/../templates/partials/trending_sidebar.php'; ?>
    </div>
  </div>
</div>