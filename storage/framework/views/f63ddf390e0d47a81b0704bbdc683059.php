<?php
    $party = $party ?? config('party');
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($party['app_name']); ?> - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    <script>
        (function() {
            const saved  = localStorage.getItem('theme') || 'dark';
            const isDark = saved === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="icon" type="image/png" href="<?php echo e(asset($party['assets']['logo'])); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Bebas+Neue&display=swap" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-mono2 { font-family: 'Plus Jakarta Sans', monospace; }

        :root {
            --color-brand: <?php echo e($party['colors']['primary']); ?>;
            --color-brand-dark: <?php echo e($party['colors']['primary_dark']); ?>;
            --color-brand-soft: <?php echo e($party['colors']['primary_soft']); ?>;
            --color-role-korcam: <?php echo e($party['colors']['korcam']); ?>;
            --color-role-kordes: <?php echo e($party['colors']['kordes']); ?>;
            --color-role-saksi-tps: <?php echo e($party['colors']['saksi_tps']); ?>;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        .dark ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        html:not(.dark) ::-webkit-scrollbar-thumb { background: #D1D5DB; border-radius: 4px; }

        .role-korcam   { background: color-mix(in srgb, var(--color-role-korcam) 15%, transparent); color: var(--color-role-korcam); border: 1px solid color-mix(in srgb, var(--color-role-korcam) 35%, transparent); }
        .role-kordes   { background: color-mix(in srgb, var(--color-role-kordes) 15%, transparent); color: var(--color-role-kordes); border: 1px solid color-mix(in srgb, var(--color-role-kordes) 35%, transparent); }
        .role-saksi_tps  { background: color-mix(in srgb, var(--color-role-saksi-tps) 15%, transparent); color: var(--color-role-saksi-tps); border: 1px solid color-mix(in srgb, var(--color-role-saksi-tps) 35%, transparent); }
        .role-admin_partai { background: color-mix(in srgb, var(--color-brand) 15%, transparent); color: var(--color-brand); border: 1px solid color-mix(in srgb, var(--color-brand) 35%, transparent); }
        .rekap-table-scroll {
            max-height: calc(100vh - 5rem);
            overflow: auto;
        }
        .rekap-sticky-header thead th {
            position: sticky;
            top: 0;
            z-index: 30;
            background: #fff;
        }
        .dark .rekap-sticky-header thead th {
            background: #1f2937;
        }
    </style>
</head>

<body class="<?php echo $__env->yieldContent('body_class', 'dark:bg-gray-950 bg-slate-200 dark:text-gray-100 text-gray-800 min-h-screen transition-colors duration-200'); ?>">

<?php if(! View::hasSection('fullscreen')): ?>

<header class="sticky top-0 z-50 dark:bg-gray-900 bg-white border-b dark:border-gray-800 border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 h-16 flex items-center justify-between gap-4">

        
        <?php
            $homeRoute = match (Auth::user()->role) {
                'admin_partai' => 'dashboard.admin_partai',
                'korcam' => 'dashboard.korcam',
                'kordes' => 'dashboard.kordes',
                'saksi_tps' => 'dashboard.saksi',
                default => 'login',
            };
        ?>
        <a href="<?php echo e(route($homeRoute)); ?>" class="flex items-center gap-3 flex-shrink-0">
            <div class="w-8 h-8 flex items-center justify-center rounded overflow-hidden">
                <img src="<?php echo e(asset($party['assets']['logo'])); ?>" alt="<?php echo e($party['app_name']); ?>" class="w-full h-full object-contain">
            </div>
            <div class="hidden sm:block">
                <p class="font-display text-lg leading-none dark:text-white text-gray-900 tracking-wide"><?php echo e($party['app_name']); ?></p>
                <p class="text-[9px] dark:text-gray-500 text-gray-400 tracking-widest uppercase"><?php echo e($party['tagline']); ?></p>
            </div>
        </a>

        
        <p class="hidden lg:block text-sm font-medium dark:text-gray-400 text-gray-500 flex-1 text-center">
            <?php echo $__env->yieldContent('title', 'Dashboard'); ?>
        </p>

        
        <div class="flex items-center gap-2 flex-shrink-0">

            
            <button onclick="toggleTheme()"
                    class="p-2 rounded-lg dark:text-gray-400 text-gray-500 dark:hover:bg-gray-800 hover:bg-gray-100 transition">
                <svg id="icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg id="icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            
            <div class="w-px h-6 dark:bg-gray-700 bg-gray-200"></div>

            
            <div class="flex items-center gap-2.5">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-semibold dark:text-gray-200 text-gray-700 leading-tight"><?php echo e(Auth::user()->name); ?></p>
                    <span class="inline-block text-[9px] px-1.5 py-0.5 rounded-sm tracking-widest uppercase font-semibold role-<?php echo e(Auth::user()->role); ?>">
                        <?php echo e(strtoupper(Auth::user()->role)); ?>

                    </span>
                </div>
                <div class="w-8 h-8 rounded-full bg-[var(--color-brand-soft)] flex items-center justify-center border dark:border-[var(--color-brand)]/20 border-[var(--color-brand)]/10">
                    <span class="text-[var(--color-brand)] text-xs font-bold">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                    </span>
                </div>
            </div>

            
            <div class="w-px h-6 dark:bg-gray-700 bg-gray-200"></div>

            <a href="<?php echo e(route('password.edit')); ?>"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium dark:text-gray-400 text-gray-500 dark:hover:bg-gray-800 hover:bg-gray-100 hover:text-[var(--color-brand)] dark:hover:text-[var(--color-brand)] transition">
                <span class="hidden sm:inline">Password</span>
            </a>

            
            <div class="w-px h-6 dark:bg-gray-700 bg-gray-200"></div>

            
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium dark:text-gray-400 text-gray-500 dark:hover:bg-gray-800 hover:bg-gray-100 hover:text-[var(--color-brand)] dark:hover:text-[var(--color-brand)] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>

        </div>
    </div>
</header>
<?php endif; ?>


<?php if(View::hasSection('fullscreen')): ?>
    <?php echo $__env->yieldContent('content'); ?>
<?php else: ?>
<main class="max-w-7xl mx-auto px-4 lg:px-8 py-6">
    <?php echo $__env->yieldContent('content'); ?>
</main>
<footer class="max-w-7xl mx-auto px-4 lg:px-8 pb-6">
    <p class="text-center text-[11px] dark:text-gray-600 text-gray-500">
        &copy; <?php echo e($party['copyright_year']); ?> <?php echo e($party['name']); ?>

    </p>
</footer>
<?php endif; ?>


<div id="pdf-modal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
    <div class="dark:bg-gray-900 bg-white border dark:border-gray-700 border-gray-200 w-full max-w-5xl h-[90vh] flex flex-col rounded-lg overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-5 py-3 border-b dark:border-gray-700 border-gray-200 flex-shrink-0">
            <span class="text-sm font-semibold dark:text-gray-300 text-gray-600">Preview File</span>
            <button onclick="closePreview()"
                    class="px-3 py-1.5 rounded-lg text-xs dark:text-gray-400 text-gray-500 dark:hover:bg-gray-800 hover:bg-gray-100 transition">
                ✕ Tutup
            </button>
        </div>
        <iframe id="pdf-frame" src="" class="flex-1 w-full bg-white"></iframe>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>

<script>
    function updateThemeIcon(isDark) {
        const sun  = document.getElementById('icon-sun');
        const moon = document.getElementById('icon-moon');
        if (!sun || !moon) return;
        sun.classList.toggle('hidden', !isDark);
        moon.classList.toggle('hidden', isDark);
    }

    function toggleTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        document.documentElement.classList.toggle('dark', !isDark);
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
        updateThemeIcon(!isDark);
    }

    // Set icon sesuai tema saat ini
    updateThemeIcon(document.documentElement.classList.contains('dark'));

    // PDF Preview
    function openPreview(url) {
        document.getElementById('pdf-frame').src = url;
        document.getElementById('pdf-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closePreview() {
        document.getElementById('pdf-frame').src = '';
        document.getElementById('pdf-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.getElementById('pdf-modal').addEventListener('click', function(e) {
        if (e.target === this) closePreview();
    });

    // ── Override window.confirm dengan toast modal ──
    window.confirm = function(message) {
        return new Promise((resolve) => {
            const modal     = document.getElementById('toast-confirm');
            const msg       = document.getElementById('toast-confirm-msg');
            const btnOk     = document.getElementById('toast-confirm-ok');
            const btnCancel = document.getElementById('toast-confirm-cancel');
            const backdrop  = document.getElementById('toast-confirm-backdrop');

            msg.textContent = message;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            function close(result) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                btnOk.removeEventListener('click', onOk);
                btnCancel.removeEventListener('click', onCancel);
                backdrop.removeEventListener('click', onCancel);
                resolve(result);
            }

            const onOk     = () => close(true);
            const onCancel = () => close(false);

            btnOk.addEventListener('click', onOk);
            btnCancel.addEventListener('click', onCancel);
            backdrop.addEventListener('click', onCancel);
        });
    };

    // ── Intercept semua form onsubmit yang pakai confirm (async-safe) ──
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            const attr = form.getAttribute('onsubmit');
            if (!attr || !attr.includes('confirm')) return;

            const match   = attr.match(/confirm\(['"](.*?)['"']\)/s);
            const message = match ? match[1] : 'Yakin ingin menghapus data ini?';

            form.removeAttribute('onsubmit');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const ok = await window.confirm(message);
                if (ok) this.submit();
            });
        });
    });
</script>


<div id="toast-confirm" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="toast-confirm-backdrop"></div>
    <div class="relative dark:bg-gray-800 bg-white rounded-2xl shadow-2xl border dark:border-gray-700 border-gray-200 w-full max-w-sm p-6">
        <div class="flex items-start gap-4 mb-5">
            <div class="w-10 h-10 rounded-full bg-red-500/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold dark:text-gray-100 text-gray-800 text-sm mb-1">Konfirmasi Hapus</p>
                <p id="toast-confirm-msg" class="text-xs dark:text-gray-400 text-gray-500 leading-relaxed"></p>
            </div>
        </div>
        <div class="flex gap-2">
            <button id="toast-confirm-cancel"
                    class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold border dark:border-gray-600 border-gray-300
                           dark:text-gray-400 text-gray-500 dark:hover:bg-gray-700 hover:bg-gray-100 transition">
                Batal
            </button>
            <button id="toast-confirm-ok"
                    class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold bg-red-500 hover:bg-red-600 text-white transition">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\laragon\www\simap-partai-template\resources\views/layouts/app.blade.php ENDPATH**/ ?>