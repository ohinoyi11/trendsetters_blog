<?php
// templates/partials/share.php
$url   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$title = htmlspecialchars($article['title']);
?>

<div class="flex items-center gap-4">
  <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Share Story</span>
  
  <div class="flex items-center gap-2">
    <!-- WhatsApp -->
    <a href="https://wa.me/?text=<?= urlencode($title . ' ' . $url) ?>" target="_blank" 
       class="flex h-9 w-9 items-center justify-center rounded-full bg-green-50 text-green-600 border border-green-100 transition-all hover:bg-green-600 hover:text-white group" 
       title="Share on WhatsApp">
      <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    </a>

    <!-- X (Twitter) -->
    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($title) ?>&url=<?= urlencode($url) ?>" target="_blank" 
       class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 text-slate-700 border border-slate-100 transition-all hover:bg-black hover:text-white" 
       title="Post on X">
      <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 1200 1227"><path d="M714.163 519.284L1160.89 0H1055.03L667.137 450.887L357.328 0H0L468.492 681.821L0 1226.37H105.866L515.491 750.218L842.672 1226.37H1200L714.137 519.284H714.163ZM569.165 687.828L521.697 619.934L144.011 79.6944H306.615L611.412 515.685L658.88 583.579L1055.08 1150.3H892.476L569.165 687.854V687.828Z"/></svg>
    </a>

    <!-- Native Share / Copy -->
    <button onclick="shareNative()" 
            class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-50 text-slate-500 border border-slate-100 transition-all hover:bg-primary hover:text-white" 
            title="More Options">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
    </button>
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
