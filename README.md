Local PHP frontend (converted from React)

Quick start (recommended):
1. From the project root (insight-hub-main), run the PHP built-in server with the router so static assets in `/src/assets` are served correctly:

```bash
cd c:\Users\user\Desktop\insight-hub-main\insight-hub-main
php -S localhost:8000 php/index.php
```

2. Open http://localhost:8000/php/ in your browser to view the PHP site (the router serves `php/index.php`).

Notes and tips:
- The conversion places PHP files under `php/` and reuses the original images in `src/assets`.
- If you prefer the site at root (`/`), start the server from the `php` folder and copy `src/assets` into `php/assets`:

```bash
cd php
# copy assets (Windows PowerShell)
Copy-Item -Recurse ..\src\assets .\assets
php -S localhost:8000
```

- JS interactivity (filters, load more, newsletter) is implemented in `php/assets/js/main.js` and relies on `window.ARTICLES` which is embedded by the layout.
- If you run into 404s for static files, verify your server working directory and that `src/assets` or `php/assets` are reachable by the server.

If you want, I can:
- Move/copy the image assets into `php/assets` so the site can be served directly from `php/` without the router script.
- Finish converting remaining small UI helpers into PHP includes.
