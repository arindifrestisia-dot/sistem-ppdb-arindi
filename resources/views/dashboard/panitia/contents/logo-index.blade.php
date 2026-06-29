<x-panitia-layout title="Logo RA Fadhilah">
    <section class="rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Profil Sekolah</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-900">Logo RA Fadhilah</h2>
                <p class="mt-2 text-sm text-slate-500">Logo ini akan digunakan pada header dan footer website publik.</p>
            </div>

            @if (! $contentItem)
                <a href="{{ route('panitia.contents.create', ['type' => \App\Models\SchoolContent::TYPE_PROFILE_LOGO]) }}" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-semibold text-slate-950">Tambah Logo</a>
            @endif
        </div>

        @if (session('status'))
            <div class="mt-5 rounded-2xl bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 overflow-x-auto rounded-3xl border border-slate-200">
            <table class="min-w-[720px] divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="w-20 px-5 py-4 text-left font-semibold text-slate-600">No</th>
                        <th class="px-5 py-4 text-left font-semibold text-slate-600">Logo</th>
                        <th class="px-5 py-4 text-left font-semibold text-slate-600">Nama Sekolah</th>
                        <th class="w-40 px-5 py-4 text-left font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @if ($contentItem)
                        <tr>
                            <td class="px-5 py-5 text-slate-600">1</td>
                            <td class="px-5 py-5">
                                <div class="flex h-28 w-28 items-center justify-center rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                                    <img src="{{ $contentItem->image_path ? asset('storage/' . $contentItem->image_path) : asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="h-full w-full object-contain">
                                </div>
                            </td>
                            <td class="px-5 py-5 font-semibold text-slate-700">RA Fadhilah</td>
                            <td class="px-5 py-5">
                                <a href="{{ route('panitia.contents.edit', $contentItem) }}" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm transition hover:bg-blue-700" title="Edit logo">
                                    <span class="sr-only">Edit logo</span>
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-slate-500">Logo RA Fadhilah belum diunggah.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
</x-panitia-layout>
