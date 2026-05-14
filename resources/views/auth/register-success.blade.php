<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil</title>
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
<body class="flex min-h-screen items-center justify-center bg-sky-500 px-4 py-10">
    <div class="w-full max-w-3xl rounded-[2rem] bg-white px-6 py-10 text-center shadow-[0_25px_60px_rgba(15,23,42,0.18)] sm:px-10 sm:py-14">
        <div class="mx-auto flex h-36 w-36 items-center justify-center rounded-full border-4 border-slate-900 text-slate-900">
            <svg class="h-20 w-20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/>
            </svg>
        </div>

        <h1 class="mt-10 text-4xl font-extrabold text-slate-500 sm:text-5xl">Berhasil!</h1>
        <p class="mt-4 text-2xl font-medium text-slate-500 sm:text-4xl">Akun berhasil dibuat! Silahkan login.</p>

        <a href="{{ route('login') }}" class="mt-10 inline-flex items-center justify-center rounded-xl bg-blue-700 px-8 py-5 text-2xl font-semibold text-white shadow-[0_16px_35px_rgba(29,78,216,0.25)] transition hover:bg-blue-800">
            Lanjut ke Login
        </a>
    </div>
</body>
</html>
