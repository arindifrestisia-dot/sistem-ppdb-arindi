<x-panitia-layout title="Upload Foto Galeri">
    <section class="mx-auto max-w-3xl rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="border-b border-slate-200 pb-5">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Informasi Sekolah</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">Upload Foto Galeri</h2>
            <p class="mt-2 text-sm text-slate-500">Panitia cukup menambahkan foto galeri. Setiap foto akan tersimpan sebagai item galeri terpisah dan bisa dihapus dari halaman galeri.</p>
        </div>

        @if ($errors->any())
            <div class="mt-6 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                <p class="font-semibold">Periksa kembali form berikut:</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('panitia.contents.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            <input type="hidden" name="type" value="galeri">

            <div>
                <label for="images" class="mb-2 block text-sm font-semibold text-slate-700">Foto Galeri</label>
                <input id="images" type="file" name="images[]" accept="image/*" multiple required class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
                <p class="mt-2 text-xs text-slate-500">Bisa upload beberapa foto sekaligus. Maksimal 4 MB per foto.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="published_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Upload</label>
                    <input id="published_at" type="date" name="published_at" value="{{ old('published_at', now()->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">Urutan Awal</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                </div>
            </div>

            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-700">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true))>
                Tampilkan di website publik
            </label>

            <div class="flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan Foto</button>
                <a href="{{ route('panitia.contents.index', ['type' => 'galeri']) }}" class="rounded-2xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </section>
</x-panitia-layout>
