<?php
$q = trim($_GET['q'] ?? '');
$results = [];
if (strlen($q) > 1) {
  $lower = mb_strtolower($q);
  foreach ($articles as $a) {
    if (mb_stripos($a['title'] . ' ' . $a['excerpt'] . ' ' . $a['category'], $lower) !== false) {
      $results[] = $a;
    }
  }
}
?>

<div class="container py-10">
  <h1 class="font-display text-3xl font-bold text-foreground">Search</h1>
  <div class="relative mt-4 max-w-xl">
    <form action="/search" method="get">
      <input name="q" value="<?php echo htmlspecialchars($q); ?>" type="text" placeholder="Search articles, topics, authors..." class="w-full rounded-xl border border-border bg-card py-3.5 pl-4 pr-4 text-base text-foreground shadow-card placeholder:text-muted-foreground" autofocus />
    </form>
  </div>

  <?php if (strlen($q) > 1): ?>
    <p class="mt-4 text-sm text-muted-foreground"><?php echo count($results); ?> result<?php if (count($results)!==1) echo 's'; ?> for "<?php echo htmlspecialchars($q); ?>"</p>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($results as $i => $article): ?>
        <a href="/article/<?php echo $article['id']; ?>" class="block overflow-hidden rounded-xl bg-card shadow-card">
          <div class="relative aspect-[16/10] overflow-hidden"><img src="<?php echo $article['image']; ?>" class="h-full w-full object-cover"/></div>
          <div class="p-4"><h4 class="font-medium text-foreground"><?php echo htmlspecialchars($article['title']); ?></h4><p class="text-sm text-muted-foreground"><?php echo htmlspecialchars($article['excerpt']); ?></p></div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="mt-12 text-center text-muted-foreground">Start typing to search across all articles.</p>
  <?php endif; ?>
</div>