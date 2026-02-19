<?php
// templates/partials/reactions.php
$article_id = (int)$article['id'];
$user       = get_site_user();

// Fetch counts and user current reaction
$pdo = get_pdo();
$stmt = $pdo->prepare("SELECT type, COUNT(*) as count FROM reactions WHERE article_id=? GROUP BY type");
$stmt->execute([$article_id]);
$counts = ['like'=>0, 'love'=>0, 'fire'=>0];
foreach ($stmt->fetchAll() as $row) { $counts[$row['type']] = (int)$row['count']; }

$userReact = null;
if ($user) {
    $stmt = $pdo->prepare("SELECT type FROM reactions WHERE article_id=? AND user_id=? LIMIT 1");
    $stmt->execute([$article_id, $user['id']]);
    $userReact = $stmt->fetchColumn();
}
?>

<div class="flex flex-wrap items-center gap-2" id="reaction-container">
  <?php foreach (['like' => '👍', 'love' => '❤️', 'fire' => '🔥'] as $type => $emoji): ?>
    <button onclick="handleReact('<?= $type ?>')" 
            data-type="<?= $type ?>"
            class="reaction-btn flex items-center gap-2 rounded-full border px-4 py-2 transition-all <?= $userReact === $type ? 'border-primary bg-primary/10 text-primary font-bold shadow-sm' : 'border-slate-100 bg-white hover:border-slate-300' ?>">
      <span class="text-base"><?= $emoji ?></span>
      <span class="text-xs count-label"><?= number_format($counts[$type]) ?></span>
    </button>
  <?php endforeach; ?>
</div>

<script>
function handleReact(type) {
    <?php if (!$user): ?>
      window.location.href = '<?= $base ?>/login';
      return;
    <?php endif; ?>

    const formData = new FormData();
    formData.append('article_id', '<?= $article_id ?>');
    formData.append('type', type);

    fetch('<?= $base ?>/api/react.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update all counts
            Object.keys(data.counts).forEach(t => {
                const btn = document.querySelector(`.reaction-btn[data-type="${t}"]`);
                btn.querySelector('.count-label').innerText = data.counts[t];
                
                if (t === data.user_type) {
                    btn.classList.add('border-primary', 'bg-primary/10', 'text-primary', 'font-bold', 'shadow-sm');
                    btn.classList.remove('border-slate-100', 'bg-white');
                } else {
                    btn.classList.remove('border-primary', 'bg-primary/10', 'text-primary', 'font-bold', 'shadow-sm');
                    btn.classList.add('border-slate-100', 'bg-white');
                }
            });
        }
    });
}
</script>
