<?php
// templates/partials/auth_modals.php
?>
<!-- Auth Modal Backdrop -->
<div id="auth-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeAuthModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl overflow-hidden scale-95 transition-all duration-300" id="auth-content">
        <!-- Close Button -->
        <button onclick="closeAuthModal()" class="absolute top-6 right-6 h-10 w-10 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-all z-10">✕</button>

        <!-- Toggle Tabs -->
        <div class="flex border-b border-slate-100">
            <button onclick="switchTab('login')" id="tab-login" class="flex-1 py-6 text-sm font-bold uppercase tracking-widest transition-all border-b-2 border-primary text-primary">Sign In</button>
            <button onclick="switchTab('register')" id="tab-register" class="flex-1 py-6 text-sm font-bold uppercase tracking-widest transition-all border-b-2 border-transparent text-slate-400 hover:text-slate-600">Join Free</button>
        </div>

        <div class="p-8 md:p-10">
            <!-- Login Form -->
            <form id="form-login" method="POST" action="<?= $base ?>/auth" class="space-y-5">
                <input type="hidden" name="login" value="1">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                    <input type="email" name="email" required placeholder="your@email.com" class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-6 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/5">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-6 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/5">
                </div>
                <button type="submit" class="w-full btn-primary py-4 font-bold text-xs uppercase tracking-widest shadow-xl shadow-primary/20">Sign In to Account</button>
            </form>

            <!-- Register Form -->
            <form id="form-register" method="POST" action="<?= $base ?>/auth" class="space-y-5 hidden">
                <input type="hidden" name="register" value="1">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Full Name</label>
                    <input type="text" name="name" required placeholder="David Okilo" class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-6 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/5">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                    <input type="email" name="email" required placeholder="your@email.com" class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-6 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/5">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Password</label>
                    <input type="password" name="password" required placeholder="Min. 6 characters" class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-6 py-4 text-sm font-medium outline-none transition focus:border-primary focus:ring-4 focus:ring-primary/5">
                </div>
                <button type="submit" class="w-full btn-primary py-4 font-bold text-xs uppercase tracking-widest shadow-xl shadow-primary/20">Create My Account</button>
            </form>

            <p class="mt-8 text-center text-[10px] font-medium text-slate-400 leading-relaxed">
                By signing in, you agree to our <a href="#" class="underline hover:text-primary">Terms</a> and <a href="#" class="underline hover:text-primary">Privacy Policy</a>.
            </p>
        </div>
    </div>
</div>

<script>
function openAuthModal(mode = 'login') {
    const modal = document.getElementById('auth-modal');
    const content = document.getElementById('auth-content');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    content.classList.remove('scale-95');
    switchTab(mode);
}

function closeAuthModal() {
    const modal = document.getElementById('auth-modal');
    const content = document.getElementById('auth-content');
    modal.classList.add('opacity-0', 'pointer-events-none');
    content.classList.add('scale-95');
}

function switchTab(mode) {
    const loginForm = document.getElementById('form-login');
    const regForm = document.getElementById('form-register');
    const loginTab = document.getElementById('tab-login');
    const regTab = document.getElementById('tab-register');

    if (mode === 'login') {
        loginForm.classList.remove('hidden');
        regForm.classList.add('hidden');
        loginTab.classList.add('border-primary', 'text-primary');
        loginTab.classList.remove('border-transparent', 'text-slate-400');
        regTab.classList.remove('border-primary', 'text-primary');
        regTab.classList.add('border-transparent', 'text-slate-400');
    } else {
        loginForm.classList.add('hidden');
        regForm.classList.remove('hidden');
        regTab.classList.add('border-primary', 'text-primary');
        regTab.classList.remove('border-transparent', 'text-slate-400');
        loginTab.classList.remove('border-primary', 'text-primary');
        loginTab.classList.add('border-transparent', 'text-slate-400');
    }
}

// Global triggers
document.addEventListener('click', (e) => {
    if (e.target.hasAttribute('data-auth-trigger')) {
        e.preventDefault();
        openAuthModal(e.target.getAttribute('data-auth-trigger') || 'login');
    }
});
</script>
