<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Kepsek' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="overflow-x-hidden bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        <button
            id="kepsek-sidebar-backdrop"
            type="button"
            class="fixed inset-0 z-40 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"
            aria-label="Tutup menu navigasi"
            onclick="toggleKepsekSidebar(false)"
        ></button>

        <aside
            id="kepsek-sidebar"
            class="fixed inset-y-0 left-0 z-50 w-[min(18rem,86vw)] -translate-x-full overflow-y-auto bg-blue-950 text-white shadow-2xl transition-transform duration-300 lg:sticky lg:top-0 lg:z-20 lg:min-h-screen lg:w-72 lg:shrink-0 lg:translate-x-0 lg:shadow-none"
        >
            <div class="flex items-start justify-between gap-4 border-b border-blue-900 px-6 py-6">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">Dashboard Kepsek</p>
                    <h1 class="mt-2 text-2xl font-extrabold">PPDB RA Fadhilah</h1>
                    <p class="mt-2 text-sm text-slate-300">Pantau ringkasan pendaftaran dan perkembangan informasi sekolah.</p>
                </div>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl lg:hidden"
                    aria-label="Tutup menu"
                    onclick="toggleKepsekSidebar(false)"
                >
                    &times;
                </button>
            </div>

            <div class="border-b border-blue-900 px-6 py-5">
                <p class="text-lg font-bold">{{ auth()->user()->name }}</p>
                <p class="text-sm text-slate-300">{{ auth()->user()->email }}</p>
                <span class="mt-3 inline-flex rounded-full bg-amber-300 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-blue-950">Kepsek</span>
            </div>

            <nav class="space-y-2 px-4 py-6 text-sm font-semibold">
                <a href="{{ route('kepsek.dashboard') }}" class="block rounded-2xl px-4 py-3 {{ request()->routeIs('kepsek.dashboard') ? 'bg-amber-300 text-blue-950' : 'text-slate-200 hover:bg-blue-900' }}">Beranda</a>
                <a href="{{ route('profile.dashboard') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-blue-900">Lihat Website Publik</a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex items-center justify-between gap-3 border-b border-slate-200/80 bg-white/95 px-4 py-3 shadow-sm backdrop-blur sm:px-6 lg:px-8 lg:py-4">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-950 text-2xl text-white shadow-sm lg:hidden"
                        aria-label="Buka menu navigasi"
                        aria-controls="kepsek-sidebar"
                        onclick="toggleKepsekSidebar(true)"
                    >
                        &#8801;
                    </button>
                    <div class="min-w-0">
                        <p class="truncate text-xs font-semibold uppercase tracking-[0.14em] text-sky-700 sm:text-sm sm:tracking-[0.2em]">{{ $title ?? 'Dashboard Kepsek' }}</p>
                        <p class="mt-1 hidden truncate text-sm text-slate-500 sm:block">Portal monitoring kepala sekolah.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-100 sm:px-5">Keluar</button>
                </form>
            </header>

            <main class="min-w-0 flex-1 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
        function toggleKepsekSidebar(open) {
            const sidebar = document.getElementById('kepsek-sidebar');
            const backdrop = document.getElementById('kepsek-sidebar-backdrop');

            if (!sidebar || !backdrop) {
                return;
            }

            sidebar.classList.toggle('-translate-x-full', !open);
            backdrop.classList.toggle('hidden', !open);
            document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                toggleKepsekSidebar(false);
            }
        });
    </script>
</body>
</html>
