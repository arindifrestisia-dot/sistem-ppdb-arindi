<x-panitia-layout title="Kelola Konten Sekolah">
    @php
        $isTeacherType = $type === \App\Models\SchoolContent::TYPE_TEACHER;
    @endphp

    <section class="rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Informasi Sekolah</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $typeOptions[$type] ?? 'Konten Sekolah' }}</h2>
                <p class="mt-2 text-sm text-slate-500">Panitia dapat menambah, mengubah, dan menghapus konten yang akan ditampilkan pada website publik.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach ($typeOptions as $typeKey => $label)
                    <a href="{{ route('panitia.contents.index', ['type' => $typeKey]) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ $type === $typeKey ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('panitia.contents.create', ['type' => $type]) }}" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-semibold text-slate-950">{{ $isTeacherType ? 'Tambah Guru' : 'Tambah Konten' }}</a>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Foto</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">{{ $isTeacherType ? 'Nama Guru' : 'Judul' }}</th>
                        @unless ($isTeacherType)
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Publikasi</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Jumlah Foto</th>
                        @endunless
                        @unless ($isTeacherType)
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Urutan</th>
                        @endunless
                        @unless ($isTeacherType)
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Status</th>
                        @endunless
                        <th class="px-4 py-3 text-left font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($contents as $content)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="h-16 w-16 overflow-hidden rounded-2xl bg-slate-100">
                                    <img
                                        src="{{ $content->image_path ? asset('storage/' . $content->image_path) : asset($isTeacherType ? 'image/fotoguru.png' : 'image/berita1.png') }}"
                                        alt="{{ $content->title }}"
                                        class="h-full w-full object-cover"
                                    >
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-800">{{ $content->title }}</p>
                                <p class="text-xs text-slate-500">{{ $isTeacherType ? $content->excerpt : \Illuminate\Support\Str::limit($content->excerpt, 80) }}</p>
                                @if ($isTeacherType)
                                    <p class="mt-1 text-xs text-slate-400">NIP: {{ $content->content ?: '-' }}</p>
                                @endif
                            </td>
                            @unless ($isTeacherType)
                                <td class="px-4 py-3 text-slate-600">{{ optional($content->published_at)->format('d-m-Y') ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ max($content->images->count(), $content->image_path ? 1 : 0) }}</td>
                            @endunless
                            @unless ($isTeacherType)
                                <td class="px-4 py-3 text-slate-600">{{ $content->sort_order }}</td>
                            @endunless
                            @unless ($isTeacherType)
                                <td class="px-4 py-3 text-slate-600">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $content->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $content->is_published ? 'Tayang' : 'Draft' }}
                                    </span>
                                </td>
                            @endunless
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('panitia.contents.edit', $content) }}" class="font-semibold text-sky-700">Edit</a>
                                    <form method="POST" action="{{ route('panitia.contents.destroy', $content) }}" onsubmit="return confirm('Hapus konten ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-rose-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isTeacherType ? 3 : 7 }}" class="px-4 py-5 text-center text-slate-500">{{ $isTeacherType ? 'Belum ada data guru.' : 'Belum ada konten untuk kategori ini.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $contents->links() }}
        </div>
    </section>
</x-panitia-layout>
