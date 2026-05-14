<header class="flex flex-wrap items-center justify-between gap-4 bg-white px-5 py-4 shadow md:px-8">
    <button type="button" class="text-xl text-slate-400">&#8801;</button>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-sm font-semibold text-red-500">Keluar</button>
    </form>
</header>
