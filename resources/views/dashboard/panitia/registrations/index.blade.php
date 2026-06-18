@php
    $layoutComponent = auth()->user()?->isKepsek() ? 'kepsek-layout' : 'panitia-layout';
    $registrationRoutePrefix = auth()->user()?->isKepsek() ? 'kepsek' : 'panitia';
@endphp

<x-dynamic-component :component="$layoutComponent" title="Data Siswa dan Calon Siswa">
    <section class="space-y-6">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Data Siswa</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $segmentOptions[$segment] ?? 'Data Siswa' }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Panitia dapat mencari data siswa, memfilter per kelas dan tahun ajaran, lalu membuka biodata lengkap setiap siswa.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($segmentOptions as $segmentKey => $label)
                        <a
                            href="{{ route($registrationRoutePrefix . '.registrations.index', ['segment' => $segmentKey]) }}"
                            class="rounded-full px-4 py-2 text-sm font-semibold {{ $segment === $segmentKey ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <section class="rounded-[2rem] bg-white shadow-sm">
            <div class="border-b border-slate-200 p-6">
                <form method="GET" class="grid gap-4 lg:grid-cols-[minmax(0,1.3fr)_220px_220px_auto]">
                    <input type="hidden" name="segment" value="{{ $segment }}">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama atau NIS"
                        class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none"
                    >

                    <select name="class" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach ($classOptions as $classOption)
                            <option value="{{ $classOption }}" @selected($class === $classOption)>{{ $classOption }}</option>
                        @endforeach
                    </select>

                    <select name="ta" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none">
                        <option value="">Semua TA</option>
                        @foreach ($academicYearOptions as $yearOption)
                            <option value="{{ $yearOption }}" @selected($academicYear === $yearOption)>{{ $yearOption }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Terapkan</button>
                </form>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 p-6">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $segmentOptions[$segment] ?? 'Data Siswa' }}</h3>
                    <p class="mt-2 text-sm text-slate-500">Total data pada tampilan ini: {{ $registrations->total() }} siswa</p>
                </div>

                <a
                    href="{{ route($registrationRoutePrefix . '.registrations.export', ['segment' => $segment, 'q' => $search, 'class' => $class, 'ta' => $academicYear]) }}"
                    class="rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Ekspor Excel
                </a>
            </div>

            <div class="overflow-x-auto rounded-b-[2rem] border-t border-slate-200">
                <table class="min-w-[900px] divide-y divide-slate-200 text-sm">
                    <thead class="bg-[#f9f5ea] text-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">NIS</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Nama Siswa</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Kelas</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Jenis Kelamin</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($registrations as $registration)
                            <tr>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        @if ($registration->child_photo_path)
                                            <img
                                                src="{{ asset('storage/' . $registration->child_photo_path) }}"
                                                alt="{{ $registration->full_name }}"
                                                class="h-14 w-14 rounded-2xl object-cover ring-1 ring-slate-200"
                                            >
                                        @else
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-sm font-bold text-slate-500 ring-1 ring-slate-200">
                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($registration->full_name, 0, 2)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-bold text-slate-900">{{ $registration->registration_number ?? '-' }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ $registration->display_academic_year }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">{{ $registration->full_name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $registration->user?->email ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-5 text-slate-700">{{ $registration->display_class }}</td>
                                <td class="px-6 py-5 text-slate-700">{{ $registration->gender }}</td>
                                <td class="px-6 py-5">
                                    <a href="{{ route($registrationRoutePrefix . '.registrations.show', ['registration' => $registration, 'segment' => $segment, 'q' => $search, 'class' => $class, 'ta' => $academicYear]) }}" class="inline-flex rounded-xl bg-sky-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-800">
                                        Lihat data
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data siswa pada kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $registrations->links() }}
            </div>
        </section>
    </section>
</x-dynamic-component>
