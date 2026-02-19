<?php
// templates/partials/comments.php
$article_id = (int)$article['id'];
$user = get_site_user();

$pdo = get_pdo();
$stmt = $pdo->prepare(
    "SELECT c.*, u.name, u.role
     FROM comments c
     JOIN users u ON c.user_id = u.id
     WHERE c.article_id = ?
     ORDER BY c.id DESC"
);
$stmt->execute([$article_id]);
$allComments = $stmt->fetchAll();
?>

<div class="mt-16 pt-16 border-t border-slate-100">
  <div class="flex items-center justify-between mb-8">
    <h3 class="font-display text-2xl font-bold text-slate-900">Comments <span class="text-slate-300 font-medium ml-1"><?= count($allComments) ?></span></h3>
  </div>

  <!-- Form -->
  <?php if ($user): ?>
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm mb-10 overflow-hidden focus-within:ring-4 focus-within:ring-primary/5 transition-all">
      <div class="flex gap-4">
        <div class="h-10 w-10 shrink-0 rounded-full bg-primary flex items-center justify-center text-[10px] font-bold text-white shadow-md">
          <?= initials($user['name']) ?>
        </div>
        <div class="flex-1">
          <textarea id="comment-textarea" class="w-full bg-transparent border-none p-0 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 resize-none" rows="3" placeholder="Add to the discussion..."></textarea>
          <div class="flex justify-end mt-4">
            <button onclick="submitComment()" class="rounded-full bg-primary px-6 py-2.5 text-xs font-bold text-white shadow-lg shadow-primary/20 transition hover:bg-primary/90 hover:shadow-xl">Post Comment</button>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="bg-slate-50 rounded-3xl p-8 text-center mb-10 border border-dashed border-slate-200">
      <p class="text-sm font-medium text-slate-600 mb-4">Log in to join the conversation</p>
      <a href="<?= $base ?>/login" class="inline-flex rounded-full bg-primary px-8 py-3 text-xs font-bold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition">Sign In to Comment</a>
    </div>
  <?php endif; ?>

  <!-- List -->
  <div id="comments-list" class="divide-y divide-slate-50 bg-white rounded-3xl border border-slate-100 overflow-hidden">
    <?php if (empty($allComments)): ?>
      <div id="no-comments" class="p-10 text-center text-slate-400 text-sm italic">Be the first to comment on this story.</div>
    <?php else: ?>
      <?php foreach ($allComments as $c): ?>
        <div class="flex gap-4 p-6">
          <div class="h-10 w-10 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
            <?= initials($c['name']) ?>
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="text-sm font-bold text-slate-900"><?= htmlspecialchars($c['name']) ?></span>
              <span class="text-[10px] text-slate-400 font-medium"><?= date('M j, Y', strtotime($c['created_at'])) ?></span>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed"><?= nl2br(htmlspecialchars($c['content'])) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
function submitComment() {
    const text = document.getElementById('comment-textarea').value.trim();
    if (!text) return;

    const formData = new FormData();
    formData.append('article_id', '<?= $article_id ?>');
    formData.append('content', text);

    fetch('<?= $base ?>/api/comment.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const list = document.getElementById('comments-list');
            const placeholder = document.getElementById('no-comments');
            if (placeholder) placeholder.remove();

            list.insertAdjacentHTML('afterbegin', data.html);
            document.getElementById('comment-textarea').value = '';
        }
    });
}
</script>
