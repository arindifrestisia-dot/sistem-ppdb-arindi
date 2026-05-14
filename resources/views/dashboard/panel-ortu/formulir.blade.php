<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir PPDB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .payment-option-radio::after {
            content: '';
            width: 0.625rem;
            height: 0.625rem;
            border-radius: 9999px;
            background: #2563eb;
            transform: scale(0);
            transition: transform 0.2s ease;
        }

        .method-card.is-active .payment-option-radio::after {
            transform: scale(1);
        }

        .instruction-step-number {
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.14);
        }
    </style>
</head>
<body class="bg-[#cfe0f8] text-slate-900">
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'formulir')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-5xl" id="paymentFlowRoot">
                    <div class="text-center">
                        <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Pembelian Formulir PPDB</h1>
                        <p class="mt-2 text-lg text-slate-500">Pilih dan beli formulir pendaftaran RA Fadhilah secara bertahap.</p>
                    </div>

                    <div class="mt-8 grid gap-3 md:grid-cols-5">
                        <div class="step-indicator rounded-2xl bg-blue-900 px-4 py-3 text-center text-sm font-semibold text-white" data-step-indicator="1">1. Formulir</div>
                        <div class="step-indicator rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-slate-500 ring-1 ring-slate-200" data-step-indicator="2">2. Pembayaran</div>
                        <div class="step-indicator rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-slate-500 ring-1 ring-slate-200" data-step-indicator="3">3. Instruksi</div>
                        <div class="step-indicator rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-slate-500 ring-1 ring-slate-200" data-step-indicator="4">4. Verifikasi</div>
                        <div class="step-indicator rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-slate-500 ring-1 ring-slate-200" data-step-indicator="5">5. Sukses</div>
                    </div>

                    <section class="payment-step mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8" data-step="1">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <h2 class="text-3xl font-bold text-blue-950">Pilih Formulir Pendaftaran</h2>
                                <p class="mt-2 text-slate-500">Tersedia 1 jenis formulir untuk tahun ajaran 2026/2027.</p>
                            </div>
                            <span class="inline-flex rounded-xl bg-blue-900 px-4 py-2 text-sm font-semibold text-white">Tersedia</span>
                        </div>

                        <div class="mt-8 rounded-[1.75rem] border border-blue-200 p-5 shadow-[0_10px_24px_rgba(15,23,42,0.08)]">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold text-blue-900">Formulir Pendaftaran PPDB Reguler</h3>
                                    <p class="mt-2 uppercase tracking-[0.15em] text-slate-500">Raudhatul Athfal Fadhilah</p>
                                    <ul class="mt-4 space-y-2 text-slate-600">
                                        <li>&bull; Formulir pendaftaran digital</li>
                                        <li>&bull; Nomor registrasi unik</li>
                                        <li>&bull; Akses upload berkas persyaratan</li>
                                        <li>&bull; Berlaku untuk 1 calon peserta didik</li>
                                    </ul>
                                </div>
                                <div class="text-right">
                                    <p class="text-5xl font-extrabold text-blue-900">Rp 100.000</p>
                                    <p class="mt-2 text-slate-500">sekali bayar</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 rounded-3xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-900">
                            Pembayaran formulir hanya dilakukan sekali. Pastikan data yang Anda masukkan sudah benar sebelum melanjutkan ke tahap pembayaran.
                        </div>

                        <button type="button" class="mt-10 inline-flex w-full items-center justify-center rounded-[1.5rem] bg-blue-900 px-8 py-5 text-2xl font-bold text-white shadow-[0_12px_24px_rgba(15,23,42,0.18)] transition hover:bg-blue-800" data-next-step="2">
                            Beli Formulir
                        </button>
                    </section>

                    <section class="payment-step mt-8 hidden rounded-[2rem] border-[3px] border-blue-500 bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] md:p-8" data-step="2">
                        <div class="mx-auto max-w-4xl">
                            <div class="text-center">
                                <h2 class="text-3xl font-extrabold text-blue-950">Pembayaran Formulir</h2>
                                <p class="mt-2 text-slate-500">Pilih metode pembayaran yang tersedia untuk menyelesaikan pembelian formulir.</p>
                            </div>

                            <div class="mt-8 rounded-[1.75rem] border-2 border-blue-500 p-5 shadow-[0_10px_24px_rgba(15,23,42,0.08)]">
                                <h3 class="text-2xl font-bold text-blue-950">Pilih metode pembayaran yang tersedia</h3>

                                <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-slate-300 shadow-[0_8px_18px_rgba(15,23,42,0.08)]">
                                    <div class="space-y-3 bg-slate-50 px-5 py-4 text-sm font-medium text-slate-700">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-lg font-semibold text-blue-950">Formulir Pendaftaran SPMB Reguler</p>
                                                <p class="text-base text-slate-600">Biaya formulir</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-semibold text-blue-950">Rp 100.000</p>
                                                <p class="text-base text-slate-600">Rp 2.500</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-4 border-t border-slate-300 bg-white px-5 py-4">
                                        <p class="text-xl font-extrabold text-blue-950">Total Pembayaran</p>
                                        <p class="text-xl font-extrabold text-blue-950">Rp 102.500</p>
                                    </div>
                                </div>

                                <div class="mt-8 space-y-6">
                                    <div>
                                        <p class="border-b border-slate-300 pb-2 text-sm font-extrabold uppercase tracking-[0.16em] text-slate-600">Virtual Account Bank</p>
                                        <div class="mt-4 space-y-3">
                                            <label class="method-card is-active flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-blue-300 bg-blue-50 px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="VA Bank BSI">
                                                <input type="radio" name="paymentMethod" value="VA Bank BSI" class="sr-only" checked>
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#0f4c81] text-sm font-extrabold text-white">BSI</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">BSI Virtual Account</p>
                                                    <p class="text-sm leading-6 text-slate-500">Transfer ATM / Mobile Banking</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-blue-500 bg-white"></span>
                                            </label>

                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="VA BCA">
                                                <input type="radio" name="paymentMethod" value="VA BCA" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#00529c] text-sm font-extrabold text-white">BCA</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">BCA Virtual Account</p>
                                                    <p class="text-sm leading-6 text-slate-500">Transfer ATM / Mobile Banking</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>

                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="VA Mandiri">
                                                <input type="radio" name="paymentMethod" value="VA Mandiri" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#113a70] text-sm font-extrabold text-[#f7c948]">MDR</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">Mandiri Virtual Account</p>
                                                    <p class="text-sm leading-6 text-slate-500">Transfer ATM / Livin'</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>

                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="VA BNI">
                                                <input type="radio" name="paymentMethod" value="VA BNI" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#f97316] text-sm font-extrabold text-white">BNI</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">BNI Virtual Account</p>
                                                    <p class="text-sm leading-6 text-slate-500">Transfer ATM / BNI Mobile</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>

                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="VA BRI">
                                                <input type="radio" name="paymentMethod" value="VA BRI" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#1d4ed8] text-sm font-extrabold text-white">BRI</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">BRI Virtual Account</p>
                                                    <p class="text-sm leading-6 text-slate-500">Transfer ATM / BRImo</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="border-b border-slate-300 pb-2 text-sm font-extrabold uppercase tracking-[0.16em] text-slate-600">Dompet Digital</p>
                                        <div class="mt-4 space-y-3">
                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="DANA">
                                                <input type="radio" name="paymentMethod" value="DANA" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#2196f3] text-sm font-extrabold text-white">D</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">DANA</p>
                                                    <p class="text-sm leading-6 text-slate-500">Bayar langsung via aplikasi</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>

                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="GoPay">
                                                <input type="radio" name="paymentMethod" value="GoPay" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#22c55e] text-sm font-extrabold text-white">G</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">GoPay</p>
                                                    <p class="text-sm leading-6 text-slate-500">Bayar langsung via Gojek</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>

                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="OVO">
                                                <input type="radio" name="paymentMethod" value="OVO" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#334155] text-sm font-extrabold text-white">OVO</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">OVO</p>
                                                    <p class="text-sm leading-6 text-slate-500">Bayar instan dari saldo OVO</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="border-b border-slate-300 pb-2 text-sm font-extrabold uppercase tracking-[0.16em] text-slate-600">Scan & Pay</p>
                                        <div class="mt-4 space-y-3">
                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="QRIS">
                                                <input type="radio" name="paymentMethod" value="QRIS" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#ef4444] text-xs font-extrabold text-white">QRIS</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">QRIS</p>
                                                    <p class="text-sm leading-6 text-slate-500">Scan dari semua aplikasi pembayaran</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>

                                            <label class="method-card flex cursor-pointer items-center gap-4 rounded-[1.35rem] border border-slate-200 bg-white px-4 py-4 transition hover:border-blue-400 hover:shadow-md" data-method="Bayar di Sekolah">
                                                <input type="radio" name="paymentMethod" value="Bayar di Sekolah" class="sr-only">
                                                <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-[#0f766e] text-xs font-extrabold text-white">TU</div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-lg font-bold text-blue-900">Bayar di Sekolah</p>
                                                    <p class="text-sm leading-6 text-slate-500">Bayar langsung ke TU sekolah</p>
                                                </div>
                                                <span class="payment-option-radio flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-300 bg-white"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 rounded-[1.25rem] border border-slate-400 bg-slate-100 px-4 py-3 text-sm leading-6 text-slate-600">
                                    Setelah klik `Lanjut Bayar`, nomor pembayaran atau instruksi sesuai metode yang dipilih akan ditampilkan. Selesaikan pembayaran dalam 1x24 jam agar verifikasi dapat diproses otomatis.
                                </div>
                            </div>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-between">
                                <button type="button" class="rounded-[1.25rem] border border-slate-300 bg-white px-6 py-3 font-bold text-blue-950 shadow-[0_6px_14px_rgba(15,23,42,0.08)] transition hover:bg-slate-50" data-prev-step="1">Kembali</button>
                                <button type="button" class="rounded-[1.25rem] bg-white px-6 py-3 font-bold text-blue-950 shadow-[0_6px_14px_rgba(15,23,42,0.12)] ring-1 ring-slate-300 transition hover:bg-slate-50" data-next-step="3">Lanjut Bayar</button>
                            </div>
                        </div>
                    </section>

                    <section class="payment-step mt-8 hidden bg-transparent md:p-0" data-step="3">
                        <div class="mx-auto max-w-2xl rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="text-center">
                                <h2 class="text-3xl font-extrabold text-blue-950">Instruksi Pembayaran</h2>
                                <p class="mt-1 text-lg text-slate-400" id="selectedMethodSubLabel">BSI Virtual Account</p>
                            </div>

                            <div class="mt-6 rounded-[1rem] border border-amber-300 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <span>Selesaikan pembayaran dalam</span>
                                    <span class="text-lg font-extrabold tracking-[0.06em]" id="paymentCountdown">23:42:06</span>
                                </div>
                            </div>

                            <div class="mt-5 overflow-hidden rounded-[1.4rem] border border-slate-300 shadow-[0_10px_22px_rgba(15,23,42,0.08)]">
                                <div class="flex items-center gap-4 bg-[#2551d8] px-4 py-4 text-white">
                                    <div class="flex h-11 w-14 items-center justify-center rounded-xl bg-white/15 text-sm font-extrabold" id="paymentBrandBadge">BSI</div>
                                    <div>
                                        <p class="text-2xl font-extrabold leading-tight" id="selectedMethodLabel">VA Bank BSI</p>
                                        <p class="text-sm text-blue-100">Gunakan nomor pembayaran di bawah ini</p>
                                    </div>
                                </div>

                                <div class="space-y-5 bg-white px-4 py-5 md:px-5">
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Nomor Virtual Account</p>
                                        <p class="mt-1 text-[2rem] font-extrabold tracking-[0.12em] text-slate-900 md:text-[2.2rem]" id="paymentCodeLabel">8877 2026 1001</p>
                                    </div>

                                    <button type="button" id="copyVaButton" class="inline-flex items-center gap-2 rounded-[0.9rem] border border-slate-300 px-4 py-3 text-base font-bold text-slate-700 transition hover:bg-slate-50">
                                        <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="9" y="9" width="11" height="11" rx="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                        <span>Salin nomor VA</span>
                                    </button>

                                    <div class="flex items-end justify-between gap-4 border-t border-slate-200 pt-4">
                                        <div>
                                            <p class="text-sm font-medium text-slate-500">Nominal yang harus ditransfer</p>
                                        </div>
                                        <p class="text-3xl font-extrabold text-slate-900">Rp 102.500</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h3 class="text-xl font-extrabold uppercase tracking-[0.08em] text-slate-700" id="instructionHeading">Cara Transfer ATM BSI</h3>
                                <ol class="mt-4 space-y-1" id="instructionList"></ol>
                            </div>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-between">
                                <button type="button" class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-600 transition hover:bg-slate-50" data-prev-step="2">Kembali</button>
                                <button type="button" class="rounded-2xl bg-blue-900 px-6 py-3 font-semibold text-white transition hover:bg-blue-800" data-next-step="4">Saya Sudah Bayar</button>
                            </div>
                        </div>
                    </section>

                    <section class="payment-step mt-8 hidden rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8" data-step="4">
                        <h2 class="text-3xl font-bold text-blue-950">Verifikasi Pembayaran</h2>
                        <p class="mt-2 text-slate-500">Pastikan informasi pembayaran Anda benar sebelum kami tandai berhasil.</p>

                        <div class="mt-8 grid gap-5 lg:grid-cols-[minmax(0,1.3fr)_minmax(280px,0.7fr)]">
                            <div class="rounded-[1.75rem] border border-slate-200 p-6">
                                <h3 class="text-xl font-bold text-blue-900">Ringkasan Pembayaran</h3>
                                <div class="mt-5 space-y-3 text-sm text-slate-600">
                                    <div class="flex justify-between gap-4">
                                        <span>Nama Orang Tua / Wali</span>
                                        <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span>Formulir</span>
                                        <span class="font-semibold text-slate-800">SPMB Reguler 2026/2027</span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span>Metode Pembayaran</span>
                                        <span class="font-semibold text-slate-800" id="summaryMethodLabel">VA Bank BSI</span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span>Kode Pembayaran</span>
                                        <span class="font-semibold text-slate-800" id="summaryCodeLabel">8877 2026 1001</span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span>Nominal</span>
                                        <span class="font-semibold text-slate-800">Rp 102.500</span>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-[1.75rem] bg-amber-50 p-6 ring-1 ring-amber-100">
                                <h3 class="text-xl font-bold text-amber-800">Checklist Verifikasi</h3>
                                <ul class="mt-5 space-y-3 text-sm leading-7 text-slate-700">
                                    <li>&bull; Pembayaran dilakukan sesuai nominal.</li>
                                    <li>&bull; Bukti transfer / pembayaran telah disimpan.</li>
                                    <li>&bull; Metode pembayaran yang dipilih sudah benar.</li>
                                    <li>&bull; Siap melanjutkan ke status pembayaran berhasil.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-between">
                            <button type="button" class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-600 transition hover:bg-slate-50" data-prev-step="3">Kembali</button>
                            <button type="button" class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white transition hover:bg-emerald-500" data-next-step="5">Verifikasi Sekarang</button>
                        </div>
                    </section>

                    <section class="payment-step mt-8 hidden rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8" data-step="5">
                        <div class="rounded-[1.75rem] bg-emerald-50 p-8 text-center ring-1 ring-emerald-100">
                            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-600 text-4xl text-white">&check;</div>
                            <h2 class="mt-6 text-3xl font-extrabold text-emerald-700">Pembayaran Berhasil</h2>
                            <p class="mt-3 text-slate-600">Pembelian formulir pendaftaran RA Fadhilah telah berhasil diverifikasi. Anda sekarang dapat mengunduh bukti pembayaran atau langsung membuka formulir pendaftaran.</p>
                        </div>

                        <div class="mt-8 grid gap-5 md:grid-cols-2">
                            <button type="button" id="downloadProofButton" class="rounded-[1.5rem] bg-blue-900 px-6 py-4 text-lg font-semibold text-white transition hover:bg-blue-800">
                                Unduh Bukti Pembayaran
                            </button>
                            <a href="{{ route('data-diri') }}" class="inline-flex items-center justify-center rounded-[1.5rem] bg-emerald-600 px-6 py-4 text-lg font-semibold text-white transition hover:bg-emerald-500">
                                Buka Formulir Pendaftaran
                            </a>
                        </div>
                    </section>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>

    <script>
        const stepIndicators = document.querySelectorAll('[data-step-indicator]');
        const steps = document.querySelectorAll('.payment-step');
        const nextButtons = document.querySelectorAll('[data-next-step]');
        const prevButtons = document.querySelectorAll('[data-prev-step]');
        const methodCards = document.querySelectorAll('.method-card');
        const methodInputs = document.querySelectorAll('input[name="paymentMethod"]');

        const selectedMethodLabel = document.getElementById('selectedMethodLabel');
        const selectedMethodSubLabel = document.getElementById('selectedMethodSubLabel');
        const paymentCodeLabel = document.getElementById('paymentCodeLabel');
        const instructionList = document.getElementById('instructionList');
        const instructionHeading = document.getElementById('instructionHeading');
        const summaryMethodLabel = document.getElementById('summaryMethodLabel');
        const summaryCodeLabel = document.getElementById('summaryCodeLabel');
        const downloadProofButton = document.getElementById('downloadProofButton');
        const paymentBrandBadge = document.getElementById('paymentBrandBadge');
        const copyVaButton = document.getElementById('copyVaButton');
        const paymentCountdown = document.getElementById('paymentCountdown');

        const methodConfig = {
            'VA Bank BSI': {
                label: 'BSI Virtual Account',
                shortLabel: 'BSI Virtual Account',
                badge: 'BSI',
                heading: 'Cara Transfer ATM BSI',
                code: '8877 2026 1001',
                instructions: [
                    'Masukkan kartu ATM dan PIN BSI Anda.',
                    'Pilih menu Pembayaran lalu masuk ke Virtual Account.',
                    'Masukkan nomor VA: 8877 2026 1001.',
                    'Pastikan nama dan nominal sudah benar, lalu konfirmasi.',
                    'Simpan bukti transfer sebagai arsip.'
                ]
            },
            'VA BCA': {
                label: 'BCA Virtual Account',
                shortLabel: 'BCA Virtual Account',
                badge: 'BCA',
                heading: 'Cara Transfer ATM BCA',
                code: '3901 2200 1025',
                instructions: [
                    'Masukkan kartu ATM dan PIN BCA Anda.',
                    'Pilih Transfer lalu ke Rekening BCA Virtual Account.',
                    'Masukkan nomor VA: 3901 2200 1025.',
                    'Pastikan nama dan nominal sudah benar, lalu konfirmasi.',
                    'Simpan bukti transfer sebagai arsip.'
                ]
            },
            'VA Mandiri': {
                label: 'Mandiri Virtual Account',
                shortLabel: 'Mandiri Virtual Account',
                badge: 'MDR',
                heading: 'Cara Transfer ATM Mandiri',
                code: '70012 2026 1025',
                instructions: [
                    'Masukkan kartu ATM dan PIN Mandiri Anda.',
                    'Pilih Bayar/Beli lalu menu Multipayment.',
                    'Masukkan kode pembayaran 70012 2026 1025.',
                    'Periksa nama tagihan dan nominal Rp 102.500.',
                    'Simpan bukti transfer sebagai arsip.'
                ]
            },
            'VA BNI': {
                label: 'BNI Virtual Account',
                shortLabel: 'BNI Virtual Account',
                badge: 'BNI',
                heading: 'Cara Transfer ATM BNI',
                code: '8808 1025 2026',
                instructions: [
                    'Masukkan kartu ATM dan PIN BNI Anda.',
                    'Pilih menu Pembayaran lalu Virtual Account Billing.',
                    'Masukkan nomor VA: 8808 1025 2026.',
                    'Pastikan nama tagihan dan nominal sudah sesuai.',
                    'Simpan bukti transfer sebagai arsip.'
                ]
            },
            'VA BRI': {
                label: 'BRI Virtual Account',
                shortLabel: 'BRI Virtual Account',
                badge: 'BRI',
                heading: 'Cara Transfer ATM BRI',
                code: '26215 1025 2026',
                instructions: [
                    'Masukkan kartu ATM dan PIN BRI Anda.',
                    'Pilih Pembayaran lalu BRIVA.',
                    'Masukkan nomor BRIVA 26215 1025 2026.',
                    'Pastikan nominal yang tampil Rp 102.500.',
                    'Simpan bukti transfer sebagai arsip.'
                ]
            },
            'DANA': {
                label: 'DANA',
                shortLabel: 'DANA',
                badge: 'D',
                heading: 'Cara Bayar via DANA',
                code: 'DANA-RAF-1025',
                instructions: [
                    'Buka aplikasi DANA di ponsel Anda.',
                    'Pilih menu bayar atau masukkan kode transaksi sekolah.',
                    'Masukkan kode pembayaran DANA-RAF-1025.',
                    'Pastikan total tagihan Rp 102.500 lalu konfirmasi.',
                    'Simpan bukti pembayaran digital sebagai arsip.'
                ]
            },
            'GoPay': {
                label: 'GoPay',
                shortLabel: 'GoPay',
                badge: 'G',
                heading: 'Cara Bayar via GoPay',
                code: 'GOPAY-RAF-1025',
                instructions: [
                    'Buka aplikasi Gojek lalu pilih GoPay.',
                    'Masuk ke menu pembayaran atau tagihan.',
                    'Masukkan kode pembayaran GOPAY-RAF-1025.',
                    'Pastikan nominal pembayaran Rp 102.500 sudah sesuai.',
                    'Simpan riwayat transaksi sebagai arsip.'
                ]
            },
            'OVO': {
                label: 'OVO',
                shortLabel: 'OVO',
                badge: 'OVO',
                heading: 'Cara Bayar via OVO',
                code: 'OVO-RAF-1025',
                instructions: [
                    'Buka aplikasi OVO.',
                    'Pilih menu pembayaran atau tagihan.',
                    'Masukkan kode transaksi OVO-RAF-1025.',
                    'Cek total pembayaran Rp 102.500 sebelum konfirmasi.',
                    'Simpan bukti pembayaran digital sebagai arsip.'
                ]
            },
            'QRIS': {
                label: 'QRIS',
                shortLabel: 'QRIS',
                badge: 'QRIS',
                heading: 'Cara Bayar via QRIS',
                code: 'QRIS-RAF-2026-01',
                instructions: [
                    'Buka aplikasi mobile banking atau e-wallet Anda.',
                    'Pilih menu scan QRIS.',
                    'Scan kode QRIS yang ditampilkan sistem.',
                    'Pastikan nominal pembayaran Rp 102.500 lalu konfirmasi.',
                    'Simpan bukti pembayaran digital sebagai arsip.'
                ]
            },
            'Bayar di Sekolah': {
                label: 'Bayar di Sekolah',
                shortLabel: 'Pembayaran TU',
                badge: 'TU',
                heading: 'Cara Bayar di Sekolah',
                code: 'TU-RAF-1025',
                instructions: [
                    'Datang ke bagian TU sekolah pada jam operasional.',
                    'Sampaikan bahwa pembayaran untuk formulir pendaftaran.',
                    'Sebutkan kode referensi TU-RAF-1025 kepada petugas.',
                    'Bayarkan nominal Rp 102.500 dan minta bukti pembayaran.',
                    'Simpan bukti bayar tersebut sebagai arsip.'
                ]
            }
        };

        let currentStep = 1;
        let selectedMethod = 'VA Bank BSI';
        let countdownInterval;
        let countdownRemainingSeconds = (23 * 60 * 60) + (42 * 60) + 6;

        function renderStep(step) {
            currentStep = step;

            steps.forEach(section => {
                section.classList.toggle('hidden', Number(section.dataset.step) !== step);
            });

            stepIndicators.forEach(indicator => {
                const indicatorStep = Number(indicator.dataset.stepIndicator);
                const isActive = indicatorStep === step;
                const isPassed = indicatorStep < step;

                indicator.className = 'step-indicator rounded-2xl px-4 py-3 text-center text-sm font-semibold';

                if (isActive) {
                    indicator.classList.add('bg-blue-900', 'text-white');
                } else if (isPassed) {
                    indicator.classList.add('bg-emerald-100', 'text-emerald-700', 'ring-1', 'ring-emerald-200');
                } else {
                    indicator.classList.add('bg-white', 'text-slate-500', 'ring-1', 'ring-slate-200');
                }
            });
        }

        function updateMethodUI() {
            methodCards.forEach(card => {
                const active = card.dataset.method === selectedMethod;
                card.classList.toggle('is-active', active);
                card.classList.toggle('border-blue-500', active);
                card.classList.toggle('bg-blue-50', active);
                card.classList.toggle('shadow-md', active);
                card.classList.toggle('border-blue-300', active);
                card.classList.toggle('border-slate-200', !active);
                card.querySelector('.payment-option-radio')?.classList.toggle('border-blue-500', active);
                card.querySelector('.payment-option-radio')?.classList.toggle('border-slate-300', !active);
                card.querySelector('input').checked = active;
            });

            const config = methodConfig[selectedMethod];
            selectedMethodLabel.textContent = config.label;
            selectedMethodSubLabel.textContent = config.shortLabel;
            paymentCodeLabel.textContent = config.code;
            paymentBrandBadge.textContent = config.badge;
            instructionHeading.textContent = config.heading;
            summaryMethodLabel.textContent = selectedMethod;
            summaryCodeLabel.textContent = config.code;
            instructionList.innerHTML = config.instructions.map((item, index) => `
                <li class="flex items-start gap-4 border-b border-slate-200 py-3 last:border-b-0">
                    <span class="instruction-step-number flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-extrabold text-blue-700">${index + 1}</span>
                    <span class="pt-0.5 text-base font-medium leading-6 text-slate-700">${item}</span>
                </li>
            `).join('');
        }

        function formatCountdown(totalSeconds) {
            const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            const seconds = String(totalSeconds % 60).padStart(2, '0');

            return `${hours}:${minutes}:${seconds}`;
        }

        function startCountdown() {
            clearInterval(countdownInterval);
            paymentCountdown.textContent = formatCountdown(countdownRemainingSeconds);

            countdownInterval = setInterval(() => {
                if (countdownRemainingSeconds > 0) {
                    countdownRemainingSeconds -= 1;
                }

                paymentCountdown.textContent = formatCountdown(countdownRemainingSeconds);
            }, 1000);
        }

        nextButtons.forEach(button => {
            button.addEventListener('click', () => {
                const nextStep = Number(button.dataset.nextStep);
                renderStep(nextStep);

                if (nextStep === 3) {
                    startCountdown();
                }
            });
        });

        prevButtons.forEach(button => {
            button.addEventListener('click', () => renderStep(Number(button.dataset.prevStep)));
        });

        methodInputs.forEach(input => {
            input.addEventListener('change', () => {
                selectedMethod = input.value;
                updateMethodUI();
            });
        });

        downloadProofButton.addEventListener('click', () => {
            const content = [
                'BUKTI PEMBAYARAN FORMULIR SPMB RA FADHILAH',
                '==========================================',
                `Nama Orang Tua/Wali : {{ Auth::user()->name }}`,
                'Formulir            : SPMB Reguler 2026/2027',
                `Metode Pembayaran   : ${selectedMethod}`,
                `Kode Pembayaran     : ${methodConfig[selectedMethod].code}`,
                'Nominal             : Rp 102.500',
                'Status              : Pembayaran Berhasil',
                'Sekolah             : RAUDHATUL ATHFAL FADHILAH'
            ].join('\n');

            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'bukti-pembayaran-formulir-ra-fadhilah.txt';
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        });

        copyVaButton.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(methodConfig[selectedMethod].code);
                copyVaButton.querySelector('span').textContent = 'Nomor VA tersalin';

                setTimeout(() => {
                    copyVaButton.querySelector('span').textContent = 'Salin nomor VA';
                }, 1800);
            } catch (error) {
                copyVaButton.querySelector('span').textContent = 'Gagal menyalin';

                setTimeout(() => {
                    copyVaButton.querySelector('span').textContent = 'Salin nomor VA';
                }, 1800);
            }
        });

        updateMethodUI();
        renderStep(1);
    </script>
</body>
</html>
