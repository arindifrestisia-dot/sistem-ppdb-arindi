<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil</title>
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
        @php($activeMenu = 'data-diri')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-5xl rounded-[2rem] border-2 border-blue-300 bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] md:p-10">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full border-4 border-emerald-600 text-2xl font-bold text-emerald-600">✓</div>
                    </div>

                    <div class="mt-8 text-center">
                        <h1 class="text-3xl font-extrabold text-slate-900 md:text-4xl">Pendaftaran Berhasil Dikirim!</h1>
                        <p class="mx-auto mt-3 max-w-2xl text-lg leading-8 text-slate-600">
                            Formulir dan berkas Anda telah diterima sistem PPDB. Simpan nomor registrasi berikut sebagai bukti pendaftaran, lalu lanjutkan ke pemilihan jadwal wawancara.
                        </p>
                    </div>

                    <div class="mx-auto mt-8 max-w-md rounded-[1.5rem] bg-stone-50 px-6 py-5 text-center shadow-inner">
                        <p class="text-sm font-semibold text-slate-500">Nomor Registrasi</p>
                        <p class="mt-2 text-3xl font-extrabold tracking-[0.14em] text-slate-900">{{ $registration->registration_number }}</p>
                    </div>

                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <span class="rounded-full bg-lime-100 px-4 py-2 text-sm font-semibold text-lime-700">✓ Formulir Aktif</span>
                        <span class="rounded-full bg-lime-100 px-4 py-2 text-sm font-semibold text-lime-700">✓ Pembayaran Lunas</span>
                        <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">• Menunggu Verifikasi Berkas</span>
                    </div>

                    <div class="mt-10 rounded-[1.5rem] bg-stone-50 p-6">
                        <h2 class="text-2xl font-bold text-slate-900">Langkah selanjutnya:</h2>
                        <ol class="mt-4 space-y-3 text-lg text-slate-700">
                            <li>1. Pilih jadwal wawancara yang tersedia di portal PPDB.</li>
                            <li>2. Pantau status verifikasi berkas di portal PPDB.</li>
                            <li>3. Siapkan proses daftar ulang jika dinyatakan diterima.</li>
                            <li>4. Hubungi sekolah jika ada pertanyaan: <span class="font-bold text-blue-700">0821 6207 736 / 0822 8681 7315</span></li>
                        </ol>
                    </div>

                    <div class="mt-10 flex flex-col justify-center gap-3 md:flex-row">
                        <a href="{{ route('wawancara') }}" class="inline-flex w-full items-center justify-center rounded-[1.25rem] bg-blue-900 px-6 py-4 text-lg font-bold text-white transition hover:bg-blue-800 md:w-auto md:min-w-[320px]">
                            Pilih Jadwal Wawancara
                        </a>
                        <a href="{{ route('data-diri.download.kartu') }}" class="inline-flex w-full items-center justify-center rounded-[1.25rem] border border-blue-300 bg-white px-6 py-4 text-lg font-bold text-blue-900 transition hover:bg-blue-50 md:w-auto md:min-w-[320px]">
                            Cetak Kartu Bukti
                        </a>
                    </div>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>
</body>
</html>
