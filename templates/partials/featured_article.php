<?php if (!empty($featured)): ?>
  <div class="group relative block overflow-hidden rounded-3xl shadow-xl">
    <div class="aspect-[16/9] w-full overflow-hidden md:aspect-[21/9]">
      <img src="<?= $base ?>/<?php echo $featured['image']; ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-105" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
    </div>
    <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16 text-left">
      <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-primary px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest text-primary-foreground shadow-lg">
        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span></span>
        <?php echo htmlspecialchars($featured['category']); ?>
      </div>
      <h1 class="mb-6 max-w-4xl font-display text-3xl font-bold leading-tight text-white md:text-5xl lg:text-6xl"><?php echo htmlspecialchars($featured['title']); ?></h1>
      <div class="flex flex-wrap items-center gap-6 text-xs font-medium text-white/80 md:text-sm">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-xs font-bold text-white"><?php echo htmlspecialchars($featured['author']['avatar']); ?></div>
          <span class="font-bold text-white"><?php echo htmlspecialchars($featured['author']['name']); ?></span>
        </div>
        <div class="flex items-center gap-4 border-l border-white/20 pl-6">
          <span><?php echo htmlspecialchars($featured['date']); ?></span>
          <span class="h-1 w-1 rounded-full bg-white/40"></span>
          <span><?php echo htmlspecialchars($featured['readTime']); ?></span>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
