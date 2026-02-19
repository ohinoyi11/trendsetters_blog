<?php
// input partial
// usage: set $name, optional $value, optional $placeholder, optional $attrs, optional $class
if (!isset($name)) $name = '';
if (!isset($value)) $value = '';
if (!isset($placeholder)) $placeholder = '';
if (!isset($attrs)) $attrs = '';
if (!isset($class)) $class = 'rounded-2xl border border-border bg-background px-5 py-3 text-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary';
?>
<input name="<?php echo htmlspecialchars($name); ?>" value="<?php echo htmlspecialchars($value); ?>" placeholder="<?php echo htmlspecialchars($placeholder); ?>" <?php echo $attrs; ?> class="<?php echo htmlspecialchars($class); ?>" />
