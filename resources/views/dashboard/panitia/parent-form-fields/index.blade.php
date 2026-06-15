<x-panitia-layout title="Formulir Orang Tua">
    <section class="space-y-6">
        <div class="flex flex-col gap-4 rounded-[2rem] bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">Formulir</p>
                <h1 class="mt-2 text-2xl font-bold text-slate-900">Formulir Orang Tua</h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">
                    Kelola pertanyaan tambahan yang akan muncul pada formulir pendaftaran orang tua.
                    Field sistem tetap dilindungi karena digunakan pada proses seleksi dan dokumen pendaftaran.
                </p>
            </div>
            <a href="{{ route('panitia.parent-form-fields.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                + Tambah Field
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Field Tambahan</h2>
                    <p class="mt-1 text-sm text-slate-500">Field aktif langsung tampil pada formulir orang tua.</p>
                </div>
                <span class="rounded-full bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-700">{{ $fields->count() }} field</span>
            </div>

            <div class="mt-5 overflow-x-auto rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Urutan</th>
                            <th class="px-5 py-4 font-semibold">Nama Field</th>
                            <th class="px-5 py-4 font-semibold">Bagian</th>
                            <th class="px-5 py-4 font-semibold">Tipe</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($fields as $field)
                            <tr>
                                <td class="px-5 py-4 text-slate-500">{{ $field->sort_order }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ $field->label }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $field->field_key }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ \App\Models\ParentFormField::SECTIONS[$field->section] }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ \App\Models\ParentFormField::TYPES[$field->type] }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $field->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $field->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        @if ($field->is_required)
                                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">Wajib</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('panitia.parent-form-fields.edit', $field) }}" class="rounded-xl border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                                        <form method="POST" action="{{ route('panitia.parent-form-fields.destroy', $field) }}" onsubmit="return confirm('Hapus field ini? Jawaban lama tetap tersimpan, tetapi field tidak akan tampil lagi.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-xl border border-rose-200 px-4 py-2 font-semibold text-rose-600 hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada field tambahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Field Sistem</h2>
            <p class="mt-1 text-sm text-slate-500">Daftar biodata bawaan yang selalu digunakan sistem dan tidak dapat dihapus.</p>
            <div class="mt-5 grid gap-4 lg:grid-cols-3">
                @foreach ($systemFields as $section => $items)
                    <article class="rounded-2xl border border-slate-200 p-5">
                        <h3 class="font-bold text-slate-800">{{ $section }}</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($items as $item)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $item }}</span>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </section>
</x-panitia-layout>
