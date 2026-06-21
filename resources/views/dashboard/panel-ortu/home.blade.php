<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#cfe0f8] text-slate-900">
    @php
        $registration = Auth::user()->studentRegistration;
        $isFormPaid = Auth::user()->hasPaidPpdbForm();
        $isDataSubmitted = (bool) $registration?->submitted_at;
        $isInterviewSelected = (bool) $registration?->interview_selected_at;
        $isSelectionPublished = (bool) ($registration && $registration->selection_published_at);
        $isPassed = $isSelectionPublished && $registration?->selection_result === 'lulus';
        $isReRegistrationPaid = (bool) $registration?->reregistration_paid_at;
        $formRoute = route('ortu.formulir');
        $dataRoute = $isFormPaid ? route('data-diri') : $formRoute;

        $registrationStages = [
            [
                'number' => 1,
                'status' => $isFormPaid ? 'Lunas' : 'Belum',
                'title' => 'Pembelian Formulir',
                'link' => $formRoute,
                'action' => $isFormPaid ? 'Lihat status pembayaran ->' : 'Beli formulir ->',
                'active' => true,
                'tone' => $isFormPaid ? 'emerald' : 'amber',
            ],
            [
                'number' => 2,
                'status' => $isDataSubmitted ? 'Selesai' : 'Belum',
                'title' => 'Pengisian Data Diri',
                'link' => $dataRoute,
                'action' => $isFormPaid ? 'Buka data diri ->' : 'Lunasi formulir dulu ->',
                'active' => $isFormPaid,
                'tone' => $isDataSubmitted ? 'emerald' : ($isFormPaid ? 'blue' : 'slate'),
            ],
            [
                'number' => 3,
                'status' => $isInterviewSelected ? 'Terpilih' : 'Belum',
                'title' => 'Pemilihan Jadwal Wawancara',
                'link' => route('wawancara'),
                'action' => $isInterviewSelected ? 'Lihat jadwal ->' : ($isDataSubmitted ? 'Pilih jadwal ->' : 'Lengkapi data dulu ->'),
                'active' => $isDataSubmitted,
                'tone' => $isInterviewSelected ? 'emerald' : ($isDataSubmitted ? 'blue' : 'slate'),
            ],
            [
                'number' => 4,
                'status' => $isSelectionPublished ? 'Terbit' : 'Menunggu',
                'title' => 'Status Kelulusan',
                'link' => $isPassed || $isReRegistrationPaid ? route('daftar-ulang') : route('status-lulus'),
                'action' => $isSelectionPublished ? 'Lihat hasil ->' : 'Pantau hasil ->',
                'active' => $isInterviewSelected || $isSelectionPublished,
                'tone' => $isSelectionPublished ? 'emerald' : ($isInterviewSelected ? 'amber' : 'slate'),
            ],
            [
                'number' => 5,
                'status' => $isReRegistrationPaid ? 'Lunas' : ($isPassed ? 'Belum' : ($isSelectionPublished ? 'Tidak Tersedia' : 'Menunggu')),
                'title' => 'Lakukan Pendaftaran Ulang',
                'link' => route('status-lulus'),
                'action' => $isReRegistrationPaid ? 'Lihat status kelulusan ->' : ($isPassed ? 'Lihat hasil dan instruksi ->' : ($isSelectionPublished ? 'Lihat hasil seleksi ->' : 'Menunggu kelulusan ->')),
                'active' => $isPassed || $isReRegistrationPaid,
                'tone' => $isReRegistrationPaid ? 'emerald' : ($isPassed ? 'amber' : 'slate'),
            ],
        ];

        $stageToneClasses = [
            'emerald' => 'bg-emerald-100 text-emerald-700',
            'blue' => 'bg-blue-100 text-blue-700',
            'amber' => 'bg-amber-100 text-amber-700',
            'slate' => 'bg-slate-100 text-slate-600',
        ];
    @endphp
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'beranda')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="max-w-6xl">
                    <h2 class="text-2xl font-extrabold text-blue-950 md:text-3xl">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="mt-2 text-sm text-slate-500 md:text-base">Pantau tahapan pendaftaran ananda dan lanjutkan proses PPDB RA Fadhilah dari panel ini.</p>
                </div>

                <div class="mt-8 overflow-x-auto pb-3">
                    <div class="grid min-w-[1050px] grid-cols-5 gap-3">
                        @foreach ($registrationStages as $stage)
                            <article class="flex min-h-[190px] flex-col rounded-3xl bg-white p-4 shadow-[0_10px_25px_rgba(15,23,42,0.12)] ring-1 ring-blue-100">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-xl font-extrabold leading-tight text-blue-950">{{ $stage['status'] }}</p>
                                    <span class="shrink-0 rounded-xl px-3 py-2 text-xs font-bold {{ $stageToneClasses[$stage['tone']] }}">
                                        Tahap {{ $stage['number'] }}
                                    </span>
                                </div>

                                <p class="mt-3 text-xs font-medium leading-5 text-slate-600">{{ $stage['title'] }}</p>

                                <a
                                    href="{{ $stage['link'] }}"
                                    class="mt-auto inline-flex pt-5 text-xs font-semibold leading-5 {{ $stage['active'] ? 'text-blue-900 hover:text-blue-700' : 'pointer-events-none text-slate-400' }}"
                                    @if (! $stage['active']) aria-disabled="true" tabindex="-1" @endif
                                >
                                    {{ $stage['action'] }}
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8">
                    <section class="rounded-[2rem] bg-blue-950 p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-900 md:p-8">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-300">Ringkasan</p>
                                <h3 class="mt-2 text-xl font-bold text-white md:text-2xl">Panduan Pendaftaran Anda</h3>
                            </div>
                            <a href="{{ $dataRoute }}" class="inline-flex rounded-full bg-yellow-300 px-6 py-3 text-sm font-semibold text-blue-950 transition hover:bg-yellow-200">
                                {{ $isFormPaid ? 'Lanjut Data Diri' : 'Mulai dari Formulir' }}
                            </a>
                        </div>

                        <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                            <div class="rounded-3xl bg-blue-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-blue-700">1. Formulir</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Lakukan pembelian formulir pendaftaran untuk mendapatkan akses proses SPMB.</p>
                            </div>
                            <div class="rounded-3xl bg-emerald-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-emerald-700">2. Data Diri</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Isi data peserta didik, orang tua, dan data pendukung lainnya dengan lengkap.</p>
                            </div>
                            <div class="rounded-3xl bg-violet-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-violet-700">3. Wawancara</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Setelah selesai mengisi data diri, orang tua wajib memilih jadwal wawancara yang tersedia.</p>
                            </div>
                            <div class="rounded-3xl bg-amber-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-amber-700">4. Verifikasi</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Pantau verifikasi pembayaran dan kelengkapan berkas sampai proses selesai.</p>
                            </div>
                            <div class="rounded-3xl bg-rose-50 p-5 md:col-span-2 xl:col-span-1">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-rose-700">5. Pendaftaran Ulang</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Setelah pengumuman menyatakan anak lulus, orang tua wajib membayar biaya daftar ulang sebesar Rp1.700.000 yang dapat dicicil sebanyak 3 kali.</p>
                            </div>
                        </div>
                    </section>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>
</body>
</html>
