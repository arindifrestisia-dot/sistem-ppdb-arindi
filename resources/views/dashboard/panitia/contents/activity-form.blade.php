<x-panitia-layout title="{{ $contentItem->exists ? 'Ubah Kegiatan' : 'Tambah Kegiatan' }}">
    <section class="mx-auto max-w-3xl rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="border-b border-slate-200 pb-5">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Informasi Sekolah</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $contentItem->exists ? 'Ubah Data Kegiatan' : 'Tambah Kegiatan Baru' }}</h2>
            <p class="mt-2 text-sm text-slate-500">Untuk kegiatan, panitia cukup mengisi nama kegiatan dan mengunggah satu foto.</p>
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

            <input type="hidden" name="type" value="kegiatan">

            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Nama Kegiatan</label>
                <input id="title" type="text" name="title" value="{{ old('title', $contentItem->title) }}" placeholder="Contoh: Practical Life" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="images" class="mb-2 block text-sm font-semibold text-slate-700">Foto Kegiatan</label>
                <input id="images" type="file" name="images[]" accept="image/*" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
                <p class="mt-2 text-xs text-slate-500">Upload satu foto untuk setiap kegiatan.</p>
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
                    <div class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50">
                        <img src="{{ asset('storage/' . $existingImages->first()->image_path) }}" alt="Foto kegiatan" class="h-64 w-full object-cover">
                        <div class="p-4">
                            <p class="text-sm text-slate-500">Upload foto baru untuk mengganti foto kegiatan ini.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $contentItem->exists ? 'Simpan Perubahan' : 'Simpan Kegiatan' }}</button>
                <a href="{{ route('panitia.contents.index', ['type' => 'kegiatan']) }}" class="rounded-2xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </section>
</x-panitia-layout>
