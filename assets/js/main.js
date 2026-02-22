(function () {
  // --- Essential UI ---
  function els(q, ctx = document) { return Array.from(ctx.querySelectorAll(q)); }

  // Mobile menu toggle
  document.getElementById('mobile-toggle')?.addEventListener('click', () => {
    document.getElementById('mobile-menu')?.classList.toggle('hidden');
  });

  // Search toggle
  document.getElementById('search-toggle')?.addEventListener('click', () => {
    const s = document.getElementById('header-search');
    if (!s) return; s.classList.toggle('hidden');
    document.getElementById('search-input')?.focus();
  });

  // --- Scroll Animations (Intersection Observer) ---
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
      }
    });
  }, { threshold: 0.05 }); // Lower threshold for better mobile triggers

  function initReveals() {
    els('.reveal').forEach(revealEl => {
      revealObserver.observe(revealEl);
    });
  }

  // --- Swiper Initialization ---
  function initSwipers() {
    const swiperEl = document.querySelector('.featured-swiper');
    if (swiperEl && typeof Swiper !== 'undefined') {
      const slideCount = swiperEl.querySelectorAll('.swiper-slide').length;
      new Swiper('.featured-swiper', {
        loop: slideCount > 1,
        speed: 800,
        autoplay: slideCount > 1 ? { delay: 6000, disableOnInteraction: false } : false,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      });
    } else if (swiperEl) {
      setTimeout(initSwipers, 150);
    }
  }

  // Handle Newsletter
  document.getElementById('subscribe-btn')?.addEventListener('click', (e) => {
    const email = document.getElementById('newsletter-email')?.value;
    if (email) alert('Thanks! We will notify ' + email + ' soon.');
  });

  // Start components
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => { initSwipers(); initReveals(); });
  } else {
    initSwipers();
    initReveals();
  }
})();