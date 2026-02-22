<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>TrendSetters News</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    document.documentElement.classList.add('js-ready');
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Outfit', 'sans-serif'],
            body: ['Inter', 'sans-serif'],
            sans: ['Inter', 'sans-serif'],
          },
          colors: {
            background:  'hsl(210 40% 98%)',
            foreground:  'hsl(222 47% 11%)',
            card:        'hsl(0 0% 100%)',
            'card-foreground': 'hsl(222 47% 11%)',
            primary: {
              DEFAULT: 'hsl(243 75% 59%)',
              foreground: 'hsl(210 40% 98%)',
            },
            secondary: {
              DEFAULT: 'hsl(210 40% 96%)',
              foreground: 'hsl(222 47% 11%)',
            },
            muted: {
              DEFAULT: 'hsl(210 40% 94%)',
              foreground: 'hsl(215 16% 47%)',
            },
            accent: {
              DEFAULT: 'hsl(262 83% 58%)',
              foreground: 'hsl(210 40% 98%)',
            },
            border: 'hsl(214 32% 91%)',
            input:  'hsl(214 32% 91%)',
            ring:   'hsl(243 75% 59%)',
          },
          borderRadius: {
            '2xl': '1rem',
            '3xl': '1.5rem',
          },
          boxShadow: {
            'premium': '0 10px 30px -10px rgba(0,0,0,0.08)',
            'card': '0 2px 12px -2px rgba(0,0,0,0.06)',
          }
        }
      }
    }
  </script>
  <style>
    /* Base styles */
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background-color: hsl(210 40% 98%);
      color: hsl(222 47% 11%);
      -webkit-font-smoothing: antialiased;
      margin: 0;
      overflow-x: hidden;
    }
    html {
      overflow-x: hidden;
    }
    h1,h2,h3,h4,h5,h6 {
      font-family: 'Outfit', sans-serif;
      font-weight: 700;
      letter-spacing: -0.025em;
    }
    a { text-decoration: none; }
    img { display: block; max-width: 100%; }

    /* Glass effect */
    .glass {
      background: rgba(255,255,255,0.80);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(255,255,255,0.3);
    }

    /* Hover lift */
    .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.12); }

    /* Line clamp */
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

    /* Ticker animation */
    @keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    .animate-ticker { animation: ticker 40s linear infinite; }

    /* Reveal animations - only hide if JS is ready */
    .js-ready    .reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1);
      visibility: hidden;
    }
    .js-ready .reveal {
      visibility: visible;
    }
    .reveal.revealed {
      opacity: 1;
      transform: translateY(0);
    }
    @media (prefers-reduced-motion: reduce) {
      .reveal {
        transition: none !important;
        opacity: 1 !important;
        transform: none !important;
        visibility: visible !important;
      }
    }
    .reveal-delay-1 { transition-delay: 0.1s; }
    .reveal-delay-2 { transition-delay: 0.2s; }
    .reveal-delay-3 { transition-delay: 0.3s; }

    /* Container */
    .container { width: 100%; max-width: 1280px; margin-left: auto; margin-right: auto; padding-left: 1.5rem; padding-right: 1.5rem; }

    /* Card shadow shorthand */
    .shadow-card { box-shadow: 0 2px 12px -2px rgba(0,0,0,0.06); }
    .shadow-premium { box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08); }

    /* Swiper Custom */
    .swiper-button-next, .swiper-button-prev { color: white !important; background: rgba(0,0,0,0.3); width: 44px !important; height: 44px !important; border-radius: 50%; backdrop-filter: blur(4px); }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 18px !important; font-weight: bold; }
    .swiper-pagination-bullet { background: white !important; opacity: 0.5; }
    .swiper-pagination-bullet-active { opacity: 1; width: 24px !important; border-radius: 4px !important; }

    /* Category Specific Colors */
    .bg-politics { background-color: #ef4444; }
    .bg-tech { background-color: #3b82f6; }
    .bg-sports { background-color: #10b981; }
    .bg-business { background-color: #f59e0b; }
    .bg-health { background-color: #8b5cf6; }
    .bg-fashion { background-color: #ec4899; }
    .bg-entertainment { background-color: #f43f5e; }

    /* Ranking Numbers */
    .rank-number {
      font-family: 'Outfit', sans-serif;
      font-size: 4rem;
      line-height: 1;
      font-weight: 800;
      opacity: 0.1;
      color: currentColor;
      -webkit-text-stroke: 1px currentColor;
    }

    /* Toast System */
    #toast-container { position: fixed; bottom: 2rem; right: 2rem; z-index: 200; display: flex; flex-direction: column; gap: 0.75rem; }
    .toast { 
      transform: translateX(120%); transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      background: white; border: 1px solid hsl(214 32% 91%); padding: 1rem 1.5rem; border-radius: 1.25rem;
      box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: flex; items-center: center; gap: 0.75rem;
      min-width: 280px;
    }
    .toast.show { transform: translateX(0); }
    .toast-icon { height: 2rem; w-2rem; rounded-full: true; flex-shrink: 0; display: flex; items-center: center; justify-center: center; }
  </style>
</head>
<body class="font-body bg-background text-foreground">
  <?php include __DIR__ . '/partials/header.php'; ?>
  <?php include __DIR__ . '/partials/breaking_ticker.php'; ?>
  <main>
    <?php echo $content; ?>
  </main>
  <?php include __DIR__ . '/partials/footer.php'; ?>
  <?php include __DIR__ . '/partials/auth_modals.php'; ?>

  <!-- Toast Container -->
  <div id="toast-container"></div>

  <script>
    window.CONFIG = { base: '<?= $base ?>' };
    window.CATEGORIES = <?php echo json_encode($categories); ?>; 
    window.BREAKING = <?php echo json_encode($breakingNews ?? []); ?>;

    function showToast(message, type = 'success') {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `toast shadow-premium border-l-4 ${type === 'success' ? 'border-green-500' : 'border-red-500'}`;
      
      const icon = type === 'success' ? '✅' : '⚠️';
      
      toast.innerHTML = `
        <div class="text-xl">${icon}</div>
        <div class="flex-1">
          <p class="text-xs font-bold text-slate-900">${message}</p>
        </div>
      `;
      
      container.appendChild(toast);
      setTimeout(() => toast.classList.add('show'), 10);
      
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 500);
      }, 4000);
    }

    // Check for session flash messages (PHP to JS)
    <?php if (isset($_SESSION['flash_success'])): ?>
      showToast("<?= $_SESSION['flash_success'] ?>", 'success');
      <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
      showToast("<?= $_SESSION['flash_error'] ?>", 'error');
      <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
  </script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
  <script src="<?= $base ?>/assets/js/main.js" defer></script>
</body>
</html>