<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun PPDB</title>
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
<body class="min-h-screen bg-slate-100 px-4 py-8 text-slate-900 sm:px-6">
    <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-2xl items-start justify-center">
        <div class="w-full overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-[0_18px_45px_rgba(15,23,42,0.1)]">
            <div class="border-b border-slate-200 px-5 py-6 text-center sm:px-7">
                <h1 class="text-3xl font-extrabold uppercase text-blue-700 sm:text-4xl">Daftar Akun</h1>
                <p class="mt-3 text-lg font-bold text-slate-400 sm:text-xl">SPMB RA FADHILAH 2027</p>
            </div>

            <div class="px-5 py-6 sm:px-7 sm:py-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        Mohon periksa kembali data pendaftaran. Beberapa isian masih belum sesuai.
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="sr-only">Nama Lengkap Siswa</label>
                        <div class="flex items-center rounded-xl border border-slate-400 bg-white px-4 shadow-sm">
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Lengkap Siswa" class="h-11 w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-base">
                            <svg class="h-6 w-6 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-3.49 0-6.75 1.71-6.75 3.75V21h13.5v-3c0-2.04-3.26-3.75-6.75-3.75Z"/>
                            </svg>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="sr-only">Email Aktif</label>
                        <div class="flex items-center rounded-xl border border-slate-400 bg-white px-4 shadow-sm">
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Aktif" class="h-11 w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-base">
                            <svg class="h-6 w-6 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3.75 5.25A2.25 2.25 0 0 0 1.5 7.5v9A2.25 2.25 0 0 0 3.75 18.75h16.5A2.25 2.25 0 0 0 22.5 16.5v-9a2.25 2.25 0 0 0-2.25-2.25H3.75Zm0 1.5h16.5a.75.75 0 0 1 .49 1.32l-7.4 6.35a2 2 0 0 1-2.68 0l-7.4-6.35a.75.75 0 0 1 .49-1.32Z"/>
                            </svg>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <div class="flex items-center rounded-xl border border-slate-400 bg-white px-4 shadow-sm">
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required autocomplete="username" placeholder="Username Login" class="h-11 w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-base">
                            <svg class="h-6 w-6 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.25a9.75 9.75 0 1 0 9.75 9.75A9.76 9.76 0 0 0 12 2.25Zm0 3a3.75 3.75 0 1 1-3.75 3.75A3.75 3.75 0 0 1 12 5.25Zm0 14.25a7.44 7.44 0 0 1-5.63-2.58c.6-1.55 2.79-2.67 5.63-2.67s5.03 1.12 5.63 2.67A7.44 7.44 0 0 1 12 19.5Z"/>
                            </svg>
                        </div>
                        @error('username')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="flex items-center rounded-xl border border-slate-400 bg-white px-4 shadow-sm">
                            <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Password (Min. 8 Karakter)" class="h-11 w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-base">
                            <button type="button" data-toggle-password="password" class="text-slate-600" aria-label="Tampilkan password">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 5.25c5.25 0 9.19 4.55 10.34 6.08a1.2 1.2 0 0 1 0 1.34C21.19 14.2 17.25 18.75 12 18.75S2.81 14.2 1.66 12.67a1.2 1.2 0 0 1 0-1.34C2.81 9.8 6.75 5.25 12 5.25Zm0 2.25a4.5 4.5 0 1 0 4.5 4.5A4.5 4.5 0 0 0 12 7.5Zm0 2.25A2.25 2.25 0 1 1 9.75 12 2.25 2.25 0 0 1 12 9.75Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
                        <div class="flex items-center rounded-xl border border-slate-400 bg-white px-4 shadow-sm">
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Konfirmasi Password" class="h-11 w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-base">
                            <button type="button" data-toggle-password="password_confirmation" class="text-slate-600" aria-label="Tampilkan konfirmasi password">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 5.25c5.25 0 9.19 4.55 10.34 6.08a1.2 1.2 0 0 1 0 1.34C21.19 14.2 17.25 18.75 12 18.75S2.81 14.2 1.66 12.67a1.2 1.2 0 0 1 0-1.34C2.81 9.8 6.75 5.25 12 5.25Zm0 2.25a4.5 4.5 0 1 0 4.5 4.5A4.5 4.5 0 0 0 12 7.5Zm0 2.25A2.25 2.25 0 1 1 9.75 12 2.25 2.25 0 0 1 12 9.75Z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-blue-700 px-6 py-2.5 text-base font-bold uppercase tracking-wide text-white shadow-[0_10px_20px_rgba(29,78,216,0.2)] transition hover:bg-blue-800 sm:text-lg">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="mt-7 text-center text-sm text-slate-700">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Login di sini</a>
                </p>
            </div>

            <div class="border-t border-slate-200 px-5 py-6 text-center sm:px-7">
                <a href="{{ route('ppdb.info') }}" class="inline-flex items-center gap-3 text-sm font-semibold text-slate-500 transition hover:text-slate-700 sm:text-base">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.togglePassword);
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        });
    </script>
</body>
</html>
