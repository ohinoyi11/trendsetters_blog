<?php
// simple button partial
// usage: set $text, optional $attrs (string), optional $class
if (!isset($text)) $text = '';
if (!isset($attrs)) $attrs = '';
if (!isset($class)) $class = 'rounded-2xl bg-primary px-6 py-3 font-display text-sm font-bold text-primary-foreground shadow-lg shadow-primary/20 transition-all hover:bg-primary/90 hover:shadow-xl active:scale-95';
?>
<button <?php echo $attrs; ?> class="<?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($text); ?></button>
