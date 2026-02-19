(function(){
  const ARTICLES = window.ARTICLES || [];
  const CATEGORIES = window.CATEGORIES || [];
  let activeFilter = 'All';
  let visibleCount = 6;

  function el(q, ctx=document) { return ctx.querySelector(q); }
  function els(q, ctx=document) { return Array.from(ctx.querySelectorAll(q)); }

  // Mobile menu toggle
  document.getElementById('mobile-toggle')?.addEventListener('click', ()=>{
    const m = document.getElementById('mobile-menu');
    if (!m) return; m.classList.toggle('hidden');
  });

  // Search toggle
  document.getElementById('search-toggle')?.addEventListener('click', ()=>{
    const s = document.getElementById('header-search');
    if (!s) return; s.classList.toggle('hidden');
    const input = document.getElementById('search-input'); if(input) input.focus();
  });

  // Filters
  function renderLists(){
    const nonFeatured = ARTICLES.filter(a=>!a.featured);
    const filtered = activeFilter === 'All' ? nonFeatured : nonFeatured.filter(a=>a.category===activeFilter);

    // Editors picks
    const picks = nonFeatured.slice(0,3);
    const picksContainer = document.getElementById('editors-picks');
    if(picksContainer){
      picksContainer.innerHTML = picks.map(a=>`
        <div class="group flex gap-3 py-3">
          <div class="h-16 w-16 shrink-0 overflow-hidden rounded-lg"><img src="${a.image}" class="h-full w-full object-cover"/></div>
          <div class="flex-1"><p class="line-clamp-2 text-sm font-semibold leading-tight text-foreground">${escapeHtml(a.title)}</p><p class="mt-1 text-xs text-muted-foreground">${a.date}</p></div>
        </div>
      `).join('');
    }

    // Latest
    const latest = filtered.slice(0, visibleCount);
    const latestContainer = document.getElementById('latest-grid');
    if(latestContainer){
      latestContainer.innerHTML = latest.map(a=>`
        <div class="group">
          <a href="/article/${a.id}" class="block overflow-hidden rounded-xl bg-card shadow-card">
            <div class="relative aspect-[16/10] overflow-hidden"><img src="${a.image}" class="h-full w-full object-cover"/></div>
            <div class="p-5">
              <h3 class="line-clamp-2 font-display text-lg font-bold text-foreground">${escapeHtml(a.title)}</h3>
              <p class="mt-2 line-clamp-2 text-sm text-muted-foreground">${escapeHtml(a.excerpt)}</p>
              <div class="mt-4 flex items-center justify-between text-xs text-muted-foreground">
                <div class="flex items-center gap-2"><div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-primary-foreground">${a.author.avatar}</div><span class="font-medium">${escapeHtml(a.author.name)}</span></div>
                <div class="flex items-center gap-3"><span>${a.readTime}</span><span>${a.comments}</span></div>
              </div>
            </div>
          </a>
        </div>
      `).join('');
    }

    // Trending
    const trending = ARTICLES.filter(a=>a.trending).slice(0,5);
    const trendContainer = document.getElementById('trending-list');
    if(trendContainer){
      trendContainer.innerHTML = trending.map((t,i)=>`
        <div class="flex gap-3 py-3 first:pt-0 last:pb-0">
          <span class="font-display text-2xl font-bold text-primary/30">${String(i+1).padStart(2,'0')}</span>
          <div class="flex-1"><a href="/article/${t.id}" class="text-sm font-semibold">${escapeHtml(t.title)}</a><div class="text-xs text-muted-foreground">${t.date}</div></div>
        </div>
      `).join('');
    }

    // Toggle load more visibility
    const loadBtn = document.getElementById('load-more');
    if(loadBtn) loadBtn.style.display = (filtered.length > visibleCount) ? '' : 'none';
  }

  function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  // Filter button handlers
  document.addEventListener('click', (e)=>{
    const t = e.target.closest && e.target.closest('.filter-btn');
    if(t){
      activeFilter = t.dataset.cat || 'All'; visibleCount = 6; renderLists();
      // update active classes
      els('.filter-btn').forEach(b=>b.classList.remove('bg-primary','text-primary-foreground'));
      t.classList.add('bg-primary','text-primary-foreground');
    }
  });

  document.getElementById('load-more')?.addEventListener('click', ()=>{ visibleCount += 6; renderLists(); });

  document.getElementById('subscribe-btn')?.addEventListener('click', (e)=>{ e.preventDefault(); const email = document.getElementById('newsletter-email').value; alert('Subscribed: '+email); });

  // initial render
  renderLists();
})();