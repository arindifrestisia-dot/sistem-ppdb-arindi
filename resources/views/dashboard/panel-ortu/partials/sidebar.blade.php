<button
    id="parent-sidebar-backdrop"
    type="button"
    class="fixed inset-0 z-40 hidden bg-slate-950/60 backdrop-blur-sm md:hidden"
    aria-label="Tutup menu navigasi"
    onclick="toggleParentSidebar(false)"
></button>

<aside
    id="parent-sidebar"
    class="fixed inset-y-0 left-0 z-50 w-[min(18rem,86vw)] -translate-x-full overflow-y-auto bg-blue-950 text-white shadow-2xl transition-transform duration-300 md:sticky md:top-0 md:z-20 md:min-h-screen md:w-72 md:shrink-0 md:translate-x-0 md:shadow-none"
>
    @php($studentRegistration = Auth::user()->studentRegistration)
    @php($formMenuRoute = Auth::user()->hasPaidPpdbForm() ? route('data-diri') : route('ortu.formulir'))
    <div class="flex items-start justify-between gap-4 border-b border-blue-900 px-5 py-5">
        <div>
            <h1 class="text-xl font-extrabold text-yellow-300">PPDB RA FADHILAH</h1>
            <p class="text-sm text-sky-100">(Penerimaan Peserta Didik Baru)</p>
        </div>
        <button
            type="button"
            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-xl text-white md:hidden"
            aria-label="Tutup menu"
            onclick="toggleParentSidebar(false)"
        >
            &times;
        </button>
    </div>

    <div class="border-b border-blue-800 px-5 py-6 text-center">
        @if ($studentRegistration?->child_photo_path)
            <img
                src="{{ asset('storage/' . $studentRegistration->child_photo_path) }}"
                alt="Foto anak"
                class="mx-auto h-20 w-20 rounded-full border-4 border-white/20 object-cover"
            >
        @else
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/20 text-xs font-medium text-sky-100">
                User Image
            </div>
        @endif

        <p class="mt-4 text-base font-bold">{{ $studentRegistration?->full_name ?: Auth::user()->name }}</p>
        <span class="mt-2 inline-flex rounded-md bg-yellow-300 px-3 py-1 text-xs font-extrabold uppercase text-blue-900">User</span>
    </div>

    <nav class="px-4 py-6 text-sm font-semibold">
        <p class="px-3 text-xs font-bold uppercase tracking-[0.25em] text-yellow-300">Menu Utama</p>
        <div class="mt-4 space-y-2">
            <a href="{{ route('panel.ortu') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ ($activeMenu ?? '') === 'beranda' ? 'bg-yellow-400 text-blue-950' : 'text-sky-100 hover:bg-blue-800' }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 11.5 12 4l9 7.5"></path>
                    <path d="M5 10.5V20h14v-9.5"></path>
                </svg>
                Beranda
            </a>
            <a href="{{ $formMenuRoute }}" class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ ($activeMenu ?? '') === 'formulir' ? 'bg-yellow-400 text-blue-950' : 'text-sky-100 hover:bg-blue-800' }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 3h6"></path>
                    <path d="M10 7h4"></path>
                    <rect x="6" y="3" width="12" height="18" rx="2"></rect>
                    <path d="M9 12h6"></path>
                    <path d="M9 16h6"></path>
                </svg>
                Formulir
            </a>
            <a href="{{ route('data-diri') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ ($activeMenu ?? '') === 'data-diri' ? 'bg-yellow-400 text-blue-950' : 'text-sky-100 hover:bg-blue-800' }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="3.5"></circle>
                    <path d="M5.5 19a6.5 6.5 0 0 1 13 0"></path>
                </svg>
                Data Diri
            </a>
            <a href="{{ route('wawancara') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ ($activeMenu ?? '') === 'wawancara' ? 'bg-yellow-400 text-blue-950' : 'text-sky-100 hover:bg-blue-800' }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                    <path d="M8 3v4"></path>
                    <path d="M16 3v4"></path>
                    <path d="M3 10h18"></path>
                    <path d="m9 14 2 2 4-4"></path>
                </svg>
                Wawancara
            </a>
            <a href="{{ route('status-lulus') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ ($activeMenu ?? '') === 'status-lulus' ? 'bg-yellow-400 text-blue-950' : 'text-sky-100 hover:bg-blue-800' }}">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 12.5 11 14.5 15.5 10"></path>
                    <circle cx="12" cy="12" r="8"></circle>
                </svg>
                Status Lulus
            </a>
            @if ($studentRegistration?->selection_result === 'lulus' && $studentRegistration?->selection_published_at)
                <a href="{{ route('status-lulus') }}#pendaftaran-ulang" class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ ($activeMenu ?? '') === 'pendaftaran-ulang' ? 'bg-yellow-400 text-blue-950' : 'text-sky-100 hover:bg-blue-800' }}">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <path d="M3 10h18"></path>
                        <path d="M7 15h4"></path>
                    </svg>
                    Pendaftaran Ulang
                </a>
            @endif
        </div>
    </nav>
</aside>
