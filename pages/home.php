<?php
// Home page
$featured    = null;
$nonFeatured = [];
foreach ($articles as $a) {
  if (!empty($a['featured']) && $featured === null) { $featured = $a; }
  else { $nonFeatured[] = $a; }
}
$visible = array_slice($nonFeatured, 0, 6);
?>

<?php if (empty($articles)): ?>
<!-- Empty state — no articles published yet -->
<section class="container mt-20 pb-32 text-center">
  <div class="mx-auto max-w-lg">
    <p class="text-6xl mb-6">✍️</p>
    <h1 class="font-display text-4xl font-bold text-foreground mb-4">Welcome to <?= htmlspecialchars($siteName ?? 'TrendSetters News') ?></h1>
    <p class="text-lg text-muted-foreground mb-8">There are no articles published yet. Visit the admin panel to start writing your first story.</p>
    <a href="admin/" class="inline-flex items-center gap-2 rounded-full bg-primary px-8 py-4 font-display font-bold text-white shadow-lg shadow-primary/30 transition hover:bg-primary/90 hover:shadow-xl text-sm">
      Go to Admin Panel →
    </a>
  </div>
</section>
<?php else: ?>

<section class="container mt-12">
  <?php include __DIR__ . '/../templates/partials/featured_article.php'; ?>
</section>

<section class="container mt-20 pb-24">
  <div class="grid gap-16 lg:grid-cols-3">
    <div class="lg:col-span-2">
      <!-- Filters -->
      <div class="mb-10 flex flex-wrap items-center gap-3" id="filters">
        <?php $text='All Stories'; $class='rounded-full px-6 py-2.5 font-display text-xs font-bold uppercase tracking-widest bg-primary text-primary-foreground shadow-lg shadow-primary/20'; include __DIR__ . '/../templates/partials/ui/button.php'; ?>
        <?php foreach ($categories as $cat): $text = $cat; $class='filter-btn rounded-full px-6 py-2.5 font-display text-xs font-bold uppercase tracking-widest bg-secondary text-muted-foreground transition-all hover:bg-secondary/80 hover:text-foreground'; ?>
          <?php include __DIR__ . '/../templates/partials/ui/button.php'; ?>
        <?php endforeach; ?>
      </div>

      <?php if (!empty($nonFeatured)): ?>
      <div class="mb-16">
        <div class="mb-8 flex items-center gap-4">
          <h2 class="font-display text-2xl font-bold tracking-tight text-foreground">Editor's Picks</h2>
          <div class="h-px flex-1 bg-border/50"></div>
        </div>
        <div id="editors-picks" class="grid gap-2">
          <?php foreach (array_slice($nonFeatured, 0, 3) as $i => $article): $variant='compact'; include __DIR__ . '/../templates/partials/article_card.php'; endforeach; ?>
        </div>
      </div>

      <div class="mb-8 flex items-center justify-between">
        <h2 class="font-display text-2xl font-bold tracking-tight text-foreground">Latest Stories</h2>
      </div>
      <div id="latest-grid" class="grid gap-8 sm:grid-cols-2">
        <?php foreach ($visible as $i => $article): $variant='default'; include __DIR__ . '/../templates/partials/article_card.php'; endforeach; ?>
      </div>

      <?php if (count($nonFeatured) > 6): ?>
      <div class="mt-16 text-center">
        <button id="load-more" class="group inline-flex items-center gap-2 rounded-full border border-border bg-white px-10 py-4 font-display text-sm font-bold text-foreground transition-all hover:border-primary hover:text-primary hover:shadow-lg">
          Load More Stories
          <span class="transition-transform group-hover:translate-x-1">→</span>
        </button>
      </div>
      <?php endif; ?>
      <?php else: ?>
      <div class="text-center py-20">
        <p class="text-gray-400 text-lg">Only one article published so far. <a href="admin/" class="text-primary hover:underline font-semibold">Add more →</a></p>
      </div>
      <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../templates/partials/trending_sidebar.php'; ?>
  </div>
</section>
<?php endif; ?>
