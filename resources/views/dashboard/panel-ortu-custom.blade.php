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
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-sky-500 text-slate-900">
    <div class="flex min-h-screen flex-col md:flex-row">
        <aside class="w-full bg-blue-900 text-white md:min-h-screen md:w-72">
            <div class="border-b border-blue-800 px-5 py-5">
                <h1 class="text-2xl font-extrabold text-yellow-300">SPMB RA FADHILAH</h1>
                <p class="text-sm text-sky-100">(Sistem Penerimaan Murid Baru)</p>
            </div>

            <div class="border-b border-blue-800 px-5 py-6 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/20 text-xs font-medium text-sky-100">
                    User Image
                </div>
                <p class="mt-4 text-lg font-bold">{{ Auth::user()->name }}</p>
                <span class="mt-2 inline-flex rounded-md bg-yellow-300 px-3 py-1 text-xs font-extrabold uppercase text-blue-900">User</span>
            </div>

            <nav class="px-4 py-6 text-sm font-semibold">
                <p class="px-3 text-xs font-bold uppercase tracking-[0.25em] text-yellow-300">Menu Utama</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg bg-yellow-400 px-4 py-3 text-blue-950">
                        <span class="text-lg">▦</span>
                        Beranda
                    </a>
                    <a href="{{ route('data-diri') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sky-100 transition hover:bg-blue-800">
                        <span class="text-lg">✎</span>
                        Formulir
                    </a>
                    <a href="{{ route('data-diri') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sky-100 transition hover:bg-blue-800">
                        <span class="text-lg">◔</span>
                        Data Diri
                    </a>
                    <a href="#" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sky-100 transition hover:bg-blue-800">
                        <span class="text-lg">✓</span>
                        Status Lulus
                    </a>
                </div>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex flex-wrap items-center justify-between gap-4 bg-white px-5 py-4 shadow md:px-8">
                <button type="button" class="text-2xl text-slate-400">≡</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="font-semibold text-red-500">Keluar</button>
                </form>
            </header>

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="max-w-6xl">
                    <h2 class="text-3xl font-extrabold text-slate-900 md:text-5xl">Selamat Datang, {{ Auth::user()->name }}!</h2>
                    <p class="mt-2 text-lg text-slate-500">Berikut adalah ringkasan status pendaftaran Anda saat ini.</p>
                </div>

                <div class="mt-8 grid gap-5 xl:grid-cols-3">
                    <article class="rounded-sm bg-amber-400 p-5 shadow-[0_10px_25px_rgba(15,23,42,0.12)]">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-5xl font-extrabold text-slate-900">Belum</p>
                                <p class="mt-1 text-lg text-slate-900">Data Identitas Siswa</p>
                            </div>
                            <span class="text-6xl text-amber-600">◌</span>
                        </div>
                        <a href="{{ route('data-diri') }}" class="mt-8 inline-flex text-base font-medium text-slate-900">Kelola Data ↺</a>
                    </article>

                    <article class="rounded-sm bg-amber-400 p-5 shadow-[0_10px_25px_rgba(15,23,42,0.12)]">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-5xl font-extrabold text-slate-900">Belum</p>
                                <p class="mt-1 text-lg text-slate-900">Data Orang Tua / Wali</p>
                            </div>
                            <span class="text-6xl text-amber-600">◉</span>
                        </div>
                        <a href="{{ route('data-diri') }}" class="mt-8 inline-flex text-base font-medium text-slate-900">Kelola Data ↺</a>
                    </article>

                    <article class="rounded-sm bg-amber-400 p-5 shadow-[0_10px_25px_rgba(15,23,42,0.12)]">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-5xl font-extrabold text-slate-900">Belum</p>
                                <p class="mt-1 text-lg text-slate-900">Data Rumah / Tujuan</p>
                            </div>
                            <span class="text-6xl text-amber-600">⌂</span>
                        </div>
                        <a href="{{ route('data-diri') }}" class="mt-8 inline-flex text-base font-medium text-slate-900">Kelola Data ↺</a>
                    </article>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_320px]">
                    <section class="overflow-hidden rounded-lg bg-white shadow-[0_14px_30px_rgba(15,23,42,0.12)]">
                        <div class="border-t-4 border-blue-900 px-5 py-4">
                            <h3 class="text-xl font-bold text-slate-700">Ringkasan Profil Anda</h3>
                        </div>
                        <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-slate-100 text-5xl">👤</div>
                            <p class="mt-6 text-lg text-slate-400">Anda belum mengisi data diri secara lengkap.</p>
                            <a href="{{ route('data-diri') }}" class="mt-6 inline-flex rounded-full bg-blue-900 px-8 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                                Mulai Isi Data Sekarang
                            </a>
                        </div>
                    </section>

                    <section class="rounded-lg bg-teal-500 p-6 text-white shadow-[0_14px_30px_rgba(15,23,42,0.12)]">
                        <h3 class="text-2xl font-bold">Langkah Selanjutnya</h3>
                        <ul class="mt-6 space-y-5 text-lg">
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-pink-200"></span>1. Beli Formulir</li>
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-pink-200"></span>2. Isi Data Diri Lengkap</li>
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-pink-200"></span>3. Upload Berkas Persyaratan</li>
                            <li class="flex gap-3"><span class="mt-2 h-3 w-3 rounded-full bg-pink-200"></span>4. Simpan Permanen</li>
                        </ul>
                    </section>
                </div>
            </main>

            <footer class="mt-auto flex flex-col gap-2 bg-white px-5 py-4 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between md:px-8">
                <p>Copyright © 2026 SPMB Online. All rights reserved.</p>
                <p class="font-semibold">RAUDHATUL ATHFAL FADHILAH</p>
            </footer>
        </div>
    </div>
</body>
</html>
