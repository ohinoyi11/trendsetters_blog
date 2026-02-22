<?php
// Home page - Legit-style Architecture Reorganization
$featuredArticles = [];
$nonFeatured     = [];
foreach ($articles as $a) {
  if (!empty($a['featured'])) { $featuredArticles[] = $a; }
  else { $nonFeatured[] = $a; }
}
$featured = !empty($featuredArticles) ? $featuredArticles[0] : null; 
$visible = array_slice($nonFeatured, 0, 10); // More dense feed
?>

<?php if (empty($articles)): ?>
<section class="container mt-20 pb-32 text-center reveal">
  <div class="mx-auto max-w-lg">
    <p class="text-6xl mb-6">✍️</p>
    <h1 class="font-display text-4xl font-bold text-foreground mb-4">Welcome to <?= htmlspecialchars($siteName ?? 'TrendSetters News') ?></h1>
    <p class="text-lg text-muted-foreground mb-8">There are no articles published yet.</p>
    <a href="admin/" class="inline-flex items-center gap-2 rounded-full bg-primary px-8 py-4 font-display font-bold text-white shadow-lg shadow-primary/30 transition hover:bg-primary/90 hover:shadow-xl text-sm">
      Go to Admin Panel →
    </a>
  </div>
</section>
<?php else: ?>

<!-- Section 2: Featured & Headline Area -->
<section class="container mt-12 reveal">
  <?php include __DIR__ . '/../templates/partials/featured_article.php'; ?>
</section>

<!-- Trending in Categories (Visual Portal Entry) -->
<section class="container mt-16 reveal">
  <div class="mb-10 flex items-center justify-between">
    <div>
      <h2 class="font-display text-2xl font-bold tracking-tight text-foreground md:text-3xl">Trending in Categories</h2>
      <p class="mt-1 text-sm text-muted-foreground font-medium">Flash updates from our major desks</p>
    </div>
  </div>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <?php 
      $desks = ['Politics', 'Technology', 'Sports', 'Business'];
      foreach ($desks as $i => $desk):
        // Find latest article in this category
        $catArticles = array_filter($articles, fn($a) => $a['category'] === $desk);
        $top = !empty($catArticles) ? reset($catArticles) : null;
        if (!$top) continue;
    ?>
      <a href="<?= $base ?>/category/<?php echo strtolower($desk); ?>" class="group relative aspect-square overflow-hidden rounded-[2.5rem] bg-slate-900 shadow-xl reveal reveal-delay-<?php echo $i; ?>">
        <img src="<?= $base ?>/<?php echo $top['image']; ?>" class="absolute inset-0 h-full w-full object-cover opacity-60 transition-transform duration-700 group-hover:scale-110" loading="lazy" />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        <div class="absolute inset-0 p-8 flex flex-col justify-between">
          <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1 text-[9px] font-bold uppercase tracking-widest text-white bg-<?php echo strtolower($desk); ?>">
             <span class="h-1 w-1 rounded-full bg-white animate-pulse"></span>
             <?php echo $desk; ?>
          </span>
          <div>
            <h3 class="font-display text-lg font-bold leading-tight text-white group-hover:text-primary-foreground/90 transition-colors line-clamp-2"><?php echo $top['title']; ?></h3>
            <p class="mt-2 text-[10px] font-bold text-white/50 uppercase tracking-widest group-hover:text-white transition-colors">Latest Insight →</p>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="container mt-16 pb-24">
  <div class="grid gap-12 lg:grid-cols-3">
    <!-- Main Content Area (Section 3: Latest News Feed) -->
    <div class="lg:col-span-2">
      
      <!-- Section 3: Latest Feed Header -->
      <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6 reveal">
        <div>
          <h2 class="font-display text-2xl font-bold tracking-tight text-foreground md:text-3xl">Latest Updates</h2>
          <p class="mt-1 text-sm text-muted-foreground">The news conveyor belt — updated by the minute</p>
        </div>
        
        <!-- Interactive Search Bar -->
        <div class="relative max-w-sm w-full group">
          <form action="<?= $base ?>/search" method="GET">
            <input type="text" name="q" placeholder="Search stories..." 
                   class="w-full rounded-2xl border border-slate-100 bg-white px-6 py-4 pl-12 text-sm shadow-sm outline-none transition-all group-hover:shadow-md focus:border-primary focus:ring-4 focus:ring-primary/5 group-hover:border-slate-200" />
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-hover:text-primary transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <button type="submit" class="absolute right-3 top-2.5 rounded-xl bg-slate-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:bg-primary hover:text-white transition-all">Go</button>
          </form>
        </div>
      </div>

      <!-- Category Filter Tabs -->
      <div class="mb-10 flex flex-wrap items-center gap-2 md:gap-3 reveal">
        <?php foreach (array_slice($categories, 0, 8) as $cat): ?>
          <a href="<?= $base ?>/category/<?php echo strtolower(str_replace(' ', '-', $cat)); ?>" class="rounded-full border border-border bg-white px-5 py-2 text-[10px] font-bold uppercase tracking-widest text-muted-foreground transition-all hover:bg-primary hover:text-white hover:border-primary"><?php echo htmlspecialchars($cat); ?></a>
        <?php endforeach; ?>
      </div>

      <!-- Feed Grid -->
      <div id="latest-grid" class="grid gap-8 sm:grid-cols-2">
        <?php foreach ($visible as $i => $article): ?>
          <?php 
            $variant = 'default'; 
            $extraClass = 'reveal reveal-delay-' . ($i % 2) . ' hover-lift'; 
            include __DIR__ . '/../templates/partials/article_card.php'; 
          ?>
        <?php endforeach; ?>
      </div>

      <!-- Section 5: News in Pictures mid-feed -->
      <div class="mt-20 mb-20 reveal">
        <div class="mb-8 flex items-center justify-between">
           <div>
             <h2 class="font-display text-2xl font-bold tracking-tight text-foreground md:text-3xl">News in Pictures</h2>
             <p class="mt-1 text-sm text-muted-foreground">Capturing global headlines in the moment</p>
           </div>
           <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-primary hover:underline border-b border-primary/20 pb-0.5">Full Gallery</a>
        </div>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
           <?php foreach (array_slice($articles, 0, 6) as $i => $a): ?>
             <a href="<?= $base ?>/article/<?php echo $a['id']; ?>" class="group relative aspect-square overflow-hidden rounded-3xl bg-slate-100 reveal reveal-delay-<?php echo $i; ?>">
                <img src="<?= $base ?>/<?php echo $a['image']; ?>" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" />
                <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-[2px]">
                   <span class="rounded-full bg-white/90 p-3 text-xl shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-transform">🔍</span>
                </div>
             </a>
           <?php endforeach; ?>
        </div>
      </div>

      <!-- Section 6: Categorized Mini-Portals -->
      <?php 
        $portalCats = ['Politics', 'Entertainment', 'People'];
        foreach ($portalCats as $pCat):
          $pArticles = array_filter($articles, fn($a) => $a['category'] === $pCat);
          if (empty($pArticles)) continue;
          $pMain = array_shift($pArticles);
          $pSide = array_slice($pArticles, 0, 3);
      ?>
      <div class="mb-20 reveal">
        <div class="mb-8 flex items-center gap-4">
          <h2 class="font-display text-2xl font-bold tracking-tight text-foreground md:text-3xl"><?php echo $pCat; ?> Portal</h2>
          <div class="h-px flex-1 bg-border/40"></div>
          <a href="<?= $base ?>/category/<?php echo strtolower($pCat); ?>" class="text-[10px] font-bold uppercase tracking-widest text-primary hover:underline">View All</a>
        </div>
        <div class="grid gap-8 lg:grid-cols-2">
          <!-- Highlighted -->
          <?php $article = $pMain; $variant = 'default'; $extraClass = 'hover-lift'; include __DIR__ . '/../templates/partials/article_card.php'; ?>
          <!-- List -->
          <div class="space-y-4">
            <?php foreach ($pSide as $sA): ?>
              <?php $article = $sA; $variant = 'compact'; include __DIR__ . '/../templates/partials/article_card.php'; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Section 6: Ask TrendSetters / Special Block -->
      <div class="mb-20 reveal rounded-[3rem] bg-slate-900 p-12 text-white relative overflow-hidden">
        <div class="relative z-10 max-w-xl">
          <span class="inline-block rounded-full bg-primary px-4 py-1 text-[10px] font-bold uppercase tracking-widest mb-6">Ask TrendSetters</span>
          <h2 class="font-display text-4xl font-bold leading-tight mb-6 italic">What's on your mind today?</h2>
          <p class="text-white/70 text-lg mb-10">We answer your burning questions about politics, health, and lifestyle in our daily Q&A series.</p>
          <a href="#" class="inline-flex items-center gap-2 rounded-full bg-white px-10 py-4 font-display font-bold text-slate-900 shadow-xl transition hover:bg-slate-100 hover:scale-105 active:scale-95">
            Submit Your Question →
          </a>
        </div>
        <!-- Decorative blobs -->
        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 h-48 w-48 rounded-full bg-accent/20 blur-3xl"></div>
      </div>

      <?php if (count($nonFeatured) > 10): ?>
        <div class="mt-16 text-center reveal">
          <a href="<?= $base ?>/search" class="group inline-flex items-center gap-2 rounded-full border border-border bg-white px-12 py-5 font-display text-base font-bold text-foreground transition-all hover:border-primary hover:text-primary hover:shadow-2xl">
            See More Stories
            <span class="transition-transform group-hover:translate-x-1">→</span>
          </a>
        </div>
      <?php endif; ?>

    </div>

    <!-- Sidebar Area (Section 4: Trending / Most Read Block) -->
    <aside class="space-y-12">
      <div class="sticky top-24 space-y-12">
        <?php include __DIR__ . '/../templates/partials/trending_sidebar.php'; ?>
      </div>
    </aside>
  </div>
</section>
<?php endif; ?>
