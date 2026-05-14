<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster PPDB TK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-sky-100 via-white to-cyan-50 text-slate-900">
    <header class="border-b border-sky-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('profile.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('image/logo_TK.png') }}" alt="Logo TK" class="h-12 w-12 object-contain">
                <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA" class="h-12 w-12 object-contain">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-500">Informasi PPDB</p>
                    <h1 class="text-lg font-extrabold text-slate-900">Poster Pendaftaran</h1>
                </div>
            </a>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('ppdb.info') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Lihat Halaman PPDB
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                    Masuk Portal PPDB
                </a>
            </div>
        </div>
    </header>

    <main class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <div class="text-center">
                <p class="text-sm font-bold uppercase tracking-[0.35em] text-sky-600">Persyaratan PPDB</p>
                <h2 class="mt-3 text-3xl font-extrabold text-slate-900 sm:text-4xl">Poster Informasi PPDB TK</h2>
                <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    Halaman ini menampilkan poster PPDB seperti referensi yang Anda kirim. Pengunjung sekolah bisa melihat informasi syarat, jadwal, dan kontak pendaftaran langsung dari poster ini.
                </p>
            </div>

            <div class="mt-10 rounded-[2rem] bg-white p-4 shadow-[0_24px_60px_rgba(15,23,42,0.12)] sm:p-6">
                <img src="{{ asset('image/poster-tk.jpg') }}" alt="Poster PPDB TK" class="mx-auto w-full max-w-3xl rounded-[1.5rem] object-contain">
            </div>
        </div>
    </main>
</body>
</html>
