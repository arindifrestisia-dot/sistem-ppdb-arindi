<header class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-slate-200/80 bg-white/95 px-4 py-3 shadow-sm backdrop-blur md:px-8 md:py-4">
    <div class="flex min-w-0 items-center gap-3">
        <button
            type="button"
            class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-950 text-2xl text-white shadow-sm md:hidden"
            aria-label="Buka menu navigasi"
            aria-controls="parent-sidebar"
            onclick="toggleParentSidebar(true)"
        >
            &#8801;
        </button>
        <div class="min-w-0">
            <p class="truncate text-sm font-extrabold text-blue-950 sm:text-base">Portal Orang Tua</p>
            <p class="hidden truncate text-xs text-slate-500 sm:block">SPMB RA Fadhilah Pekanbaru</p>
        </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="rounded-full bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100">Keluar</button>
    </form>
</header>

<script>
    function toggleParentSidebar(open) {
        const sidebar = document.getElementById('parent-sidebar');
        const backdrop = document.getElementById('parent-sidebar-backdrop');

        if (!sidebar || !backdrop) {
            return;
        }

        sidebar.classList.toggle('-translate-x-full', !open);
        backdrop.classList.toggle('hidden', !open);
        document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 768);
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            toggleParentSidebar(false);
        }
    });
</script>
