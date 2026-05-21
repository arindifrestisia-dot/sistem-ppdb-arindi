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
                                Hasil kelulusan belum dipublikasikan. Silakan pantau halaman ini secara berkala untuk melihat pengumuman resmi dari panitia PPDB.
                            </p>
                        </section>
                    @else
                        <section class="mt-8 overflow-hidden rounded-[2.25rem] bg-white shadow-[0_18px_45px_rgba(15,23,42,0.14)] ring-1 ring-slate-200">
                            <div class="border-b border-slate-200 px-6 py-6 md:px-10">
                                <div class="flex items-center justify-center gap-3 text-center">
                                    <svg class="h-6 w-6 text-blue-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5l-8-3Zm0 3.2 5 1.9V11c0 3.6-2.2 7-5 8.3-2.8-1.3-5-4.7-5-8.3V7.1l5-1.9Z"></path>
                                    </svg>
                                    <h2 class="text-lg font-bold uppercase tracking-[0.22em] text-slate-600 md:text-2xl">Hasil Seleksi PMBM Online</h2>
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

                        <section id="detail-status" data-graduation-section class="hidden mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">Detail Hasil</p>
                                    <h2 class="mt-2 text-2xl font-bold text-blue-950">{{ $registration?->full_name ?: Auth::user()->name }}</h2>
                                    <p class="mt-2 text-slate-500">Hasil seleksi resmi telah dipublikasikan oleh panitia PPDB.</p>
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
                        </section>

                        @if ($canPayReRegistration)
                            <section id="pendaftaran-ulang" data-graduation-section class="hidden mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-emerald-100 md:p-8">
                                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Pendaftaran Ulang</p>
                                        <h2 class="mt-2 text-2xl font-bold text-blue-950">Selesaikan pembayaran daftar ulang</h2>
                                        <p class="mt-2 max-w-3xl text-sm leading-7 text-slate-500">
                                            Untuk mengunci kursi peserta didik, lakukan pembayaran daftar ulang melalui Midtrans sandbox sebesar <span class="font-bold text-slate-800">{{ $reRegistrationAmountLabel }}</span>.
                                            @if ($reRegistrationDeadline)
                                                Batas pembayaran sampai {{ $reRegistrationDeadline->translatedFormat('d F Y') }}.
                                            @endif
                                        </p>
                                    </div>

                                    <div class="rounded-3xl bg-emerald-50 px-5 py-4 text-left lg:min-w-64">
                                        <p class="text-sm font-medium text-emerald-700">Status Pembayaran</p>
                                        <p class="mt-1 text-xl font-extrabold {{ $isReRegistrationPaid ? 'text-emerald-700' : 'text-amber-600' }}" id="reregStatusLabel">
                                            {{ $isReRegistrationPaid ? 'Lunas' : 'Belum Lunas' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-4 md:grid-cols-3">
                                    <div class="rounded-3xl bg-slate-50 p-5">
                                        <p class="text-sm font-medium text-slate-500">Nominal</p>
                                        <p class="mt-2 text-lg font-bold text-slate-800">{{ $reRegistrationAmountLabel }}</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-50 p-5">
                                        <p class="text-sm font-medium text-slate-500">Gateway</p>
                                        <p class="mt-2 text-lg font-bold text-slate-800">Midtrans Sandbox</p>
                                    </div>
                                    <div class="rounded-3xl bg-slate-50 p-5">
                                        <p class="text-sm font-medium text-slate-500">Order ID</p>
                                        <p class="mt-2 break-all text-lg font-bold text-slate-800">{{ $registration?->reregistration_order_id ?: 'Dibuat saat bayar' }}</p>
                                    </div>
                                </div>

                                @if (! $isMidtransConfigured)
                                    <div class="mt-6 rounded-3xl bg-amber-50 p-5 text-sm font-semibold leading-7 text-amber-700">
                                        Konfigurasi Midtrans sandbox belum lengkap. Hubungi admin sekolah untuk mengaktifkan pembayaran daftar ulang.
                                    </div>
                                @elseif (! $isReRegistrationPaid)
                                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                                        <button
                                            type="button"
                                            id="payReregistrationButton"
                                            class="inline-flex justify-center rounded-full bg-emerald-500 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-white shadow-[0_18px_40px_rgba(16,185,129,0.28)] transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:bg-slate-400"
                                        >
                                            Bayar Daftar Ulang
                                        </button>
                                        <p class="text-sm text-slate-500" id="reregistrationPaymentMessage">Jendela pembayaran Midtrans akan terbuka setelah tombol diklik.</p>
                                    </div>
                                @else
                                    <div class="mt-6 rounded-3xl bg-emerald-50 p-5 text-sm font-semibold leading-7 text-emerald-700">
                                        Pembayaran daftar ulang sudah tercatat lunas. Ananda resmi masuk tahap peserta didik terdaftar.
                                    </div>
                                @endif
                            </section>
                        @endif
                    @endif
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>

    @if ($isSelectionPublished)
        <script>
            const revealGraduationStatusButton = document.getElementById('revealGraduationStatusButton');
            const graduationSections = document.querySelectorAll('[data-graduation-section]');
            const detailStatusSection = document.getElementById('detail-status');

            const showGraduationSections = (scrollTarget = detailStatusSection) => {
                graduationSections.forEach((section) => {
                    section.classList.remove('hidden');
                });

                scrollTarget?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            };

            revealGraduationStatusButton?.addEventListener('click', () => {
                showGraduationSections();
            });

            if (window.location.hash === '#detail-status' || window.location.hash === '#pendaftaran-ulang') {
                showGraduationSections(document.querySelector(window.location.hash));
            }
        </script>
    @endif

    @if ($canPayReRegistration && $isMidtransConfigured && ! $isReRegistrationPaid)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
        <script>
            const payButton = document.getElementById('payReregistrationButton');
            const paymentMessage = document.getElementById('reregistrationPaymentMessage');
            const statusLabel = document.getElementById('reregStatusLabel');

            const setMessage = (message, tone = 'slate') => {
                paymentMessage.textContent = message;
                paymentMessage.className = tone === 'error'
                    ? 'text-sm font-semibold text-rose-600'
                    : 'text-sm text-slate-500';
            };

            const syncPaymentStatus = async () => {
                const response = await fetch('{{ route('daftar-ulang.midtrans.sync') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({}),
                });

                if (! response.ok) {
                    return;
                }

                const data = await response.json();

                if (data.paid) {
                    statusLabel.textContent = 'Lunas';
                    statusLabel.className = 'mt-1 text-xl font-extrabold text-emerald-700';
                    payButton.disabled = true;
                    setMessage('Pembayaran sudah tercatat lunas. Halaman akan dimuat ulang.');
                    window.setTimeout(() => window.location.reload(), 1200);
                }
            };

            payButton?.addEventListener('click', async () => {
                payButton.disabled = true;
                setMessage('Menyiapkan transaksi Midtrans sandbox...');

                try {
                    const response = await fetch('{{ route('daftar-ulang.midtrans.token') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({}),
                    });
                    const data = await response.json();

                    if (! response.ok) {
                        throw new Error(data.message || 'Transaksi belum dapat dibuat.');
                    }

                    if (data.status === 'paid') {
                        window.location.reload();
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: syncPaymentStatus,
                        onPending: () => {
                            setMessage('Transaksi dibuat. Selesaikan pembayaran sesuai instruksi Midtrans.');
                            payButton.disabled = false;
                        },
                        onError: () => {
                            setMessage('Pembayaran gagal diproses. Silakan coba lagi.', 'error');
                            payButton.disabled = false;
                        },
                        onClose: () => {
                            setMessage('Jendela pembayaran ditutup. Anda dapat melanjutkan pembayaran kapan saja.');
                            payButton.disabled = false;
                        },
                    });
                } catch (error) {
                    setMessage(error.message, 'error');
                    payButton.disabled = false;
                }
            });
        </script>
    @endif
</body>
</html>
