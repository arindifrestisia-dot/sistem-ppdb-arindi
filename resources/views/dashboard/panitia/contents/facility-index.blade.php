@php
    $isKepsek = auth()->user()?->isKepsek();
    $layoutComponent = $isKepsek ? 'kepsek-layout' : 'panitia-layout';
@endphp

<x-dynamic-component :component="$layoutComponent" title="Fasilitas Sekolah">
    <section class="rounded-[2rem] bg-slate-950 p-6 text-white shadow-sm">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 20V9.5L12 4l8 5.5V20"></path>
                            <path d="M9 20v-5h6v5"></path>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-3xl font-extrabold">Fasilitas Sekolah</h2>
                        <p class="mt-2 text-sm text-slate-400">Kelola foto dan nama fasilitas yang ditampilkan pada halaman publik.</p>
                    </div>
                </div>
            </div>

            @unless ($isKepsek)
                <a href="{{ route('panitia.contents.create', ['type' => $type]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">
                    <span>+</span>
                    <span>Tambah Fasilitas</span>
                </a>
            @endunless
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($contents as $content)
                <article class="overflow-hidden rounded-[1.5rem] border border-slate-800 bg-slate-900/80">
                    <div class="relative h-56 bg-slate-800">
                        <img
                            src="{{ $content->image_path ? asset('storage/' . $content->image_path) : asset('image/berita1.png') }}"
                            alt="{{ $content->title }}"
                            class="h-full w-full object-cover"
                        >
                        @unless ($isKepsek)
                            <form method="POST" action="{{ route('panitia.contents.destroy', $content) }}" class="absolute right-3 top-3" onsubmit="return confirm('Hapus fasilitas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex rounded-full bg-slate-950/75 px-3 py-1.5 text-xs font-semibold text-rose-200 backdrop-blur transition hover:bg-rose-500 hover:text-white">Hapus</button>
                            </form>
                        @endunless
                    </div>
                    <div class="p-4">
                        <p class="text-lg font-bold text-white">{{ $content->title }}</p>
                        <p class="mt-1 text-sm text-slate-400">{{ optional($content->published_at)->translatedFormat('d M Y') ?? $content->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.5rem] border border-dashed border-slate-700 bg-slate-900/60 px-6 py-14 text-center text-slate-400 md:col-span-2 xl:col-span-4">
                    Belum ada data fasilitas.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $contents->links() }}
        </div>
    </section>
</x-dynamic-component>
