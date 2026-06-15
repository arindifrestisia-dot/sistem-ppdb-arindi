<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Ulang</title>
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
        @php($activeMenu = 'pendaftaran-ulang')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-6xl">
                    <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Daftar Ulang</h1>
                    <p class="mt-2 text-lg text-slate-500">Ikuti instruksi dan selesaikan pembayaran daftar ulang ananda.</p>

                    <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-emerald-100 md:p-8">
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
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>

    @if ($isMidtransConfigured && ! $isReRegistrationPaid)
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
