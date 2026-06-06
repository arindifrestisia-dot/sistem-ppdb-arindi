<x-panitia-layout title="{{ $contentItem->exists ? 'Ubah Testimoni' : 'Tambah Testimoni' }}">
    <section class="mx-auto max-w-3xl rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="border-b border-slate-200 pb-5">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Testimoni Orang Tua</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $contentItem->exists ? 'Ubah Testimoni' : 'Tambah Testimoni Baru' }}</h2>
            <p class="mt-2 text-sm text-slate-500">Isi nama orang tua, isi testimoni, dan foto yang akan ditampilkan di halaman utama.</p>
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

        <form method="POST" action="{{ $contentItem->exists ? route('panitia.contents.update', $contentItem) : route('panitia.contents.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @if ($contentItem->exists)
                @method('PUT')
            @endif

            <input type="hidden" name="type" value="{{ \App\Models\SchoolContent::TYPE_TESTIMONIAL }}">

            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Nama Orang Tua</label>
                <input id="title" type="text" name="title" value="{{ old('title', $contentItem->title) }}" placeholder="Contoh: Bunda Alleryk" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">Isi Testimoni</label>
                <textarea id="content" name="content" rows="6" maxlength="2000" placeholder="Tuliskan pengalaman orang tua selama menyekolahkan anak di RA Fadhilah..." class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm leading-7 text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">{{ old('content', $contentItem->content) }}</textarea>
            </div>

            <div>
                <label for="images" class="mb-2 block text-sm font-semibold text-slate-700">Foto Orang Tua</label>
                <input id="images" type="file" name="images[]" accept="image/*" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
                <p class="mt-2 text-xs text-slate-500">{{ $contentItem->exists ? 'Kosongkan jika foto tidak ingin diganti.' : 'Upload satu foto orang tua, maksimal 4 MB.' }}</p>
            </div>

            @if ($contentItem->image_path)
                <div>
                    <p class="mb-3 text-sm font-semibold text-slate-700">Foto Saat Ini</p>
                    <img src="{{ asset('storage/' . $contentItem->image_path) }}" alt="{{ $contentItem->title }}" class="h-32 w-32 rounded-full border-4 border-slate-100 object-cover shadow-sm">
                </div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
                    <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $contentItem->sort_order ?? 0) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <p class="mt-2 text-xs text-slate-500">Angka lebih kecil ditampilkan lebih dahulu.</p>
                </div>

                <label class="flex items-center gap-3 self-end rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-slate-300" @checked(old('is_published', $contentItem->is_published))>
                    <span>Tampilkan di website</span>
                </label>
            </div>

            <div class="flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $contentItem->exists ? 'Simpan Perubahan' : 'Simpan Testimoni' }}</button>
                <a href="{{ route('panitia.contents.index', ['type' => \App\Models\SchoolContent::TYPE_TESTIMONIAL]) }}" class="rounded-2xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </section>
</x-panitia-layout>
