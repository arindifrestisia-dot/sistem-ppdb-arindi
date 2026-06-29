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

        $isRequirementsComplete = (bool) (
            $registration?->submitted_at
            && $registration?->child_photo_path
            && $registration?->parents_id_card_path
            && $registration?->birth_certificate_path
            && $registration?->family_card_path
        );

        $registrationStatusLabel = match (true) {
            $isSelectionPublished && $registration?->selection_result === 'lulus' => 'Diterima',
            $isSelectionPublished && $registration?->selection_result === 'tidak_lulus' => 'Tidak Diterima',
            $isInterviewSelected => 'Menunggu Hasil',
            $isDataSubmitted => 'Diproses',
            $isFormPaid => 'Melengkapi Data',
            default => 'Belum Daftar',
        };

        $accountStageRows = [
            ['label' => 'Formulir', 'complete' => $isFormPaid, 'done' => 'Sudah', 'pending' => 'Belum'],
            ['label' => 'Persyaratan', 'complete' => $isRequirementsComplete, 'done' => 'Sudah', 'pending' => 'Belum'],
            ['label' => 'Wawancara', 'complete' => $isInterviewSelected, 'done' => 'Sudah', 'pending' => 'Belum'],
            ['label' => 'Pembayaran', 'complete' => $isReRegistrationPaid, 'done' => 'Lunas', 'pending' => 'Belum'],
        ];

        $progressSummaryItems = [
            [
                'title' => 'Akun berhasil dibuat',
                'description' => (Auth::user()->name ?: 'Orang tua') . ' sudah memiliki akun untuk memulai proses PPDB.',
                'complete' => true,
            ],
            [
                'title' => 'Formulir pendaftaran',
                'description' => $isFormPaid ? 'Formulir sudah lunas dan siap diproses panitia.' : 'Formulir belum lunas. Selesaikan pembelian formulir terlebih dahulu.',
                'complete' => $isFormPaid,
            ],
            [
                'title' => 'Dokumen persyaratan',
                'description' => $isRequirementsComplete ? 'Dokumen persyaratan sudah lengkap.' : 'Lengkapi data diri dan unggah seluruh dokumen persyaratan.',
                'complete' => $isRequirementsComplete,
            ],
            [
                'title' => 'Wawancara',
                'description' => $isInterviewSelected ? 'Jadwal wawancara sudah dipilih.' : 'Jadwal wawancara belum dipilih.',
                'complete' => $isInterviewSelected,
            ],
            [
                'title' => 'Hasil seleksi dan pembayaran',
                'description' => $isReRegistrationPaid ? 'Siswa diterima dan pembayaran daftar ulang sudah lunas.' : 'Pantau hasil seleksi dan selesaikan pembayaran daftar ulang bila sudah diterima.',
                'complete' => $isReRegistrationPaid,
            ],
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

                <div class="mt-8 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
                    <section class="rounded-[2rem] bg-blue-950 p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-900 md:p-8">
                        <div class="flex flex-col gap-4 border-b border-blue-800/80 pb-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-300">Ringkasan</p>
                                <h3 class="mt-2 text-xl font-bold text-white md:text-2xl">Progres PPDB Anda</h3>
                            </div>
                            <a href="{{ $dataRoute }}" class="inline-flex w-fit rounded-full bg-yellow-300 px-6 py-3 text-sm font-semibold text-blue-950 transition hover:bg-yellow-200">
                                {{ $isFormPaid ? 'Lanjut Data Diri' : 'Mulai dari Formulir' }}
                            </a>
                        </div>

                        <div class="mt-7 space-y-4">
                            @foreach ($progressSummaryItems as $item)
                                <div class="flex gap-4 rounded-[1.5rem] {{ $item['complete'] ? 'bg-emerald-50' : 'bg-rose-50' }} p-5">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $item['complete'] ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                        @if ($item['complete'])
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M12 8v5" />
                                                <path d="M12 17h.01" />
                                                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                                            </svg>
                                        @endif
                                    </span>
                                    <div>
                                        <p class="text-sm font-bold text-blue-950">{{ $item['title'] }}</p>
                                        <p class="mt-1 text-xs leading-5 text-slate-500">{{ $item['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-[2rem] bg-gradient-to-br from-sky-50 to-blue-100 p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-200 md:p-8">
                        <div class="border-b border-blue-200/80 pb-5">
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-600">Akun</p>
                            <h3 class="mt-2 text-xl font-bold text-blue-950 md:text-2xl">Informasi Akun</h3>
                        </div>

                        <div class="pt-7">
                            <div class="grid gap-x-8 gap-y-5 sm:grid-cols-2">
                                <div class="rounded-[1.5rem] bg-white/80 p-4 ring-1 ring-blue-100">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Nama Anak</p>
                                    <p class="mt-2 break-words text-sm font-bold text-blue-950">{{ $registration?->full_name ?: '-' }}</p>
                                </div>
                                <div class="rounded-[1.5rem] bg-white/80 p-4 ring-1 ring-blue-100">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Email</p>
                                    <p class="mt-2 break-all text-sm font-bold text-blue-950">{{ Auth::user()->email ?: '-' }}</p>
                                </div>
                                <div class="rounded-[1.5rem] bg-white/80 p-4 ring-1 ring-blue-100">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">No. HP</p>
                                    <p class="mt-2 break-words text-sm font-bold text-blue-950">{{ $registration?->father_phone ?: ($registration?->mother_phone ?: '-') }}</p>
                                </div>
                                <div class="rounded-[1.5rem] bg-white/80 p-4 ring-1 ring-blue-100">
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Status Pendaftaran</p>
                                    <p class="mt-2 text-sm font-bold text-blue-950">{{ $registrationStatusLabel }}</p>
                                </div>
                            </div>

                            <div class="mt-7 rounded-[1.75rem] bg-white/80 p-5 ring-1 ring-blue-100">
                                <h4 class="text-sm font-bold text-blue-950">Status Tahapan</h4>

                                <div class="mt-5 space-y-3">
                                    @foreach ($accountStageRows as $row)
                                        <div class="flex items-center justify-between gap-4 rounded-2xl {{ $row['complete'] ? 'bg-emerald-50' : 'bg-rose-50' }} px-4 py-3">
                                            <p class="text-sm text-slate-500">{{ $row['label'] }}</p>
                                            <p class="text-sm font-extrabold {{ $row['complete'] ? 'text-emerald-500' : 'text-rose-500' }}">
                                                {{ $row['complete'] ? $row['done'] : $row['pending'] }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
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
