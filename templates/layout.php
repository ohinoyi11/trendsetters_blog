<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>TrendSetters News</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
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

    /* Container */
    .container { width: 100%; max-width: 1280px; margin-left: auto; margin-right: auto; padding-left: 1.5rem; padding-right: 1.5rem; }

    /* Card shadow shorthand */
    .shadow-card { box-shadow: 0 2px 12px -2px rgba(0,0,0,0.06); }
    .shadow-premium { box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08); }
  </style>
</head>
<body class="font-body bg-background text-foreground">
  <?php include __DIR__ . '/partials/breaking_ticker.php'; ?>
  <?php include __DIR__ . '/partials/header.php'; ?>
  <main>
    <?php echo $content; ?>
  </main>
  <?php include __DIR__ . '/partials/footer.php'; ?>
  <script>window.ARTICLES = <?php echo json_encode($articles); ?>; window.CATEGORIES = <?php echo json_encode($categories); ?>; window.BREAKING = <?php echo json_encode($breakingNews); ?>;</script>
  <script src="<?= $base ?>/assets/js/main.js"></script>
</body>
</html>