<x-panitia-layout title="{{ $contentItem->exists ? 'Edit Tenaga Pendidik' : 'Tambah Tenaga Pendidik' }}">
    <section class="mx-auto max-w-3xl rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="border-b border-slate-200 pb-5">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Tenaga Pendidik</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $contentItem->exists ? 'Ubah Data Guru' : 'Tambah Data Guru' }}</h2>
            <p class="mt-2 text-sm text-slate-500">Isi foto guru, nama guru, dan jabatan guru untuk ditampilkan di website profil sekolah.</p>
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

            <input type="hidden" name="type" value="{{ \App\Models\SchoolContent::TYPE_TEACHER }}">

            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Nama Guru</label>
                <input id="title" type="text" name="title" value="{{ old('title', $contentItem->title) }}" placeholder="Contoh: Ibunda Sri Dewi, S.E." class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="excerpt" class="mb-2 block text-sm font-semibold text-slate-700">Jabatan Guru</label>
                <input id="excerpt" type="text" name="excerpt" value="{{ old('excerpt', $contentItem->excerpt) }}" placeholder="Contoh: Kepala RA / Guru Kelas A" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
            </div>

            <div>
                <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">NIP Guru</label>
                <input id="content" type="text" name="content" value="{{ old('content', $contentItem->content) }}" placeholder="Contoh: 19850101 201001 2 001" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                <p class="mt-2 text-xs text-slate-500">Boleh dikosongkan jika guru belum memiliki NIP.</p>
            </div>

            <div>
                <label for="images" class="mb-2 block text-sm font-semibold text-slate-700">Foto Guru</label>
                <input id="images" type="file" name="images[]" accept="image/*" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800">
                <p class="mt-2 text-xs text-slate-500">{{ $contentItem->exists ? 'Kosongkan jika foto guru tidak ingin diganti.' : 'Upload satu foto guru.' }}</p>
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
                    <div class="max-w-xs overflow-hidden rounded-[1.5rem] border border-slate-200 bg-slate-50">
                        <img src="{{ asset('storage/' . $existingImages->first()->image_path) }}" alt="Foto guru" class="h-64 w-full object-cover">
                        @if ($existingImages->first()->id)
                            <label class="flex items-center gap-3 p-4 text-sm text-slate-700">
                                <input type="checkbox" name="remove_images[]" value="{{ $existingImages->first()->id }}">
                                <span>Hapus foto ini</span>
                            </label>
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan Data Guru</button>
                <a href="{{ route('panitia.contents.index', ['type' => \App\Models\SchoolContent::TYPE_TEACHER]) }}" class="rounded-2xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </section>
</x-panitia-layout>
