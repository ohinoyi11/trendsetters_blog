<?php if (!empty($featuredArticles)): ?>
  <div class="featured-swiper swiper rounded-[2rem] shadow-2xl overflow-hidden bg-black">
    <div class="swiper-wrapper">
      <?php foreach ($featuredArticles as $article): ?>
        <div class="swiper-slide relative">
          <div class="aspect-[16/9] w-full overflow-hidden md:aspect-[21/9]">
            <img src="<?= $base ?>/<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="h-full w-full object-cover opacity-80" />
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
          </div>
          <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-16">
            <div class="mb-3 md:mb-5">
              <span class="inline-flex items-center gap-2 rounded-full bg-primary/90 px-3 py-1 text-[9px] font-bold uppercase tracking-wider text-white backdrop-blur-sm md:text-[10px] md:px-4 md:py-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                <?php echo htmlspecialchars($article['category']); ?>
              </span>
            </div>
            <a href="<?= $base ?>/article/<?= $article['id'] ?>" class="group max-w-4xl">
              <h2 class="mb-4 font-display text-2xl font-bold leading-tight text-white transition-colors group-hover:text-primary-foreground/90 md:text-5xl lg:text-6xl md:mb-8">
                <?php echo htmlspecialchars($article['title']); ?>
              </h2>
            </a>
            <div class="flex items-center gap-4 text-white/70 text-[10px] font-medium md:text-sm md:gap-6">
              <div class="flex items-center gap-2 md:gap-3">
                <?php if (!empty($article['author']['avatar'])): ?>
                  <img src="<?= $base ?>/<?= $article['author']['avatar'] ?>" class="h-6 w-6 rounded-full object-cover shadow-sm ring-2 ring-white/20 md:h-10 md:w-10" alt="Avatar"/>
                <?php else: ?>
                  <div class="h-6 w-6 rounded-full bg-white/20 flex items-center justify-center text-[8px] font-bold border border-white/10 md:h-10 md:w-10 md:text-xs text-white">
                    <?php echo initials($article['author']['name']); ?>
                  </div>
                <?php endif; ?>
                <span class="font-bold text-white flex items-center"><?php echo htmlspecialchars($article['author']['name']); ?><?= verified_badge() ?></span>
              </div>
              <div class="flex items-center gap-3 border-l border-white/20 pl-4 md:gap-4 md:pl-6">
                <span><?php echo htmlspecialchars($article['date']); ?></span>
                <span class="hidden h-1 w-1 rounded-full bg-white/40 md:block"></span>
                <span class="hidden md:block"><?php echo htmlspecialchars($article['readTime']); ?></span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <!-- Pagination & Navigation -->
    <div class="swiper-pagination !bottom-4 md:!bottom-8"></div>
    <div class="swiper-button-next !hidden md:!flex"></div>
    <div class="swiper-button-prev !hidden md:!flex"></div>
  </div>
<?php elseif (!empty($featured)): ?>
  <div class="relative overflow-hidden rounded-[2rem] shadow-2xl bg-black">
    <div class="aspect-[16/9] w-full overflow-hidden md:aspect-[21/9]">
      <img src="<?= $base ?>/<?php echo $featured['image']; ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>" class="h-full w-full object-cover opacity-80" />
      <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
    </div>
    <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-16">
      <div class="mb-3 md:mb-5">
        <span class="inline-flex items-center gap-2 rounded-full bg-primary/90 px-3 py-1 text-[9px] font-bold uppercase tracking-wider text-white backdrop-blur-sm md:text-[10px]">
          <?php echo htmlspecialchars($featured['category']); ?>
        </span>
      </div>
      <a href="<?= $base ?>/article/<?= $featured['id'] ?>">
        <h2 class="mb-4 font-display text-2xl font-bold leading-tight text-white md:text-5xl lg:text-6xl md:mb-8">
          <?php echo htmlspecialchars($featured['title']); ?>
        </h2>
      </a>
      <div class="flex items-center gap-4 text-white/70 text-[10px] font-medium md:text-sm md:gap-6">
        <div class="flex items-center gap-2 md:gap-3">
          <?php if (!empty($featured['author']['avatar'])): ?>
            <img src="<?= $base ?>/<?= $featured['author']['avatar'] ?>" class="h-6 w-6 rounded-full object-cover shadow-sm ring-2 ring-white/20 md:h-8 md:w-8" alt="Avatar"/>
          <?php else: ?>
            <div class="h-6 w-6 rounded-full bg-white/20 flex items-center justify-center text-[8px] font-bold border border-white/10 md:h-8 md:w-8 md:text-xs text-white">
              <?php echo initials($featured['author']['name']); ?>
            </div>
          <?php endif; ?>
          <span class="font-bold text-white flex items-center"><?php echo htmlspecialchars($featured['author']['name']); ?><?= verified_badge() ?></span>
        </div>
        <div class="flex items-center gap-3 border-l border-white/20 pl-4 md:gap-4 md:pl-6">
          <span><?php echo htmlspecialchars($featured['date']); ?></span>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
