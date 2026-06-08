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
<body class="min-h-screen bg-slate-100 px-4 py-6 text-slate-900 sm:px-6">
    <div class="mx-auto flex min-h-[calc(100vh-3rem)] max-w-sm items-center justify-center">
        <div class="w-full rounded-lg border border-slate-200 bg-white px-6 py-8 shadow-[0_10px_24px_rgba(15,23,42,0.12)] sm:px-8">
            <div class="text-center">
                <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="mx-auto h-14 w-14 object-contain">
                <h1 class="mt-4 text-2xl font-bold text-slate-950">Registrasi</h1>
                <p class="mt-3 text-sm text-slate-600">Sistem PPDB RA Fadhilah</p>
            </div>

            <div class="mt-7">
                @if ($errors->any())
                    <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-xs leading-5 text-red-700">
                        Mohon periksa kembali data pendaftaran. Beberapa isian masih belum sesuai.
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block text-xs font-medium text-slate-800">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap anak" class="h-10 w-full rounded border border-indigo-100 px-4 text-xs text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-xs font-medium text-slate-800">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Contoh: nama@gmail.com" class="h-10 w-full rounded border border-indigo-100 px-4 text-xs text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                        <p class="mt-1.5 text-[11px] text-slate-400">Gunakan email aktif untuk menerima informasi</p>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="mb-2 block text-xs font-medium text-slate-800">Username</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required autocomplete="username" placeholder="Contoh: nama123" class="h-10 w-full rounded border border-indigo-100 px-4 text-xs text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                        <p class="mt-1.5 text-[11px] text-slate-400">Digunakan untuk login ke sistem PPDB</p>
                        @error('username')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-xs font-medium text-slate-800">Password</label>
                        <div class="flex items-center rounded border border-indigo-100 px-4 transition focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100">
                            <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="h-10 w-full border-none bg-transparent p-0 text-xs text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0">
                            <button type="button" data-toggle-password="password" class="ml-3 text-slate-500 transition hover:text-indigo-600" aria-label="Tampilkan password">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 5.25c5.25 0 9.19 4.55 10.34 6.08a1.2 1.2 0 0 1 0 1.34C21.19 14.2 17.25 18.75 12 18.75S2.81 14.2 1.66 12.67a1.2 1.2 0 0 1 0-1.34C2.81 9.8 6.75 5.25 12 5.25Zm0 2.25a4.5 4.5 0 1 0 4.5 4.5A4.5 4.5 0 0 0 12 7.5Zm0 2.25A2.25 2.25 0 1 1 9.75 12 2.25 2.25 0 0 1 12 9.75Z"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1.5 text-[11px] text-slate-400">Gunakan kombinasi huruf dan angka</p>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-xs font-medium text-slate-800">Konfirmasi Password</label>
                        <div class="flex items-center rounded border border-indigo-100 px-4 transition focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100">
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Ulangi password" class="h-10 w-full border-none bg-transparent p-0 text-xs text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0">
                            <button type="button" data-toggle-password="password_confirmation" class="ml-3 text-slate-500 transition hover:text-indigo-600" aria-label="Tampilkan konfirmasi password">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 5.25c5.25 0 9.19 4.55 10.34 6.08a1.2 1.2 0 0 1 0 1.34C21.19 14.2 17.25 18.75 12 18.75S2.81 14.2 1.66 12.67a1.2 1.2 0 0 1 0-1.34C2.81 9.8 6.75 5.25 12 5.25Zm0 2.25a4.5 4.5 0 1 0 4.5 4.5A4.5 4.5 0 0 0 12 7.5Zm0 2.25A2.25 2.25 0 1 1 9.75 12 2.25 2.25 0 0 1 12 9.75Z"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1.5 text-[11px] text-slate-400">Harus sama dengan password di atas</p>
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="mt-5 inline-flex h-11 w-full items-center justify-center rounded bg-[#0d8bc8] px-6 text-xs font-semibold text-white shadow-[0_10px_18px_rgba(13,139,200,0.26)] transition hover:bg-[#087db6]">
                        Registrasi
                    </button>
                </form>

                <p class="mt-5 text-center text-xs text-slate-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-slate-900 underline-offset-2 hover:underline">Masuk</a>
                </p>
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
