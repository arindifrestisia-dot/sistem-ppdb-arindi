<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Kepsek' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="flex min-h-screen flex-col md:flex-row">
        <aside class="w-full bg-blue-950 text-white md:min-h-screen md:w-72">
            <div class="border-b border-blue-900 px-6 py-6">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">Dashboard Kepsek</p>
                <h1 class="mt-2 text-2xl font-extrabold">PPDB RA Fadhilah</h1>
                <p class="mt-2 text-sm text-slate-300">Pantau ringkasan pendaftaran dan perkembangan informasi sekolah.</p>
            </div>

            <div class="border-b border-blue-900 px-6 py-5">
                <p class="text-lg font-bold">{{ auth()->user()->name }}</p>
                <p class="text-sm text-slate-300">{{ auth()->user()->email }}</p>
                <span class="mt-3 inline-flex rounded-full bg-amber-300 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-blue-950">Kepsek</span>
            </div>

            <nav class="space-y-2 px-4 py-6 text-sm font-semibold">
                <a href="{{ route('kepsek.dashboard') }}" class="block rounded-2xl px-4 py-3 {{ request()->routeIs('kepsek.dashboard') ? 'bg-amber-300 text-blue-950' : 'text-slate-200 hover:bg-blue-900' }}">Beranda</a>
                <a href="{{ route('profile.dashboard') }}" class="block rounded-2xl px-4 py-3 text-slate-200 hover:bg-blue-900">Lihat Website Publik</a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex items-center justify-between gap-4 bg-white px-5 py-4 shadow-sm md:px-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-700">{{ $title ?? 'Dashboard Kepsek' }}</p>
                    <p class="mt-1 text-sm text-slate-500">Portal monitoring kepala sekolah.</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full bg-rose-50 px-5 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">Keluar</button>
                </form>
            </header>

            <main class="flex-1 px-5 py-6 md:px-8">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
