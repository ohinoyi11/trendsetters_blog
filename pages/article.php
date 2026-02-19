<?php
// pages/article.php — Individual article view
$id = (int)($_GET['id'] ?? 0);
$article = get_article($id); // DB lookup

if (!$article) {
  include __DIR__ . '/404.php';
  return;
}

// Increment views (simple)
get_pdo()->prepare("UPDATE articles SET views = views + 1 WHERE id=?")->execute([$id]);

$siteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<article class="container py-12">
  <div class="mx-auto max-w-4xl">
    <!-- Breadcrumbs -->
    <nav class="mb-8 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-muted-foreground">
      <a href="<?= $base ?>/" class="hover:text-primary">Home</a>
      <span>/</span>
      <a href="<?= $base ?>/category/<?= urlencode(strtolower($article['category'])) ?>" class="hover:text-primary"><?= htmlspecialchars($article['category']) ?></a>
    </nav>

    <!-- Header -->
    <header class="mb-10">
      <h1 class="font-display text-4xl font-bold leading-tight text-foreground md:text-5xl lg:text-6xl">
        <?= htmlspecialchars($article['title']) ?>
      </h1>
      
      <div class="mt-8 flex flex-wrap items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex items-center gap-4">
          <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 shadow-sm border border-white">
            <?= initials($article['author_name']) ?>
          </div>
          <div>
            <p class="text-sm font-bold text-foreground"><?= htmlspecialchars($article['author_name']) ?></p>
            <p class="text-[10px] uppercase tracking-widest text-muted-foreground font-bold"><?= $article['date'] ?> • <?= $article['read_time'] ?></p>
          </div>
        </div>

        <?php include __DIR__ . '/../templates/partials/share.php'; ?>
      </div>
    </header>

    <!-- Hero Image -->
    <?php if ($article['image']): ?>
      <div class="mb-12 overflow-hidden rounded-3xl bg-slate-100 shadow-xl shadow-slate-200/50">
        <img src="<?= $base ?>/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="aspect-video w-full object-cover" />
      </div>
    <?php endif; ?>

    <!-- Content -->
    <div class="prose prose-slate max-w-none">
      <p class="lead text-xl font-medium text-slate-700 leading-relaxed mb-8">
        <?= htmlspecialchars($article['excerpt']) ?>
      </p>
      <div class="article-body text-lg leading-relaxed text-slate-600">
        <?= $article['content'] // HTML content from editor ?>
      </div>
    </div>

    <!-- Engagement Footer -->
    <div class="mt-16 bg-slate-50 rounded-3xl p-8 border border-slate-100">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
        <div>
          <h4 class="font-display text-lg font-bold text-slate-900 mb-2">What do you think?</h4>
          <p class="text-sm text-slate-500">React to this story or join the discussion below.</p>
        </div>
        <?php include __DIR__ . '/../templates/partials/reactions.php'; ?>
      </div>
    </div>

    <!-- Comments -->
    <?php include __DIR__ . '/../templates/partials/comments.php'; ?>

  </div>
</article>

<style>
  .article-body h2 { @apply font-display text-3xl font-bold text-slate-900 mt-12 mb-6; }
  .article-body h3 { @apply font-display text-2xl font-bold text-slate-900 mt-10 mb-5; }
  .article-body p { @apply mb-6; }
  .article-body ul, .article-body ol { @apply mb-6 ml-6 list-outside; }
  .article-body ul { @apply list-disc; }
  .article-body ol { @apply list-decimal; }
  .article-body blockquote { @apply border-l-4 border-primary pl-6 italic text-slate-700 my-10; }
  .article-body img { @apply rounded-2xl my-10 shadow-lg; }
</style>