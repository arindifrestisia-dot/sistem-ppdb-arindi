<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Kelulusan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#cfe0f8] text-slate-900">
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'status-lulus')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-6xl">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Status Kelulusan</h1>
                            <p class="mt-2 text-lg text-slate-500">Pantau informasi hasil seleksi ananda melalui panel orang tua.</p>
                        </div>
                        @if ($isSelectionPublished)
                            <span class="inline-flex rounded-2xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Hasil Seleksi Sudah Terbit</span>
                        @else
                            <span class="inline-flex rounded-2xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Masih Dalam Proses Seleksi</span>
                        @endif
                    </div>

                    @if (! $isSelectionPublished)
                        <section class="mt-8 rounded-[2rem] bg-white p-8 text-center shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-12">
                            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-amber-100 text-amber-500 shadow-inner shadow-amber-100/80">
                                <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 6v6l4 2"></path>
                                    <circle cx="12" cy="12" r="9"></circle>
                                </svg>
                            </div>
                            <h2 class="mt-6 text-2xl font-bold text-blue-950 md:text-3xl">Panitia PPDB sedang melakukan proses seleksi</h2>
                            <p class="mx-auto mt-3 max-w-2xl text-base leading-7 text-slate-500 md:text-lg">
                                Hasil kelulusan belum dipublikasikan. Silakan pantau halaman ini secara berkala untuk melihat pengumuman resmi dari panitia.
                            </p>
                        </section>
                    @else
                        <section class="mt-8 overflow-hidden rounded-[2.25rem] bg-white shadow-[0_18px_45px_rgba(15,23,42,0.14)] ring-1 ring-slate-200">
                            <div class="border-b border-slate-200 px-6 py-6 md:px-10">
                                <div class="flex items-center justify-center gap-3 text-center">
                                    <svg class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5l-8-3Zm0 3.2 5 1.9V11c0 3.6-2.2 7-5 8.3-2.8-1.3-5-4.7-5-8.3V7.1l5-1.9Z"></path>
                                    </svg>
                                    <h2 class="text-lg font-bold uppercase tracking-[0.22em] text-slate-600 md:text-2xl">Hasil Seleksi PPDB Online</h2>
                                </div>
                            </div>

                            <div class="px-6 py-16 md:px-10 md:py-20">
                                <div class="mx-auto max-w-3xl text-center">
                                    <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 shadow-[0_25px_45px_rgba(34,197,94,0.18)]">
                                        <svg class="h-16 w-16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M3 10.5A1.5 1.5 0 0 1 4.5 9H8l7.4-4.9A1 1 0 0 1 17 4.9V19a1 1 0 0 1-1.6.8L8 15H6.5l1.1 4.3A1.5 1.5 0 0 1 6.2 21H5.1a1.5 1.5 0 0 1-1.45-1.1L2.4 15.2A1.5 1.5 0 0 1 1 13.7v-1.7A1.5 1.5 0 0 1 3 10.5Zm16 1a1 1 0 0 1 0-2 3 3 0 0 1 0 6 1 1 0 0 1 0-2 1 1 0 1 0 0-2Z"></path>
                                        </svg>
                                    </div>

                                    <h3 class="mt-10 text-3xl font-bold text-emerald-500 md:text-5xl">Hasil Seleksi Sudah Terbit!</h3>

                                    <button type="button" id="revealGraduationStatusButton" class="mt-8 inline-flex rounded-full bg-blue-500 px-8 py-4 text-lg font-extrabold uppercase tracking-wide text-white shadow-[0_18px_40px_rgba(59,130,246,0.35)] transition hover:bg-blue-600">
                                        Lihat Status Kelulusan
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section id="detail-status" class="hidden mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">Detail Hasil</p>
                                    <h2 class="mt-2 text-2xl font-bold text-blue-950">{{ $registration?->full_name ?: Auth::user()->name }}</h2>
                                    <p class="mt-2 text-slate-500">Hasil seleksi resmi telah dipublikasikan.</p>
                                </div>
                                <span class="inline-flex rounded-2xl px-4 py-2 text-sm font-bold {{ $selectionResultTone === 'emerald' ? 'bg-emerald-100 text-emerald-700' : ($selectionResultTone === 'rose' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $selectionResultLabel }}
                                </span>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                <div class="rounded-3xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Nomor Pendaftaran</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ $registration?->registration_number ?: '-' }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Tanggal Terbit</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ optional($registration?->selection_published_at)->translatedFormat('d F Y') ?: '-' }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Status Akhir</p>
                                    <p class="mt-2 text-lg font-bold {{ $selectionResultTone === 'emerald' ? 'text-emerald-600' : ($selectionResultTone === 'rose' ? 'text-rose-600' : 'text-slate-800') }}">{{ $selectionResultLabel }}</p>
                                </div>
                            </div>

                            @if ($canPayReRegistration)
                                <div class="mt-6 rounded-3xl bg-emerald-50 p-5">
                                    <p class="font-semibold text-emerald-800">Selamat, ananda dinyatakan lulus seleksi.</p>
                                    <p class="mt-2 text-sm leading-7 text-emerald-700">
                                        Silakan lakukan daftar ulang untuk melanjutkan proses penerimaan peserta didik.
                                    </p>
                                    <a
                                        href="{{ route('daftar-ulang') }}"
                                        class="mt-4 inline-flex rounded-full bg-yellow-300 px-6 py-3 text-sm font-bold text-blue-950 transition hover:bg-yellow-200"
                                    >
                                        Daftar Ulang
                                    </a>
                                </div>
                            @endif
                        </section>
                    @endif
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>

    @if ($isSelectionPublished)
        <script>
            const revealGraduationStatusButton = document.getElementById('revealGraduationStatusButton');
            const detailStatusSection = document.getElementById('detail-status');

            const revealSection = (section) => {
                section?.classList.remove('hidden');
                section?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            };

            revealGraduationStatusButton?.addEventListener('click', () => {
                revealSection(detailStatusSection);
            });

            if (window.location.hash === '#detail-status') {
                revealSection(detailStatusSection);
            }

        </script>
    @endif
</body>
</html>
