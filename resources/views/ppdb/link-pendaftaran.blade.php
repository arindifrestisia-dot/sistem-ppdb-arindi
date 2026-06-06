<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB RA Fadhilah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-sky-100 text-slate-900">
    <header class="sticky top-0 z-50 border-b border-sky-200/80 bg-sky-100/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('ppdb.info') }}" class="flex items-center gap-3">
                <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="h-14 w-14 object-contain sm:h-16 sm:w-16">
                <div class="max-w-xs">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-sky-700 sm:text-xs">Penerimaan Peserta Didik Baru</p>
                    <h1 class="text-sm font-extrabold uppercase leading-tight text-sky-900 sm:text-lg">Raudhatul Athfal Fadhilah</h1>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-sm font-semibold text-sky-700 md:flex">
                <a href="#persyaratan" class="transition hover:text-sky-950">Persyaratan</a>
                <a href="#alur" class="transition hover:text-sky-950">Alur</a>
                <a href="#hubungi-kami" class="transition hover:text-sky-950">Hubungi Kami</a>
                <a href="{{ route('login') }}" class="rounded-full bg-yellow-300 px-4 py-2 text-xs font-extrabold uppercase tracking-wide text-slate-900 shadow-lg shadow-sky-700/20 transition hover:-translate-y-0.5">
                    Portal Masuk
                </a>
                <a href="{{ route('home') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-sky-800 shadow-lg shadow-sky-700/15 ring-1 ring-sky-200 transition hover:-translate-y-0.5 hover:bg-sky-50 hover:text-sky-950" aria-label="Kembali ke profil sekolah" title="Kembali ke profil sekolah">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m3 11 9-8 9 8" />
                        <path d="M5 10v10h14V10" />
                        <path d="M9 20v-6h6v6" />
                    </svg>
                </a>
            </nav>
        </div>
    </header>

    <main>
        <section class="mx-auto grid max-w-7xl gap-10 px-4 pb-20 pt-10 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:pb-24 lg:pt-14">
            <div class="overflow-hidden rounded-[2rem] bg-gradient-to-br from-green-600 to-emerald-400 p-3 shadow-[0_25px_60px_rgba(15,23,42,0.25)]">
                <img src="{{ asset('image/berita1.png') }}" alt="Kegiatan siswa RA Fadhilah" class="h-full min-h-[280px] w-full rounded-[1.5rem] object-cover">
            </div>

            <div class="flex flex-col justify-center">
                <span class="mb-3 inline-flex w-fit rounded-full bg-yellow-300 px-4 py-2 text-xs font-bold uppercase tracking-[0.3em] text-slate-900">Tahun Ajaran 2026/2027</span>
                <h2 class="max-w-xl text-4xl font-extrabold leading-tight text-sky-900 sm:text-5xl">Selamat Datang di Raudhatul Athfal Fadhilah.</h2>
                <p class="mt-5 max-w-xl text-base leading-8 text-slate-700 sm:text-lg">
                    

                <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-7 py-4 text-sm font-extrabold uppercase tracking-wide text-white shadow-[0_18px_35px_rgba(22,101,52,0.35)] transition hover:-translate-y-0.5 hover:bg-green-700">
                        Masuk ke Portal PPDB
                    </a>
                    <a href="#persyaratan" class="inline-flex items-center justify-center rounded-2xl border-2 border-white/75 bg-white px-7 py-4 text-sm font-bold uppercase tracking-wide text-slate-900 transition hover:-translate-y-0.5">
                        Pelajari Syarat
                    </a>
                </div>
            </div>
        </section>

        <section id="persyaratan" class="scroll-mt-28 px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl text-center">
                <p class="text-sm font-bold uppercase tracking-[0.4em] text-slate-800">Syarat & Dokumen</p>
                <h3 class="mt-3 text-3xl font-extrabold text-slate-900 sm:text-4xl">Panduan Pendaftaran</h3>
                <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-slate-700 sm:text-base">
                    Pastikan seluruh dokumen dasar sudah disiapkan. Saat menu `Persyaratan` di atas diklik, halaman ini akan bergulir ke bagian panduan pendaftaran.
                </p>
            </div>

            <div class="mx-auto mt-12 grid max-w-6xl gap-6 lg:grid-cols-2">
                <article class="rounded-[2rem] bg-white p-8 shadow-[0_24px_50px_rgba(15,23,42,0.18)]">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"/>
                            </svg>
                        </div>
                        <h4 class="text-xl font-extrabold text-slate-900">Persyaratan Pendaftaran</h4>
                    </div>

                    <ul class="mt-8 space-y-4 text-sm leading-7 text-slate-700 sm:text-base">
                        <li class="flex gap-3"><span class="mt-1.5 h-3 w-3 shrink-0 rounded-full bg-green-500"></span>Usia minimal 4 tahun pada 1 Juli 2027.</li>
                        <li class="flex gap-3"><span class="mt-1.5 h-3 w-3 shrink-0 rounded-full bg-green-500"></span>Fotokopi Akta Kelahiran anak.</li>
                        <li class="flex gap-3"><span class="mt-1.5 h-3 w-3 shrink-0 rounded-full bg-green-500"></span>Fotokopi Kartu Keluarga.</li>
                        <li class="flex gap-3"><span class="mt-1.5 h-3 w-3 shrink-0 rounded-full bg-green-500"></span>Fotokopi KTP orang tua atau wali.</li>
                        <li class="flex gap-3"><span class="mt-1.5 h-3 w-3 shrink-0 rounded-full bg-green-500"></span>Pas foto anak 3x4 sebanyak 2 lembar.</li>
                    </ul>
                </article>

                <article class="rounded-[2rem] bg-white p-8 shadow-[0_24px_50px_rgba(15,23,42,0.18)]">
                    <h4 class="text-xl font-extrabold text-slate-900">Tanggal Penting</h4>
                    <div class="mt-8 space-y-4">
                        <div class="rounded-3xl bg-sky-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-sky-600">Pendaftaran</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">1 Januari - 31 Juli 2027</p>
                        </div>
                        <div class="rounded-3xl bg-sky-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-sky-600">Wawancara</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">10 Juli - 15 Agustus 2027</p>
                        </div>
                        <div class="rounded-3xl bg-sky-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-sky-600">Pengumuman</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">20 Agustus 2027</p>
                        </div>
                        <div class="rounded-3xl bg-sky-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-sky-600">Daftar Ulang</p>
                            <p class="mt-2 text-lg font-bold text-slate-900">21 - 28 Agustus 2027</p>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section id="alur" class="scroll-mt-28 bg-sky-200/60 px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl text-center">
                <p class="text-sm font-bold uppercase tracking-[0.4em] text-slate-800">Alur Pendaftaran</p>
                <h3 class="mt-3 text-3xl font-extrabold text-slate-900 sm:text-4xl">Tahapan PPDB</h3>
                <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-slate-700 sm:text-base">
                    
                </p>
            </div>

            <div class="mx-auto mt-12 grid max-w-6xl gap-5 md:grid-cols-2 xl:grid-cols-4">
                @php
                    $steps = [
                        ['Langkah 1', 'Masuk ke portal PPDB', 'Calon orang tua memilih portal masuk untuk membuka halaman login PPDB.'],
                        ['Langkah 2', 'Buat akun bila belum punya', 'Jika belum memiliki akun, lakukan pendaftaran akun dari halaman login PPDB.'],
                        ['Langkah 3', 'Login ke sistem', 'Setelah akun tersedia, masuk menggunakan username dan password yang sudah dibuat.'],
                        ['Langkah 4', 'Isi formulir awal', 'Lengkapi identitas siswa dan data dasar pendaftaran dengan benar.'],
                        ['Langkah 5', 'Lengkapi data orang tua', 'Masukkan data ayah, ibu, atau wali untuk proses verifikasi sekolah.'],
                        ['Langkah 6', 'Upload berkas', 'Unggah seluruh dokumen persyaratan sesuai panduan pendaftaran.'],
                        ['Langkah 7', 'Pantau proses seleksi', 'Ikuti verifikasi, wawancara, dan pengumuman dari dashboard PPDB.'],
                        ['Langkah 8', 'Daftar ulang', 'Selesaikan daftar ulang dan pembayaran untuk mengunci kursi siswa.'],
                    ];
                @endphp

                @foreach ($steps as [$label, $title, $description])
                    <article class="rounded-[1.75rem] bg-white p-5 shadow-[0_18px_40px_rgba(15,23,42,0.18)]">
                        <p class="text-xs font-extrabold uppercase tracking-[0.25em] text-sky-500">{{ $label }}</p>
                        <h4 class="mt-3 text-lg font-extrabold text-slate-900">{{ $title }}</h4>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="hubungi-kami" class="scroll-mt-28 bg-sky-50 px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-6xl rounded-[2rem] bg-white p-8 shadow-[0_24px_50px_rgba(15,23,42,0.16)] sm:p-10">
                <div class="grid gap-10 lg:grid-cols-[0.75fr_1.25fr] lg:items-center">
                    <div class="text-center lg:border-r lg:border-slate-200 lg:pr-10">
                        <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="mx-auto h-28 w-28 object-contain">
                        <h3 class="mt-6 text-2xl font-extrabold text-slate-900">RA Fadhilah</h3>
                        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Yayasan Darel Fadhilah</p>
                        <div class="mt-8 space-y-3 text-sm text-slate-600">
                            <p>Akreditasi A</p>
                            <p>NPSN 69731079</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.35em] text-sky-600">Hubungi Kami</p>
                        <h3 class="mt-3 text-3xl font-extrabold text-slate-900">Kontak Sekolah</h3>
                        <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                             
                        </p>

                        <div class="mt-8 space-y-6 text-sm leading-7 text-slate-700 sm:text-base">
                            <div>
                                <p class="font-extrabold text-slate-900">Alamat Kantor</p>
                                <p>CCV9+42C, Jl. Muhajirin, Sidomulyo Barat, Kec. Tampan, Kota Pekanbaru, Riau 28294</p>
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-900">Email</p>
                                <a href="mailto:admin@rafadhilah.sch.id" class="text-sky-600 hover:underline">admin@rafadhilah.sch.id</a>
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-900">Telepon</p>
                                <p>0821 6207 736</p>
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-900">WhatsApp</p>
                                <p>0821 6207 736 / 0822 8681 7315</p>
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-900">Jam Kerja</p>
                                <p>Senin - Sabtu, 08.00 - 13.00 WIB</p>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-6 py-4 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-sky-700">
                                Masuk ke Portal PPDB
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-6 py-4 text-sm font-bold uppercase tracking-wide text-slate-900 transition hover:bg-slate-50">
                                Saya Sudah Punya Akun
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-sky-700 px-4 py-5 text-center text-sm font-medium text-sky-50 sm:px-6 lg:px-8">
        Copyright Â© 2026 Raudhatul Athfal Fadhilah Pekanbaru. All rights reserved.
    </footer>
</body>
</html>
