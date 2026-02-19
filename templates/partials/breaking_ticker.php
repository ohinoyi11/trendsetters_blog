<div class="bg-primary text-primary-foreground">
  <div class="container flex items-center gap-4 overflow-hidden py-2">
    <strong class="mr-3 font-display">BREAKING</strong>
    <div class="w-full overflow-hidden">
      <div class="inline-block whitespace-nowrap animate-ticker">
        <?php foreach ($breakingNews as $i => $b): ?>
          <span class="mr-8"><?php echo htmlspecialchars($b); ?></span>
        <?php endforeach; ?>
        <!-- duplicate for seamless loop -->
        <?php foreach ($breakingNews as $i => $b): ?>
          <span class="mr-8"><?php echo htmlspecialchars($b); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
