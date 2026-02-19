<?php
// templates/partials/share.php
$url   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$title = htmlspecialchars($article['title']);
?>

<div class="flex items-center gap-3">
  <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Share Story</span>
  
  <!-- Web Share API -->
  <button onclick="shareNative()" class="p-2 rounded-full border border-slate-100 hover:bg-slate-50 transition-colors" title="Share">↗️</button>
  
  <div class="flex gap-2">
    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($title) ?>&url=<?= urlencode($url) ?>" target="_blank" class="p-2 rounded-full border border-slate-100 hover:bg-sky-50 transition-colors" title="Post on X">𝕏</a>
    <a href="https://wa.me/?text=<?= urlencode($title . ' ' . $url) ?>" target="_blank" class="p-2 rounded-full border border-slate-100 hover:bg-green-50 transition-colors" title="Share on WhatsApp">WA</a>
    <button onclick="copyLink()" class="p-2 rounded-full border border-slate-100 hover:bg-slate-50 transition-colors" title="Copy Link">📋</button>
  </div>
</div>

<script>
function shareNative() {
    if (navigator.share) {
        navigator.share({ title: '<?= $title ?>', url: '<?= $url ?>' });
    } else {
        copyLink();
    }
}
function copyLink() {
    navigator.clipboard.writeText('<?= $url ?>').then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
