<x-panitia-layout title="{{ $field->exists ? 'Edit Field Formulir' : 'Tambah Field Formulir' }}">
    <div class="mx-auto max-w-4xl">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $field->exists ? 'Edit Field' : 'Tambah Field Baru' }}</h1>
                <p class="mt-1 text-sm text-slate-500">Atur pertanyaan tambahan untuk formulir orang tua.</p>
            </div>
            <a href="{{ route('panitia.parent-form-fields.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Kembali</a>
        </div>

        <form method="POST" action="{{ $field->exists ? route('panitia.parent-form-fields.update', $field) : route('panitia.parent-form-fields.store') }}" class="mt-6 rounded-[2rem] bg-white p-6 shadow-sm md:p-8">
            @csrf
            @if ($field->exists)
                @method('PUT')
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                <label class="block md:col-span-2">
                    <span class="text-sm font-semibold text-slate-700">Nama Field <span class="text-rose-500">*</span></span>
                    <input type="text" name="label" value="{{ old('label', $field->label) }}" required placeholder="Contoh: Sekolah Asal Anak" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                    @error('label') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Bagian Formulir <span class="text-rose-500">*</span></span>
                    <select name="section" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        @foreach ($sectionOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('section', $field->section ?: 'child') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Tipe Isian <span class="text-rose-500">*</span></span>
                    <select id="fieldType" name="type" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        @foreach ($typeOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $field->type ?: 'text') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Placeholder</span>
                    <input type="text" name="placeholder" value="{{ old('placeholder', $field->placeholder) }}" placeholder="Petunjuk singkat di dalam kolom" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Urutan</span>
                    <input type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $field->sort_order ?? 0) }}" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </label>

                <label class="block md:col-span-2">
                    <span class="text-sm font-semibold text-slate-700">Keterangan</span>
                    <textarea name="help_text" rows="3" placeholder="Penjelasan tambahan untuk orang tua" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('help_text', $field->help_text) }}</textarea>
                </label>

                <label id="optionsWrapper" class="block md:col-span-2">
                    <span class="text-sm font-semibold text-slate-700">Daftar Pilihan</span>
                    <textarea name="options_text" rows="5" placeholder="Satu pilihan per baris" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('options_text', collect($field->options)->implode("\n")) }}</textarea>
                    <span class="mt-1 block text-xs text-slate-500">Wajib diisi untuk tipe Pilihan.</span>
                    @error('options_text') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="mt-6 flex flex-wrap gap-5 rounded-2xl bg-slate-50 p-5">
                <label class="flex items-center gap-3 font-semibold text-slate-700">
                    <input type="checkbox" name="is_required" value="1" @checked(old('is_required', $field->exists ? $field->is_required : false)) class="h-5 w-5 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    Wajib diisi
                </label>
                <label class="flex items-center gap-3 font-semibold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $field->exists ? $field->is_active : true)) class="h-5 w-5 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    Aktif dan tampil di formulir
                </label>
            </div>

            <div class="mt-7 flex justify-end">
                <button type="submit" class="rounded-2xl bg-slate-900 px-6 py-3 font-semibold text-white hover:bg-slate-800">Simpan Field</button>
            </div>
        </form>
    </div>

    <script>
        const fieldType = document.getElementById('fieldType');
        const optionsWrapper = document.getElementById('optionsWrapper');

        function toggleOptions() {
            optionsWrapper.classList.toggle('hidden', fieldType.value !== 'select');
        }

        fieldType.addEventListener('change', toggleOptions);
        toggleOptions();
    </script>
</x-panitia-layout>
