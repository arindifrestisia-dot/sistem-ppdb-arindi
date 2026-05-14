<x-panitia-layout title="{{ $contentItem->exists ? 'Ubah Fasilitas' : 'Tambah Fasilitas' }}">
    <section class="mx-auto max-w-3xl rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="border-b border-slate-200 pb-5">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Informasi Sekolah</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $contentItem->exists ? 'Ubah Data Fasilitas' : 'Tambah Fasilitas Baru' }}</h2>
            <p class="mt-2 text-sm text-slate-500">Untuk fasilitas, panitia cukup mengisi nama fasilitas dan mengunggah satu foto utama.</p>
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

            <input type="hidden" name="type" value="fasilitas">

            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Nama Fasilitas</label>
                <input id="title" type="text" name="title" value="{{ old('title', $contentItem->title) }}" placeholder="Contoh: Perpustakaan Anak" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="images" class="mb-2 block text-sm font-semibold text-slate-700">Foto Fasilitas</label>
                    <input id="images" type="file" name="images[]" accept="image/*" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
                    <p class="mt-2 text-xs text-slate-500">Upload satu foto untuk setiap fasilitas.</p>
                </div>

                <div>
                    <label for="published_at" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Publikasi</label>
                    <input id="published_at" type="date" name="published_at" value="{{ old('published_at', optional($contentItem->published_at)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                </div>
            </div>

            <div>
                <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
                <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $contentItem->sort_order ?? 0) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            </div>

            @php
                $existingImages = $contentItem->relationLoaded('images') ? $contentItem->images : collect();

                if ($existingImages->isEmpty() && $contentItem->image_path) {
                    $existingImages = collect([
                        (object) [
                            'id' => 0,
                            'image_path' => $contentItem->image_path,
                            'sort_order' => 0,
                        ],
                    ]);
                }
            @endphp

            @if ($existingImages->isNotEmpty())
                <div>
                    <p class="mb-3 text-sm font-semibold text-slate-700">Foto Saat Ini</p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($existingImages as $image)
                            <label class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Foto fasilitas" class="h-52 w-full object-cover">
                                <div class="space-y-2 p-4">
                                    @if ($image->id)
                                        <div class="flex items-center gap-3 text-sm text-slate-700">
                                            <input type="checkbox" name="remove_images[]" value="{{ $image->id }}">
                                            <span>Hapus foto ini</span>
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-500">Foto lama akan tetap dipakai sampai diganti oleh upload baru.</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-700">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $contentItem->is_published ?? true))>
                Tampilkan di website publik
            </label>

            <div class="flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $contentItem->exists ? 'Simpan Perubahan' : 'Simpan Fasilitas' }}</button>
                <a href="{{ route('panitia.contents.index', ['type' => 'fasilitas']) }}" class="rounded-2xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </section>
</x-panitia-layout>
