<x-panitia-layout title="Testimoni Orang Tua">
    <section class="rounded-[2rem] bg-slate-950 p-6 text-white shadow-sm">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-3xl font-extrabold">Testimoni Orang Tua</h2>
                <p class="mt-2 text-sm text-slate-400">Kelola testimoni orang tua yang tampil pada halaman utama website.</p>
            </div>

            <a href="{{ route('panitia.contents.create', ['type' => $type]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-amber-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-amber-300">
                <span>+</span>
                <span>Tambah Testimoni</span>
            </a>
        </div>

        <div class="mt-8 space-y-4">
            @forelse ($contents as $content)
                <article class="flex flex-col gap-5 rounded-[1.5rem] border border-slate-800 bg-slate-900/80 p-5 md:flex-row md:items-center">
                    <img
                        src="{{ $content->image_path ? asset('storage/' . $content->image_path) : asset('image/contoh.fotobunda.png') }}"
                        alt="{{ $content->title }}"
                        class="h-20 w-20 shrink-0 rounded-full border-2 border-amber-300 object-cover"
                    >

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-lg font-bold text-white">{{ $content->title }}</h3>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $content->is_published ? 'bg-emerald-400/15 text-emerald-300' : 'bg-slate-700 text-slate-300' }}">
                                {{ $content->is_published ? 'Ditampilkan' : 'Disembunyikan' }}
                            </span>
                            <span class="text-xs text-slate-500">Urutan: {{ $content->sort_order }}</span>
                        </div>
                        <p class="mt-2 text-sm leading-7 text-slate-300">{{ \Illuminate\Support\Str::limit($content->content, 180) }}</p>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('panitia.contents.edit', $content) }}" class="rounded-full bg-sky-500/15 px-4 py-2 text-xs font-semibold text-sky-200 transition hover:bg-sky-500 hover:text-white">Edit</a>
                        <form method="POST" action="{{ route('panitia.contents.destroy', $content) }}" onsubmit="return confirm('Hapus testimoni ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-full bg-rose-500/15 px-4 py-2 text-xs font-semibold text-rose-200 transition hover:bg-rose-500 hover:text-white">Hapus</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.5rem] border border-dashed border-slate-700 bg-slate-900/60 px-6 py-14 text-center text-slate-400">
                    Belum ada testimoni. Gunakan tombol `Tambah Testimoni` untuk mengisi testimoni orang tua.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $contents->links() }}
        </div>
    </section>
</x-panitia-layout>
