<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Panitia' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="overflow-x-hidden bg-slate-100 text-slate-900">
    @php
        $contentRouteModel = request()->route('content');
        $activeContentType = request()->query('type')
            ?: ($contentRouteModel?->type ?? null)
            ?: \App\Models\SchoolContent::TYPE_INFORMATION;
        $contentMenuItems = \App\Models\SchoolContent::typeOptions();
        $activeStudentSegment = request()->query('segment', 'saat_ini');
        $studentMenuItems = [
            'saat_ini' => 'Siswa Aktif',
            'calon' => 'Data Calon Siswa',
            'daftar_ulang' => 'Daftar Ulang',
            'ditolak' => 'Siswa Ditolak',
        ];
        $sidebarIcon = static function (string $key): \Illuminate\Support\HtmlString {
            $svg = match ($key) {
                'dashboard' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="11" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="18" width="7" height="3" rx="1.5"></rect>
                    </svg>
                SVG,
                'users' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="8" r="3"></circle>
                        <path d="M3.5 19a5.5 5.5 0 0 1 11 0"></path>
                        <path d="M16 11h5"></path>
                        <path d="M18.5 8.5v5"></path>
                    </svg>
                SVG,
                'berita' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="5" width="16" height="14" rx="2"></rect>
                        <path d="M8 9h8"></path>
                        <path d="M8 13h5"></path>
                    </svg>
                SVG,
                'prestasi' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 5h8"></path>
                        <path d="M7 5H5l1.5 4A4.5 4.5 0 0 0 11 12"></path>
                        <path d="M17 5h2l-1.5 4A4.5 4.5 0 0 1 13 12"></path>
                        <path d="M12 12v4"></path>
                        <path d="M9 20h6"></path>
                        <path d="M10 16h4"></path>
                    </svg>
                SVG,
                'galeri' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="5" width="16" height="14" rx="2"></rect>
                        <circle cx="9" cy="10" r="1.5"></circle>
                        <path d="m20 16-4.5-4.5L8 19"></path>
                    </svg>
                SVG,
                'kegiatan' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                        <path d="M8 3v4"></path>
                        <path d="M16 3v4"></path>
                        <path d="M3 10h18"></path>
                    </svg>
                SVG,
                'fasilitas' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 20V9.5L12 4l8 5.5V20"></path>
                        <path d="M9 20v-5h6v5"></path>
                    </svg>
                SVG,
                'tenaga_pendidik' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5.5 19a6.5 6.5 0 0 1 13 0"></path>
                    </svg>
                SVG,
                'testimoni' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 17.5 3.5 21l4-1.5A9 9 0 1 0 5 17.5Z"></path>
                        <path d="M8 10h8"></path>
                        <path d="M8 14h5"></path>
                    </svg>
                SVG,
                'saat_ini' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="8" r="3"></circle>
                        <path d="M3.5 18a5.5 5.5 0 0 1 11 0"></path>
                        <circle cx="17.5" cy="9.5" r="2.5"></circle>
                    </svg>
                SVG,
                'calon' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M7 4h7l5 5v11H7z"></path>
                        <path d="M14 4v5h5"></path>
                        <path d="M10 13h6"></path>
                        <path d="M10 17h4"></path>
                    </svg>
                SVG,
                'daftar_ulang' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="8"></circle>
                        <path d="M12 8v4l3 2"></path>
                        <path d="m9 10 1.5 1.5L15 7"></path>
                    </svg>
                SVG,
                'ditolak' => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="8"></circle>
                        <path d="m9 9 6 6"></path>
                        <path d="m15 9-6 6"></path>
                    </svg>
                SVG,
                default => <<<'SVG'
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="8"></circle>
                    </svg>
                SVG,
            };

            return new \Illuminate\Support\HtmlString($svg);
        };
        $isInterviewMenuActive = request()->routeIs('panitia.interviews.*');
        $isParentFormFieldsActive = request()->routeIs('panitia.parent-form-fields.*');
        $isFinanceFormPaymentsActive = request()->routeIs('panitia.finances.form-payments.*');
        $isFinanceReRegistrationsActive = request()->routeIs('panitia.finances.re-registrations.*');
    @endphp
    <div class="flex min-h-screen">
        <button
            id="panitia-sidebar-backdrop"
            type="button"
            class="fixed inset-0 z-40 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"
            aria-label="Tutup menu navigasi"
            onclick="togglePanitiaSidebar(false)"
        ></button>

        <aside
            id="panitia-sidebar"
            class="fixed inset-y-0 left-0 z-50 w-[min(18rem,86vw)] -translate-x-full overflow-y-auto bg-slate-950 text-white shadow-2xl transition-transform duration-300 lg:sticky lg:top-0 lg:z-20 lg:min-h-screen lg:w-72 lg:shrink-0 lg:translate-x-0 lg:shadow-none"
        >
            <div class="flex items-start justify-between gap-4 border-b border-slate-800 px-6 py-6">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">Dashboard Panitia</p>
                    <h1 class="mt-2 text-2xl font-extrabold">PPDB RA Fadhilah</h1>
                    <p class="mt-2 text-sm text-slate-300">Kelola pendaftaran, verifikasi berkas, dan informasi sekolah.</p>
                </div>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl lg:hidden"
                    aria-label="Tutup menu"
                    onclick="togglePanitiaSidebar(false)"
                >
                    &times;
                </button>
            </div>

            <nav class="space-y-2 px-4 py-6 text-sm font-semibold">
                <a href="{{ route('panitia.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('panitia.dashboard') ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}">
                    {!! $sidebarIcon('dashboard') !!}
                    <span>Beranda</span>
                </a>

                <a href="{{ route('panitia.users.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('panitia.users.*') ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}">
                    {!! $sidebarIcon('users') !!}
                    <span>Manajemen User</span>
                </a>

                <div class="pt-4">
                    <p class="px-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Informasi Sekolah</p>
                    <div class="mt-3 space-y-1">
                        @foreach ($contentMenuItems as $typeKey => $label)
                            <a
                                href="{{ route('panitia.contents.index', ['type' => $typeKey]) }}"
                                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('panitia.contents.*') && $activeContentType === $typeKey ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}"
                            >
                                {!! $sidebarIcon($typeKey) !!}
                                <span>{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4">
                    <p class="px-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Data Siswa</p>
                    <div class="mt-3 space-y-1">
                        @foreach ($studentMenuItems as $segmentKey => $label)
                            <a
                                href="{{ route('panitia.registrations.index', ['segment' => $segmentKey]) }}"
                                class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ request()->routeIs('panitia.registrations.*') && $activeStudentSegment === $segmentKey ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}"
                            >
                                {!! $sidebarIcon($segmentKey) !!}
                                <span>{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4">
                    <p class="px-4 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Formulir</p>
                    <div class="mt-3 space-y-1">
                        <a
                            href="{{ route('panitia.interviews.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ $isInterviewMenuActive ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                <path d="M16 2v4"></path>
                                <path d="M8 2v4"></path>
                                <path d="M3 10h18"></path>
                                <path d="M9 14h.01"></path>
                                <path d="M12 14h.01"></path>
                                <path d="M15 14h.01"></path>
                            </svg>
                            <span>Jadwal Wawancara</span>
                        </a>

                        <a
                            href="{{ route('panitia.parent-form-fields.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ $isParentFormFieldsActive ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 3h6"></path>
                                <rect x="5" y="3" width="14" height="18" rx="2"></rect>
                                <path d="M8 9h8"></path>
                                <path d="M8 13h8"></path>
                                <path d="M8 17h5"></path>
                            </svg>
                            <span>Formulir Orang Tua</span>
                        </a>

                        <a
                            href="{{ route('panitia.finances.form-payments.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ $isFinanceFormPaymentsActive ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="6" width="18" height="14" rx="2"></rect>
                                <path d="M8 6V4"></path>
                                <path d="M16 6V4"></path>
                                <path d="M3 10h18"></path>
                            </svg>
                            <span>Bayar Formulir</span>
                        </a>

                        <a
                            href="{{ route('panitia.finances.re-registrations.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 {{ $isFinanceReRegistrationsActive ? 'bg-slate-800 text-amber-300 ring-1 ring-amber-300/30' : 'text-slate-200 hover:bg-slate-800' }}"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 1v22"></path>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span>Pembayaran Daftar Ulang</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('profile.dashboard') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-slate-800">Lihat Website Publik</a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex items-center justify-between gap-3 border-b border-slate-200/80 bg-white/95 px-4 py-3 shadow-sm backdrop-blur sm:px-6 lg:px-8 lg:py-4">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-950 text-2xl text-white shadow-sm lg:hidden"
                        aria-label="Buka menu navigasi"
                        aria-controls="panitia-sidebar"
                        onclick="togglePanitiaSidebar(true)"
                    >
                        &#8801;
                    </button>
                    <div class="min-w-0">
                        <p class="truncate text-xs font-semibold uppercase tracking-[0.14em] text-sky-700 sm:text-sm sm:tracking-[0.2em]">{{ $title ?? 'Dashboard Panitia' }}</p>
                        <p class="mt-1 hidden truncate text-sm text-slate-500 sm:block">Portal operasional panitia PPDB.</p>
                    </div>
                </div>

                <div id="panitia-user-menu" class="relative shrink-0">
                    <button
                        id="panitia-user-menu-button"
                        type="button"
                        class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-left shadow-sm transition hover:border-sky-300 hover:bg-sky-50"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-controls="panitia-user-dropdown"
                    >
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-sky-600 text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 21a8 8 0 0 1 16 0Z"></path>
                            </svg>
                        </span>
                        <span class="hidden max-w-36 truncate font-semibold text-slate-800 sm:block">{{ auth()->user()->name }}</span>
                        <svg id="panitia-user-menu-chevron" class="h-4 w-4 text-slate-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </button>

                    <div
                        id="panitia-user-dropdown"
                        class="absolute right-0 top-full z-50 mt-2 hidden w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"
                    >
                        <div class="flex items-center gap-3 border-b border-slate-100 p-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-sky-600 text-white">
                                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path d="M4 21a8 8 0 0 1 16 0Z"></path>
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="truncate font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-slate-500">Admin PPDB</p>
                            </div>
                        </div>

                        <div class="p-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-xl bg-rose-50 px-3 py-3 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M10 17l5-5-5-5"></path>
                                        <path d="M15 12H3"></path>
                                        <path d="M14 3h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5"></path>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
        function togglePanitiaSidebar(open) {
            const sidebar = document.getElementById('panitia-sidebar');
            const backdrop = document.getElementById('panitia-sidebar-backdrop');

            if (!sidebar || !backdrop) {
                return;
            }

            sidebar.classList.toggle('-translate-x-full', !open);
            backdrop.classList.toggle('hidden', !open);
            document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                togglePanitiaSidebar(false);
            }
        });

        const panitiaUserMenu = document.getElementById('panitia-user-menu');
        const panitiaUserMenuButton = document.getElementById('panitia-user-menu-button');
        const panitiaUserDropdown = document.getElementById('panitia-user-dropdown');
        const panitiaUserMenuChevron = document.getElementById('panitia-user-menu-chevron');

        function togglePanitiaUserMenu(open) {
            panitiaUserDropdown?.classList.toggle('hidden', !open);
            panitiaUserMenuChevron?.classList.toggle('rotate-180', open);
            panitiaUserMenuButton?.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        panitiaUserMenuButton?.addEventListener('click', () => {
            togglePanitiaUserMenu(panitiaUserDropdown?.classList.contains('hidden'));
        });

        document.addEventListener('click', (event) => {
            if (panitiaUserMenu && !panitiaUserMenu.contains(event.target)) {
                togglePanitiaUserMenu(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                togglePanitiaUserMenu(false);
                panitiaUserMenuButton?.focus();
            }
        });
    </script>
</body>
</html>
