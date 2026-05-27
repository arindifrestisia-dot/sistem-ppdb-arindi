<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login PPDB</title>
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
                <h1 class="text-3xl font-extrabold uppercase text-blue-700 sm:text-4xl">SPMB</h1>
                <p class="mt-2 text-base font-medium text-slate-400 sm:text-lg">(Sistem Penerimaan Murid Baru)</p>
                <p class="mt-3 text-xl font-extrabold text-slate-500 sm:text-2xl">RAUDHATUL ATHFAL FADHILAH</p>
            </div>

            <div class="px-5 py-6 sm:px-7 sm:py-7">
                <p class="text-center text-base font-semibold text-slate-400 sm:text-lg">Silahkan masuk untuk melanjutkan</p>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        Username atau password belum sesuai. Silakan coba lagi.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <div class="flex items-center rounded-xl border border-slate-400 bg-white px-4 shadow-sm">
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Username" class="h-11 w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-base">
                        </div>
                        @error('username')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="flex items-center rounded-xl border border-slate-400 bg-white px-4 shadow-sm">
                            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Password" class="h-11 w-full border-none bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0 sm:text-base">
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

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <label for="remember_me" class="inline-flex items-center gap-3 text-base font-semibold text-slate-600 sm:text-lg">
                            <input id="remember_me" type="checkbox" name="remember" class="h-5 w-5 rounded border-slate-400 text-blue-700 focus:ring-blue-600">
                            Ingat Saya
                        </label>

                        <button type="submit" class="inline-flex min-w-[128px] items-center justify-center rounded-xl bg-blue-700 px-5 py-2.5 text-base font-semibold text-white shadow-[0_10px_20px_rgba(29,78,216,0.2)] transition hover:bg-blue-800 sm:text-lg">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-7 text-center">
                    <p class="text-sm text-slate-500 sm:text-base">Belum punya akun pendaftaran ?</p>
                    <a href="{{ route('register') }}" class="mt-4 inline-flex items-center justify-center gap-3 rounded-full border-2 border-slate-300 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-slate-500 transition hover:bg-slate-50 sm:text-base">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M15 8.25a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm-4.5 6c3.14 0 6.75 1.54 6.75 4.5v.75H3.75v-.75c0-2.96 3.61-4.5 6.75-4.5Zm9-7.5a.75.75 0 0 1 .75.75V9h1.5a.75.75 0 0 1 0 1.5h-1.5V12a.75.75 0 0 1-1.5 0v-1.5h-1.5a.75.75 0 0 1 0-1.5h1.5V7.5a.75.75 0 0 1 .75-.75Z"/>
                        </svg>
                        Buat Akun Sekarang
                    </a>
                </div>
            </div>

            <div class="px-5 pb-6 text-center sm:px-7">
                <a href="{{ route('ppdb.info') }}" class="inline-flex items-center gap-3 text-sm font-semibold text-blue-500 transition hover:text-blue-700 sm:text-base">
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
