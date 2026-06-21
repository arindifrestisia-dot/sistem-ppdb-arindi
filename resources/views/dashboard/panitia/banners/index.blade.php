<x-panitia-layout title="Banner Website">
    <section class="rounded-[2rem] bg-slate-950 p-6 text-white shadow-sm">
        <div class="flex items-start gap-4">
            <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-400/15 text-amber-300">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <circle cx="8.5" cy="10" r="1.5"></circle>
                    <path d="m4 17 5-4 3 2 3-3 5 5"></path>
                </svg>
            </span>
            <div>
                <h2 class="text-3xl font-extrabold">Banner Website</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">Kelola tiga gambar slider yang tampil di bagian paling atas website publik. Disarankan memakai gambar dengan ukuran dan rasio yang sama agar pergantian slide tetap rapi.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-5 py-4 text-sm font-semibold text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 px-5 py-4 text-sm text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        @php
            $defaults = [
                1 => 'image/banner-ppdb-2026-2027.png',
                2 => 'image/banner-profil-sekolah.png',
                3 => 'image/banner-selamat-datang-ppdb.png',
            ];
        @endphp

        <div class="mt-8 grid gap-6 xl:grid-cols-3">
            @foreach ([1, 2, 3] as $slot)
                @php $banner = $banners->get($slot); @endphp
                <article class="overflow-hidden rounded-[1.5rem] border border-slate-800 bg-slate-900">
                    <div class="aspect-[16/7] bg-slate-800">
                        <img src="{{ $banner ? asset('storage/' . $banner->image_path) : asset($defaults[$slot]) }}" alt="Banner slider {{ $slot }}" class="h-full w-full object-cover">
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-lg font-bold">Banner {{ $slot }}</h3>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $banner ? 'bg-emerald-400/15 text-emerald-300' : 'bg-slate-800 text-slate-400' }}">
                                {{ $banner ? 'Gambar unggahan' : 'Gambar bawaan' }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('panitia.banners.update', $slot) }}" enctype="multipart/form-data" class="mt-5 space-y-3">
                            @csrf
                            @method('PUT')
                            <label class="block text-sm font-semibold text-slate-300" for="banner-{{ $slot }}">Pilih banner baru</label>
                            <input id="banner-{{ $slot }}" name="image" type="file" accept="image/jpeg,image/png,image/webp" required class="block w-full cursor-pointer rounded-xl border border-slate-700 bg-slate-950 text-sm text-slate-300 file:mr-3 file:border-0 file:bg-blue-500 file:px-4 file:py-3 file:font-semibold file:text-white hover:file:bg-blue-400">
                            <button type="submit" class="w-full rounded-xl bg-blue-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-400">
                                {{ $banner ? 'Ganti Banner' : 'Upload Banner' }}
                            </button>
                        </form>

                        @if ($banner)
                            <form method="POST" action="{{ route('panitia.banners.destroy', $slot) }}" class="mt-3" onsubmit="return confirm('Hapus Banner {{ $slot }} dan gunakan gambar bawaan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-xl border border-rose-400/30 px-4 py-3 text-sm font-bold text-rose-300 transition hover:bg-rose-500 hover:text-white">Hapus Banner</button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <p class="mt-6 text-xs leading-5 text-slate-500">Format: JPG, PNG, atau WEBP. Ukuran maksimum 5 MB per gambar. Saat banner diganti, file lama otomatis dihapus.</p>
    </section>
</x-panitia-layout>
