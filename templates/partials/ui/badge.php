<?php
// badge partial
// usage: set $text, optional $class
if (!isset($text)) $text = '';
if (!isset($class)) $class = 'mb-3 bg-primary text-primary-foreground';
?>
<span class="inline-block rounded <?php echo htmlspecialchars($class); ?> px-2 py-1 text-sm font-semibold"><?php echo htmlspecialchars($text); ?></span>
