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
        $isSelectionPublished = (bool) ($registration && $registration->selection_published_at);
    @endphp
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'beranda')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="max-w-6xl">
                    <h2 class="text-2xl font-extrabold text-blue-950 md:text-3xl">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="mt-2 text-sm text-slate-500 md:text-base">Pantau tahapan pendaftaran ananda dan lanjutkan proses SPMB RA Fadhilah dari panel ini.</p>
                </div>

                <div class="mt-8 grid gap-5 xl:grid-cols-3">
                    <article class="rounded-3xl bg-white p-6 shadow-[0_10px_25px_rgba(15,23,42,0.12)] ring-1 ring-blue-100">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-3xl font-extrabold text-blue-950">Belum</p>
                                <p class="mt-1 text-sm text-slate-600">Pembelian Formulir</p>
                            </div>
                            <span class="rounded-2xl bg-amber-100 px-4 py-2 text-sm font-bold text-amber-700">Tahap 1</span>
                        </div>
                        <a href="{{ route('ortu.formulir') }}" class="mt-8 inline-flex text-sm font-semibold text-blue-900">Lanjut beli formulir &rarr;</a>
                    </article>

                    <article class="rounded-3xl bg-white p-6 shadow-[0_10px_25px_rgba(15,23,42,0.12)] ring-1 ring-blue-100">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-3xl font-extrabold text-blue-950">Belum</p>
                                <p class="mt-1 text-sm text-slate-600">Pengisian Data Diri</p>
                            </div>
                            <span class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-600">Tahap 2</span>
                        </div>
                        <a href="{{ route('data-diri') }}" class="mt-8 inline-flex text-sm font-semibold text-blue-900">Buka menu data diri &rarr;</a>
                    </article>

                    <article class="rounded-3xl bg-white p-6 shadow-[0_10px_25px_rgba(15,23,42,0.12)] ring-1 ring-blue-100">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-3xl font-extrabold text-blue-950">{{ $isSelectionPublished ? 'Terbit' : 'Menunggu' }}</p>
                                <p class="mt-1 text-sm text-slate-600">Status Kelulusan</p>
                            </div>
                            <span class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-600">Tahap 3</span>
                        </div>
                        <a href="{{ route('status-lulus') }}" class="mt-8 inline-flex text-sm font-semibold {{ $isSelectionPublished ? 'text-blue-900' : 'text-slate-400' }}">
                            {{ $isSelectionPublished ? 'Lihat status kelulusan ->' : 'Pantau proses seleksi ->' }}
                        </a>
                    </article>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_340px]">
                    <section class="rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">Ringkasan</p>
                                <h3 class="mt-2 text-xl font-bold text-blue-950 md:text-2xl">Perjalanan Pendaftaran Anda</h3>
                            </div>
                            <a href="{{ route('ortu.formulir') }}" class="inline-flex rounded-full bg-blue-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                                Mulai dari Formulir
                            </a>
                        </div>

                        <div class="mt-8 grid gap-4 md:grid-cols-3">
                            <div class="rounded-3xl bg-blue-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-blue-700">1. Formulir</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Lakukan pembelian formulir pendaftaran untuk mendapatkan akses proses SPMB.</p>
                            </div>
                            <div class="rounded-3xl bg-emerald-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-emerald-700">2. Data Diri</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Isi data peserta didik, orang tua, dan data pendukung lainnya dengan lengkap.</p>
                            </div>
                            <div class="rounded-3xl bg-amber-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-amber-700">3. Verifikasi</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Pantau verifikasi pembayaran dan kelengkapan berkas sampai proses selesai.</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-[2rem] bg-blue-900 p-6 text-white shadow-[0_14px_30px_rgba(15,23,42,0.12)]">
                        <h3 class="text-xl font-bold md:text-2xl">Langkah Selanjutnya</h3>
                        <ul class="mt-6 space-y-5 text-sm md:text-base">
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-yellow-300"></span>1. Beli formulir pendaftaran</li>
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-yellow-300"></span>2. Pilih metode pembayaran</li>
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-yellow-300"></span>3. Ikuti instruksi dan verifikasi pembayaran</li>
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-yellow-300"></span>4. Buka formulir pendaftaran dan isi data diri</li>
                        </ul>
                    </section>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>
</body>
</html>
