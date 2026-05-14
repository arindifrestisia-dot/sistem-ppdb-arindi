<x-panitia-layout title="Galeri Sekolah">
    <section class="rounded-[2rem] bg-slate-950 p-6 text-white shadow-sm">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/20 text-sky-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <circle cx="9" cy="10" r="1.5"></circle>
                            <path d="m20 16-4.5-4.5L8 19"></path>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-3xl font-extrabold">Galeri Sekolah</h2>
                        <p class="mt-2 text-sm text-slate-400">Kelola foto dan dokumentasi kegiatan sekolah.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('panitia.contents.create', ['type' => $type]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-400">
                <span>+</span>
                <span>Upload Foto</span>
            </a>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($contents as $content)
                <article class="overflow-hidden rounded-[1.5rem] border border-slate-800 bg-slate-900/80">
                    <div class="relative h-56 bg-slate-800">
                        <img
                            src="{{ $content->image_path ? asset('storage/' . $content->image_path) : asset('image/berita1.png') }}"
                            alt="Foto galeri"
                            class="h-full w-full object-cover"
                        >
                        <form method="POST" action="{{ route('panitia.contents.destroy', $content) }}" class="absolute right-3 top-3" onsubmit="return confirm('Hapus foto galeri ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex rounded-full bg-slate-950/75 px-3 py-1.5 text-xs font-semibold text-rose-200 backdrop-blur transition hover:bg-rose-500 hover:text-white">Hapus</button>
                        </form>
                    </div>
                    <div class="p-4">
                        <p class="font-semibold text-white">Foto Galeri</p>
                        <p class="mt-1 text-sm text-slate-400">{{ optional($content->published_at)->translatedFormat('d M Y') ?? $content->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.5rem] border border-dashed border-slate-700 bg-slate-900/60 px-6 py-14 text-center text-slate-400 md:col-span-2 xl:col-span-4">
                    Belum ada foto galeri. Gunakan tombol `Upload Foto` untuk menambahkan dokumentasi sekolah.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $contents->links() }}
        </div>
    </section>
</x-panitia-layout>
