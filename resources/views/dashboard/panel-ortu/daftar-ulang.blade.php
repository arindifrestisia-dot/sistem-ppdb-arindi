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
    @php
        $billingItems = [
            ['label' => 'Uang Pembangunan', 'amount' => 1000000],
            ['label' => 'Uang Seragam', 'amount' => 355000],
            ['label' => 'SPP sudah termasuk bulan Juli', 'amount' => 175000],
            ['label' => 'Asuransi', 'amount' => 20000],
        ];

        $billingTotal = 1550000;
        $paymentPlans = [
            [
                'key' => 'full',
                'name' => 'Pembayaran Lunas',
                'amount' => $billingTotal,
                'amounts' => [$billingTotal],
                'description' => 'Bayar penuh satu kali untuk menyelesaikan daftar ulang.',
                'tone' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                'button' => 'Pilih Lunas',
            ],
            [
                'key' => 'installment_2',
                'name' => 'Cicilan 2x',
                'amount' => 775000,
                'amounts' => [775000, 775000],
                'description' => 'Bayar bertahap sebanyak 2 kali dengan nominal yang sama.',
                'tone' => 'bg-blue-50 text-blue-700 ring-blue-100',
                'button' => 'Pilih Cicilan 2x',
            ],
            [
                'key' => 'installment_3',
                'name' => 'Cicilan 3x',
                'amount' => 518000,
                'amounts' => [518000, 518000, 514000],
                'description' => 'Bayar bertahap sebanyak 3 kali sesuai rencana cicilan.',
                'tone' => 'bg-amber-50 text-amber-700 ring-amber-100',
                'button' => 'Pilih Cicilan 3x',
            ],
        ];

        $formatRupiah = fn (int $amount) => 'Rp ' . number_format($amount, 0, ',', '.');
        $initialPlan = $selectedReRegistrationPlan ?? 'full';
        $initialPlanDefinition = collect($paymentPlans)->firstWhere('key', $initialPlan) ?: $paymentPlans[0];
        $initialPaymentAmount = $initialPlanDefinition['amounts'][max(0, ($reRegistrationInstallmentStatus['current_installment'] ?? 1) - 1)] ?? $initialPlanDefinition['amount'];
        $hasStoredPaymentPlan = filled(data_get($registration?->custom_form_data, 'reregistration_plan'));
        $hasPaidReRegistrationInstallments = count($reRegistrationInstallmentStatus['paid_items'] ?? []) > 0;
        $shouldLockPaymentPlan = $hasStoredPaymentPlan
            || $hasPaidReRegistrationInstallments
            || $registration?->reregistration_status === 'manual_pending'
            || ($registration?->reregistration_order_id && ! $isReRegistrationPaid);
        $visiblePaymentPlans = $shouldLockPaymentPlan
            ? collect($paymentPlans)->where('key', $initialPlan)->values()->all()
            : $paymentPlans;
    @endphp
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'pendaftaran-ulang')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-6xl">
                    <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Daftar Ulang</h1>
                    <p class="mt-2 text-lg text-slate-500">Ikuti instruksi dan selesaikan pembayaran daftar ulang ananda.</p>

                    @if (session('status'))
                        <div class="mt-6 rounded-3xl bg-amber-50 p-5 text-sm font-semibold text-amber-700 ring-1 ring-amber-100">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mt-6 rounded-3xl bg-rose-50 p-5 text-sm font-semibold text-rose-700 ring-1 ring-rose-100">{{ $errors->first() }}</div>
                    @endif

                    <section class="mt-8 grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
                        <div class="rounded-[2rem] bg-blue-950 p-6 text-white shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-900 md:p-8">
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-yellow-300">Ringkasan Tagihan</p>
                            <h2 class="mt-3 text-2xl font-extrabold leading-tight">Biaya Masuk RA FADHILAH</h2>
                            <p class="mt-3 text-3xl font-extrabold text-yellow-300">{{ $formatRupiah($billingTotal) }}</p>

                            <div class="mt-7 rounded-[1.5rem] bg-white/10 p-5 ring-1 ring-white/10">
                                <p class="text-sm font-bold text-sky-100">Rincian Biaya</p>
                                <div class="mt-4 space-y-3">
                                    @foreach ($billingItems as $item)
                                        <div class="flex items-start justify-between gap-4 border-b border-white/10 pb-3 last:border-0 last:pb-0">
                                            <span class="text-sm leading-6 text-sky-100">{{ $item['label'] }}</span>
                                            <span class="shrink-0 text-sm font-bold">{{ $formatRupiah($item['amount']) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[2rem] bg-gradient-to-br from-sky-50 to-emerald-50 p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-emerald-100 md:p-8">
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Rencana Pembayaran</p>
                            <h2 class="mt-2 text-2xl font-bold text-blue-950">Pilih skema pembayaran</h2>
                            <p class="mt-2 max-w-3xl text-sm leading-7 text-slate-500">
                                Tagihan dapat dibayar lunas atau dicicil. Setelah menentukan rencana, lanjutkan pembayaran melalui Midtrans, transfer BRI/DANA, atau cash ke sekolah.
                            </p>

                            <div class="mt-6 grid gap-4 {{ count($visiblePaymentPlans) === 1 ? 'md:grid-cols-1' : 'md:grid-cols-3' }}">
                                @foreach ($visiblePaymentPlans as $plan)
                                    <article class="rounded-[1.5rem] {{ $plan['tone'] }} p-5 ring-1">
                                        <p class="text-sm font-extrabold">{{ $plan['name'] }}</p>
                                        <p class="mt-3 whitespace-nowrap text-xl font-extrabold text-blue-950">{{ $formatRupiah($plan['amount']) }}</p>
                                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $plan['description'] }}</p>
                                        <button
                                            type="button"
                                            class="payment-plan-button mt-5 inline-flex w-full justify-center rounded-full px-4 py-3 text-sm font-extrabold transition {{ $initialPlan === $plan['key'] ? 'bg-blue-950 text-white shadow-[0_12px_28px_rgba(15,23,42,0.18)]' : 'bg-white text-blue-950 ring-1 ring-blue-100 hover:bg-blue-950 hover:text-white' }}"
                                            data-plan="{{ $plan['key'] }}"
                                            data-label="{{ $plan['name'] }}"
                                            data-amounts='@json($plan["amounts"])'
                                        >
                                            {{ $initialPlan === $plan['key'] ? 'Dipilih' : $plan['button'] }}
                                        </button>
                                    </article>
                                @endforeach
                            </div>

                            <div class="mt-7 overflow-hidden rounded-[1.5rem] bg-white/85 ring-1 ring-emerald-100">
                                <div class="bg-sky-500 px-5 py-4 text-white">
                                    <p class="text-sm font-extrabold">Status Pembayaran</p>
                                    <p class="mt-1 text-xs font-medium text-sky-50" id="selectedPaymentPlanLabel">Skema: {{ $initialPlanDefinition['name'] }}</p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-left text-sm">
                                        <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                                            <tr>
                                                <th class="px-5 py-4">Termin</th>
                                                <th class="px-5 py-4">Nominal</th>
                                                <th class="px-5 py-4">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="paymentPlanStatusRows" class="divide-y divide-slate-100"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-emerald-100 md:p-8">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Pendaftaran Ulang</p>
                                <h2 class="mt-2 text-2xl font-bold text-blue-950">Selesaikan pembayaran daftar ulang</h2>
                                <p class="mt-2 max-w-3xl text-sm leading-7 text-slate-500">
                                    Untuk mengunci kursi peserta didik, lakukan pembayaran daftar ulang melalui Midtrans sandbox, Transfer Rekening atau Cash ke sekolah sebesar <span class="font-bold text-slate-800">{{ $reRegistrationAmountLabel }}</span>.
                                    @if ($reRegistrationDeadline)
                                        Batas pembayaran sampai {{ $reRegistrationDeadline->translatedFormat('d F Y') }}.
                                    @endif
                                </p>
                            </div>

                            <div class="rounded-3xl bg-emerald-50 px-5 py-4 text-left lg:min-w-64">
                                <p class="text-sm font-medium text-emerald-700">Status Pembayaran</p>
                                <p class="mt-1 text-xl font-extrabold {{ $isReRegistrationPaid ? 'text-emerald-700' : 'text-amber-600' }}" id="reregStatusLabel">
                                    {{ $isReRegistrationPaid ? 'Lunas' : ($registration?->reregistration_status === 'manual_pending' ? 'Menunggu Verifikasi' : (($reRegistrationInstallmentStatus['paid_installments'] ?? 0) > 0 ? 'Cicilan Berjalan' : 'Belum Lunas')) }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div class="rounded-3xl bg-slate-50 p-5">
                                <p class="text-sm font-medium text-slate-500">Nominal</p>
                                <p class="mt-2 text-lg font-bold text-slate-800" id="reregistrationSummaryAmount">{{ $formatRupiah($initialPaymentAmount) }}</p>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-5">
                                <p class="text-sm font-medium text-slate-500">Metode Pembayaran</p>
                                <p class="mt-2 text-lg font-bold text-slate-800" id="reregistrationSummaryPaymentMethod">{{ $reRegistrationPaymentMethodLabel }}</p>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-5">
                                <p class="text-sm font-medium text-slate-500">Order ID</p>
                                <p class="mt-2 break-all text-lg font-bold text-slate-800" id="reregistrationSummaryOrderId">{{ $registration?->reregistration_order_id ?: 'Dibuat saat bayar' }}</p>
                            </div>
                        </div>

                        @if ($registration?->reregistration_status === 'manual_pending')
                            <div class="mt-6 flex justify-center rounded-3xl bg-amber-50 p-5">
                                <span class="inline-flex rounded-full bg-amber-100 px-6 py-3 text-sm font-extrabold text-amber-700">Menunggu Verifikasi Panitia</span>
                            </div>
                        @elseif (! $isMidtransConfigured)
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
                                    Bayar via Midtrans
                                </button>
                                <div><p class="text-sm text-slate-500" id="reregistrationPaymentMessage">Jendela pembayaran Midtrans akan terbuka setelah tombol diklik.</p><p class="mt-1 text-xs font-semibold text-emerald-600" id="reregistrationPaymentGuide">Tanpa upload bukti dan otomatis lunas setelah transaksi berhasil.</p></div>
                            </div>
                        @else
                            <div class="mt-6 rounded-3xl bg-emerald-50 p-5 text-sm font-semibold leading-7 text-emerald-700">
                                Pembayaran daftar ulang sudah tercatat lunas. Ananda resmi masuk tahap peserta didik terdaftar.
                            </div>
                        @endif
                    </section>

                    @if (! $isReRegistrationPaid && $registration->reregistration_status !== 'manual_pending')
                        <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-emerald-100 md:p-8">
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Pembayaran Manual</p><h2 class="mt-2 text-2xl font-bold text-blue-950">Transfer/DANA atau cash ke sekolah</h2>
                            <div class="mt-5 grid gap-4 md:grid-cols-2"><div class="rounded-3xl bg-emerald-50 p-5 text-sm leading-7 text-slate-700"><p class="font-extrabold text-emerald-900">Bank BRI</p><p class="mt-1 text-lg font-bold">5510 0106 1682 530</p><p>a.n Arindi Frestisia Ningtias</p></div><div class="rounded-3xl bg-sky-50 p-5 text-sm leading-7 text-slate-700"><p class="font-extrabold text-sky-900">DANA</p><p class="mt-1 text-lg font-bold">0853 6294 4666</p><p>a.n Arindi Frestisia Ningtias</p></div></div>
                            <form method="POST" action="{{ route('daftar-ulang.manual') }}" enctype="multipart/form-data" class="mt-6 grid gap-5 md:grid-cols-2">@csrf
                                <input type="hidden" name="payment_plan" id="manualPaymentPlanInput" value="{{ $initialPlan }}">
                                <div><label for="reregistrationPaymentMethod" class="text-sm font-bold text-slate-700">Metode pembayaran</label><select id="reregistrationPaymentMethod" name="payment_method" required class="mt-2 w-full rounded-2xl border border-slate-300 px-4 py-3"><option value="transfer">Transfer BRI atau DANA</option><option value="cash">Bayar Cash ke Sekolah</option></select></div>
                                <div id="reregistrationPaymentProofBox"><label class="text-sm font-bold text-slate-700">Upload bukti pembayaran</label><input id="reregistrationPaymentProof" name="proof" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white text-sm file:mr-3 file:border-0 file:bg-emerald-600 file:px-4 file:py-3 file:font-semibold file:text-white"><p class="mt-2 text-xs text-slate-500">Wajib untuk transfer/DANA. Maksimal 5 MB.</p></div>
                                <div class="md:col-span-2 flex flex-wrap items-center gap-4"><button class="rounded-full bg-emerald-600 px-7 py-3 font-bold text-white hover:bg-emerald-500">Kirim untuk Verifikasi</button>@if ($registration->reregistration_status === 'manual_pending')<span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-700">Menunggu verifikasi panitia</span>@endif</div>
                            </form>
                        </section>
                    @endif
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
            const reregistrationSummaryAmount = document.getElementById('reregistrationSummaryAmount');
            const reregistrationSummaryPaymentMethod = document.getElementById('reregistrationSummaryPaymentMethod');
            const reregistrationSummaryOrderId = document.getElementById('reregistrationSummaryOrderId');
            const reregistrationPaymentGuide = document.getElementById('reregistrationPaymentGuide');

            window.updateReregistrationPaymentSummary = (methodLabel, orderId = null, guide = null, amountLabel = null) => {
                if (reregistrationSummaryAmount && amountLabel) {
                    reregistrationSummaryAmount.textContent = amountLabel;
                }

                if (reregistrationSummaryPaymentMethod) {
                    reregistrationSummaryPaymentMethod.textContent = methodLabel;
                }

                if (reregistrationSummaryOrderId && orderId) {
                    reregistrationSummaryOrderId.textContent = orderId;
                }

                if (reregistrationPaymentGuide && guide) {
                    reregistrationPaymentGuide.textContent = guide;
                }
            };

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
                    window.updateReregistrationPaymentSummary(data.payment_method_label || 'Midtrans Sandbox', data.order_id);
                    statusLabel.textContent = 'Lunas';
                    statusLabel.className = 'mt-1 text-xl font-extrabold text-emerald-700';
                    payButton.disabled = true;
                    setMessage('Pembayaran sudah tercatat lunas. Halaman akan dimuat ulang.');
                    window.setTimeout(() => window.location.reload(), 1200);
                    return;
                }

                if (data.status === 'installment_partial') {
                    statusLabel.textContent = 'Cicilan Berjalan';
                    statusLabel.className = 'mt-1 text-xl font-extrabold text-amber-600';
                    payButton.disabled = true;
                    setMessage('Termin pembayaran tercatat. Halaman akan dimuat ulang untuk menampilkan termin berikutnya.');
                    window.setTimeout(() => window.location.reload(), 1200);
                }
            };

            payButton?.addEventListener('click', async () => {
                payButton.disabled = true;
                window.updateReregistrationPaymentSummary(
                    'Midtrans Sandbox',
                    null,
                    'Tanpa upload bukti dan otomatis lunas setelah transaksi berhasil.'
                );
                setMessage('Menyiapkan transaksi Midtrans sandbox...');

                try {
                    const response = await fetch('{{ route('daftar-ulang.midtrans.token') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            payment_plan: window.selectedReregistrationPlan || '{{ $initialPlan }}',
                        }),
                    });
                    const data = await response.json();

                    if (! response.ok) {
                        throw new Error(data.message || 'Transaksi belum dapat dibuat.');
                    }

                    if (data.status === 'paid') {
                        window.location.reload();
                        return;
                    }

                    window.updateReregistrationPaymentSummary(data.payment_method_label || 'Midtrans Sandbox', data.order_id);

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
    <script>
        const paymentPlanButtons = Array.from(document.querySelectorAll('.payment-plan-button'));
        const paymentPlanStatusRows = document.getElementById('paymentPlanStatusRows');
        const selectedPaymentPlanLabel = document.getElementById('selectedPaymentPlanLabel');
        const manualPaymentPlanInput = document.getElementById('manualPaymentPlanInput');
        const reregistrationSummaryAmountGlobal = document.getElementById('reregistrationSummaryAmount');
        const paidInstallmentItems = @json($reRegistrationInstallmentStatus['paid_items'] ?? []);
        const initialCurrentInstallment = @json($reRegistrationInstallmentStatus['current_installment'] ?? 1);
        const hasPaidInstallments = paidInstallmentItems.length > 0;
        window.selectedReregistrationPlan = '{{ $initialPlan }}';

        const formatRupiah = (amount) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(amount).replace(/\s/g, ' ');

        const statusBadge = (text, tone) => {
            const classes = {
                emerald: 'bg-emerald-100 text-emerald-700',
                amber: 'bg-amber-100 text-amber-700',
                slate: 'bg-slate-100 text-slate-600',
            };

            return `<span class="inline-flex rounded-full px-3 py-1.5 text-xs font-extrabold ${classes[tone] || classes.slate}">${text}</span>`;
        };

        const renderPaymentPlanStatus = (button) => {
            if (! button || ! paymentPlanStatusRows) {
                return;
            }

            const amounts = JSON.parse(button.dataset.amounts || '[]');
            const planLabel = button.dataset.label || 'Pembayaran';
            const currentInstallment = hasPaidInstallments ? initialCurrentInstallment : 1;

            paymentPlanStatusRows.innerHTML = amounts.map((amount, index) => {
                const installmentNumber = index + 1;
                const paidItem = paidInstallmentItems.find((item) => Number(item.installment) === installmentNumber);
                const status = paidItem
                    ? statusBadge('Sudah Dibayar', 'emerald')
                    : statusBadge('Belum Dibayar', 'slate');

                return `
                    <tr>
                        <td class="px-5 py-4 font-semibold text-slate-700">${installmentNumber}/${amounts.length}</td>
                        <td class="px-5 py-4 font-bold text-blue-950">${formatRupiah(amount)}</td>
                        <td class="px-5 py-4">${status}</td>
                    </tr>
                `;
            }).join('');

            if (selectedPaymentPlanLabel) {
                selectedPaymentPlanLabel.textContent = `Skema: ${planLabel}`;
            }

            if (reregistrationSummaryAmountGlobal) {
                reregistrationSummaryAmountGlobal.textContent = formatRupiah(amounts[Math.max(0, currentInstallment - 1)] || amounts[0] || 0);
            }
        };

        const selectPaymentPlan = (button) => {
            if (! button || (hasPaidInstallments && button.dataset.plan !== '{{ $initialPlan }}')) {
                return;
            }

            window.selectedReregistrationPlan = button.dataset.plan;

            if (manualPaymentPlanInput) {
                manualPaymentPlanInput.value = window.selectedReregistrationPlan;
            }

            paymentPlanButtons.forEach((item) => {
                const selected = item === button;
                item.textContent = selected ? 'Dipilih' : (item.dataset.plan === 'full' ? 'Pilih Lunas' : (item.dataset.plan === 'installment_2' ? 'Pilih Cicilan 2x' : 'Pilih Cicilan 3x'));
                item.className = selected
                    ? 'payment-plan-button mt-5 inline-flex w-full justify-center rounded-full bg-blue-950 px-4 py-3 text-sm font-extrabold text-white shadow-[0_12px_28px_rgba(15,23,42,0.18)] transition'
                    : 'payment-plan-button mt-5 inline-flex w-full justify-center rounded-full bg-white px-4 py-3 text-sm font-extrabold text-blue-950 ring-1 ring-blue-100 transition hover:bg-blue-950 hover:text-white';

                if (hasPaidInstallments && item.dataset.plan !== '{{ $initialPlan }}') {
                    item.disabled = true;
                    item.className = 'payment-plan-button mt-5 inline-flex w-full cursor-not-allowed justify-center rounded-full bg-slate-100 px-4 py-3 text-sm font-extrabold text-slate-400 ring-1 ring-slate-200';
                }
            });

            renderPaymentPlanStatus(button);
        };

        paymentPlanButtons.forEach((button) => {
            button.addEventListener('click', () => selectPaymentPlan(button));
        });
        selectPaymentPlan(paymentPlanButtons.find((button) => button.dataset.plan === window.selectedReregistrationPlan) || paymentPlanButtons[0]);

        const reregistrationPaymentMethod = document.getElementById('reregistrationPaymentMethod');
        const reregistrationPaymentProofBox = document.getElementById('reregistrationPaymentProofBox');
        const reregistrationPaymentProof = document.getElementById('reregistrationPaymentProof');

        if (! window.updateReregistrationPaymentSummary) {
            window.updateReregistrationPaymentSummary = (methodLabel, orderId = null, guide = null, amountLabel = null) => {
                const reregistrationSummaryAmount = document.getElementById('reregistrationSummaryAmount');
                const reregistrationSummaryPaymentMethod = document.getElementById('reregistrationSummaryPaymentMethod');
                const reregistrationSummaryOrderId = document.getElementById('reregistrationSummaryOrderId');
                const reregistrationPaymentGuide = document.getElementById('reregistrationPaymentGuide');

                if (reregistrationSummaryAmount && amountLabel) {
                    reregistrationSummaryAmount.textContent = amountLabel;
                }

                if (reregistrationSummaryPaymentMethod) {
                    reregistrationSummaryPaymentMethod.textContent = methodLabel;
                }

                if (reregistrationSummaryOrderId && orderId) {
                    reregistrationSummaryOrderId.textContent = orderId;
                }

                if (reregistrationPaymentGuide && guide) {
                    reregistrationPaymentGuide.textContent = guide;
                }
            };
        }

        const updateManualReregistrationSummary = () => {
            if (! reregistrationPaymentMethod) {
                return;
            }

            if (reregistrationPaymentMethod.value === 'cash') {
                window.updateReregistrationPaymentSummary(
                    'Cash ke Sekolah',
                    null,
                    'Bayar cash langsung ke sekolah. Status pembayaran akan lunas setelah diverifikasi panitia.'
                );
                return;
            }

            window.updateReregistrationPaymentSummary(
                'Transfer BRI / DANA',
                null,
                'Unggah bukti pembayaran transfer BRI/DANA agar dapat diverifikasi panitia.'
            );
        };

        const toggleReregistrationProof = (shouldUpdateSummary = false) => {
            const shouldShowProof = reregistrationPaymentMethod?.value !== 'cash';

            reregistrationPaymentProofBox?.classList.toggle('hidden', !shouldShowProof);

            if (reregistrationPaymentProof) {
                reregistrationPaymentProof.disabled = !shouldShowProof;
                if (! shouldShowProof) {
                    reregistrationPaymentProof.value = '';
                }
            }

            if (shouldUpdateSummary) {
                updateManualReregistrationSummary();
            }
        };

        reregistrationPaymentMethod?.addEventListener('change', () => toggleReregistrationProof(true));
        toggleReregistrationProof();
    </script>
</body>
</html>
