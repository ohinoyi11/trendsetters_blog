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
          <?php if (!empty($article['author_avatar'])): ?>
            <img src="<?= $base ?>/<?= $article['author_avatar'] ?>" class="h-12 w-12 rounded-full object-cover shadow-sm ring-2 ring-white" alt="Avatar"/>
          <?php else: ?>
            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 shadow-sm border border-white">
                <?= initials($article['author_name']) ?>
            </div>
          <?php endif; ?>
          <div>
            <p class="text-sm font-bold text-foreground flex items-center"><?= htmlspecialchars($article['author_name']) ?><?= verified_badge() ?></p>
            <p class="text-[10px] uppercase tracking-widest text-muted-foreground font-bold"><?= $article['date'] ?> • <?= $article['read_time'] ?></p>
          </div>
        </div>

        <?php include __DIR__ . '/../templates/partials/share.php'; ?>
      </div>
    </header>

    <!-- Hero Image -->
    <?php if ($article['image']): ?>
      <div class="group relative mb-12 overflow-hidden rounded-3xl bg-slate-100 shadow-xl shadow-slate-200/50">
        <img src="<?= $base ?>/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="aspect-video w-full object-cover transition-transform duration-500 group-hover:scale-105" />
        <?php if ($article['views'] > 1000): ?>
          <div class="absolute right-6 top-6 rounded-full bg-red-500 px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-white shadow-lg shadow-red-500/30 animate-pulse">🔥 Viral</div>
        <?php endif; ?>
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

    <!-- Author Bio Card (New Feature) -->
    <div class="mt-16 bg-white rounded-3xl p-8 border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-8 items-center sm:items-start">
        <?php if ($article['author_avatar']): ?>
            <img src="<?= $base ?>/<?= $article['author_avatar'] ?>" class="h-24 w-24 rounded-full object-cover shadow-lg ring-4 ring-slate-50" alt="<?= htmlspecialchars($article['author_name']) ?>"/>
        <?php else: ?>
            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-primary text-2xl font-bold text-white shadow-lg ring-4 ring-slate-50">
                <?= initials($article['author_name']) ?>
            </div>
        <?php endif; ?>
        <div class="flex-1 text-center sm:text-left">
            <h4 class="font-display text-xl font-bold text-slate-900 mb-1 flex items-center justify-center sm:justify-start"><?= htmlspecialchars($article['author_name']) ?><?= verified_badge() ?></h4>
            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-4">Correspondent</div>
            <p class="text-sm text-slate-500 leading-relaxed line-clamp-3 italic">
                <?= !empty($article['author_bio']) ? htmlspecialchars($article['author_bio']) : "Investigative journalist reporting on the latest trends and stories that shape Africa's future." ?>
            </p>
            <div class="mt-6 flex justify-center sm:justify-start gap-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Journalist Profile Verified ✅</span>
            </div>
        </div>
    </div>

    <!-- Engagement Footer -->
    <div class="mt-8 bg-slate-50 rounded-3xl p-8 border border-slate-100">
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

<!-- Reading progress bar -->
<div id="read-progress" class="fixed top-0 left-0 z-[100] h-1 w-0 bg-primary transition-all duration-75"></div>

<script>
window.onscroll = function() {
  const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  const scrolled = (winScroll / height) * 100;
  document.getElementById("read-progress").style.width = scrolled + "%";
};
</script>

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