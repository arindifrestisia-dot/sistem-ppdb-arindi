<x-panitia-layout title="{{ $contentItem->exists ? 'Edit Logo RA Fadhilah' : 'Tambah Logo RA Fadhilah' }}">
    <section class="mx-auto max-w-3xl rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="border-b border-slate-200 pb-5">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Profil Sekolah</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $contentItem->exists ? 'Ubah Logo RA Fadhilah' : 'Tambah Logo RA Fadhilah' }}</h2>
            <p class="mt-2 text-sm text-slate-500">Unggah satu gambar logo. Logo ini langsung dipakai pada website publik.</p>
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

            <input type="hidden" name="type" value="{{ \App\Models\SchoolContent::TYPE_PROFILE_LOGO }}">

            @if ($contentItem->image_path)
                <div>
                    <p class="mb-3 text-sm font-semibold text-slate-700">Logo Saat Ini</p>
                    <div class="flex h-36 w-36 items-center justify-center rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <img src="{{ asset('storage/' . $contentItem->image_path) }}" alt="Logo RA Fadhilah" class="h-full w-full object-contain">
                    </div>
                </div>
            @endif

            <div>
                <label for="images" class="mb-2 block text-sm font-semibold text-slate-700">Upload Logo RA Fadhilah</label>
                <input id="images" type="file" name="images[]" accept="image/*" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800" {{ $contentItem->exists ? '' : 'required' }}>
                <p class="mt-2 text-xs text-slate-500">Gunakan format JPG, PNG, atau WebP. Maksimal 4 MB.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3 border-t border-slate-200 pt-6">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan Logo</button>
                <a href="{{ route('panitia.contents.index', ['type' => \App\Models\SchoolContent::TYPE_PROFILE_LOGO]) }}" class="rounded-2xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </section>
</x-panitia-layout>
