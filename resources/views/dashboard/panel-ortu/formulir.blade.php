<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Formulir PPDB</title>
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
        @php($activeMenu = 'formulir')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-5xl">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Pembelian Formulir PPDB</h1>
                            <p class="mt-2 text-lg text-slate-500">Lunasi formulir melalui Midtrans sandbox untuk membuka akses pengisian data diri.</p>
                        </div>
                        <span class="inline-flex rounded-2xl px-4 py-2 text-sm font-semibold {{ $isPaid ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $isPaid ? 'Formulir Lunas' : 'Menunggu Pembayaran' }}
                        </span>
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-3xl bg-amber-50 p-5 text-sm font-semibold leading-7 text-amber-700 ring-1 ring-amber-100">
                            {{ session('status') }}
                        </div>
                    @endif

                    <section class="mt-8 overflow-hidden rounded-[2rem] bg-white shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100">
                        <div class="grid gap-0 lg:grid-cols-[minmax(0,1.25fr)_360px]">
                            <div class="p-6 md:p-8">
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">Tahap 1</p>
                                <h2 class="mt-2 text-2xl font-bold text-blue-950">Formulir Pendaftaran PPDB Reguler</h2>
                                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500">
                                    Pembelian formulir dilakukan satu kali untuk satu akun orang tua. Setelah status pembayaran lunas, menu data diri dapat digunakan untuk mengisi data calon peserta didik dan mengunggah berkas.
                                </p>

                                <div class="mt-8 grid gap-4 md:grid-cols-3">
                                    <div class="rounded-3xl bg-blue-50 p-5">
                                        <p class="text-sm font-semibold text-blue-700">1. Bayar</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">Klik tombol bayar dan selesaikan transaksi di Snap Midtrans.</p>
                                    </div>
                                    <div class="rounded-3xl bg-emerald-50 p-5">
                                        <p class="text-sm font-semibold text-emerald-700">2. Isi Formulir</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">Setelah lunas, isi data diri calon siswa dan orang tua.</p>
                                    </div>
                                    <div class="rounded-3xl bg-amber-50 p-5">
                                        <p class="text-sm font-semibold text-amber-700">3. Seleksi</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-600">Panitia memeriksa berkas, wawancara, lalu menerbitkan hasil.</p>
                                    </div>
                                </div>
                            </div>

                            <aside class="bg-blue-950 p-6 text-white md:p-8">
                                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-yellow-300">Ringkasan</p>
                                <h3 class="mt-3 text-2xl font-bold">Formulir PPDB RA Fadhilah</h3>

                                <div class="mt-8 space-y-4 text-sm">
                                    <div class="flex items-center justify-between gap-4 border-b border-white/15 pb-4">
                                        <span class="text-sky-100">Nama pembeli</span>
                                        <span class="text-right font-semibold">{{ Auth::user()->name }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-4 border-b border-white/15 pb-4">
                                        <span class="text-sky-100">Nominal</span>
                                        <span class="text-right text-xl font-extrabold text-yellow-300">{{ $formAmountLabel }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-4 border-b border-white/15 pb-4">
                                        <span class="text-sky-100">Gateway</span>
                                        <span class="text-right font-semibold">Midtrans Sandbox</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-sky-100">Order ID</span>
                                        <span class="text-right text-xs font-semibold">{{ $payment?->order_id ?: 'Dibuat saat bayar' }}</span>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </section>

                    <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                        <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">Status Pembayaran</p>
                                <h2 class="mt-2 text-2xl font-bold {{ $isPaid ? 'text-emerald-600' : 'text-blue-950' }}" id="formPaymentStatusLabel">
                                    {{ $isPaid ? 'Pembayaran formulir lunas' : 'Formulir belum lunas' }}
                                </h2>
                                <p class="mt-2 text-sm leading-7 text-slate-500" id="formPaymentMessage">
                                    {{ $isPaid ? 'Anda sudah dapat membuka dan mengisi formulir pendaftaran.' : 'Selesaikan pembayaran terlebih dahulu agar formulir pendaftaran terbuka.' }}
                                </p>
                            </div>

                            @if ($isPaid)
                                <a href="{{ route('data-diri') }}" class="inline-flex justify-center rounded-full bg-emerald-600 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-white shadow-[0_18px_40px_rgba(16,185,129,0.28)] transition hover:bg-emerald-500">
                                    Buka Formulir
                                </a>
                            @elseif (! $isMidtransConfigured)
                                <span class="inline-flex rounded-2xl bg-amber-50 px-5 py-4 text-sm font-semibold text-amber-700">
                                    Midtrans belum dikonfigurasi
                                </span>
                            @else
                                <button type="button" id="payFormButton" class="inline-flex justify-center rounded-full bg-blue-600 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-white shadow-[0_18px_40px_rgba(59,130,246,0.28)] transition hover:bg-blue-500 disabled:cursor-not-allowed disabled:bg-slate-400">
                                    Bayar Formulir
                                </button>
                            @endif
                        </div>

                        @if (! $isPaid && ! $isMidtransConfigured)
                            <div class="mt-6 rounded-3xl bg-amber-50 p-5 text-sm font-semibold leading-7 text-amber-700">
                                Isi `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` sandbox di file `.env`, lalu jalankan `php artisan config:clear`.
                            </div>
                        @endif
                    </section>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>

    @if (! $isPaid && $isMidtransConfigured)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
        <script>
            const payFormButton = document.getElementById('payFormButton');
            const formPaymentMessage = document.getElementById('formPaymentMessage');
            const formPaymentStatusLabel = document.getElementById('formPaymentStatusLabel');

            const setFormPaymentMessage = (message, tone = 'slate') => {
                formPaymentMessage.textContent = message;
                formPaymentMessage.className = tone === 'error'
                    ? 'mt-2 text-sm font-semibold leading-7 text-rose-600'
                    : 'mt-2 text-sm leading-7 text-slate-500';
            };

            const syncFormPaymentStatus = async () => {
                const response = await fetch('{{ route('ortu.formulir.midtrans.sync') }}', {
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
                    formPaymentStatusLabel.textContent = 'Pembayaran formulir lunas';
                    formPaymentStatusLabel.className = 'mt-2 text-2xl font-bold text-emerald-600';
                    payFormButton.disabled = true;
                    setFormPaymentMessage('Pembayaran lunas. Halaman akan dimuat ulang agar tombol formulir terbuka.');
                    window.setTimeout(() => window.location.reload(), 1200);
                }
            };

            payFormButton?.addEventListener('click', async () => {
                payFormButton.disabled = true;
                setFormPaymentMessage('Menyiapkan transaksi Midtrans sandbox...');

                try {
                    const response = await fetch('{{ route('ortu.formulir.midtrans.token') }}', {
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
                        onSuccess: syncFormPaymentStatus,
                        onPending: () => {
                            setFormPaymentMessage('Transaksi dibuat. Selesaikan pembayaran sesuai instruksi Midtrans.');
                            payFormButton.disabled = false;
                        },
                        onError: () => {
                            setFormPaymentMessage('Pembayaran gagal diproses. Silakan coba lagi.', 'error');
                            payFormButton.disabled = false;
                        },
                        onClose: () => {
                            setFormPaymentMessage('Jendela pembayaran ditutup. Anda dapat melanjutkan pembayaran kapan saja.');
                            payFormButton.disabled = false;
                        },
                    });
                } catch (error) {
                    setFormPaymentMessage(error.message, 'error');
                    payFormButton.disabled = false;
                }
            });
        </script>
    @endif
</body>
</html>
