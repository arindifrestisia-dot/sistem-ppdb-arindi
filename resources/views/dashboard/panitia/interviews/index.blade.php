<x-panitia-layout title="Jadwal Wawancara">
    <section class="space-y-6">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Wawancara</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Manajemen Jadwal Wawancara</h2>
                    <p class="mt-2 text-sm text-slate-500">Atur jadwal untuk orang tua yang sudah mengisi formulir, serta pantau jadwal wawancara yang telah dipilih.</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Total Siap Wawancara</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ $stats['total'] }}</p>
            </article>

            <article class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Sudah Memilih Jadwal</p>
                <p class="mt-2 text-5xl font-extrabold text-emerald-700">{{ $stats['selected'] }}</p>
            </article>

            <article class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Belum Memilih Jadwal</p>
                <p class="mt-2 text-5xl font-extrabold text-amber-700">{{ $stats['waiting'] }}</p>
            </article>

            <article class="rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Sudah Selesai Wawancara</p>
                <p class="mt-2 text-5xl font-extrabold text-blue-700">{{ $stats['completed'] }}</p>
            </article>
        </div>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Kelola Jadwal</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-900">CRUD Jadwal Wawancara</h3>
                    <p class="mt-2 text-sm text-slate-500">Tambah, ubah, nonaktifkan, atau hapus jadwal yang akan muncul di portal orang tua.</p>
                </div>
                <span class="inline-flex rounded-full bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700">
                    {{ $managedSchedules->count() }} jadwal
                </span>
            </div>

            <div class="mt-6 grid gap-4 xl:grid-cols-2">
                <form method="POST" action="{{ route('panitia.interviews.schedules.store') }}" class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
                    @csrf
                    <input type="hidden" name="q" value="{{ $search }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="ta" value="{{ $academicYear }}">
                    <input type="hidden" name="page" value="{{ $registrations->currentPage() }}">

                    <h4 class="text-base font-bold text-slate-900">Tambah Satu Jadwal</h4>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Tanggal</span>
                            <input type="date" name="interview_date" min="2025-10-01" max="2026-07-31" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Sesi</span>
                            <input type="text" name="session_label" value="Jadwal Wawancara" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Jam</span>
                            <input type="text" name="interview_time" value="08.00 - 13.00 WIB" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Ruangan</span>
                            <input type="text" name="room" value="RUANGAN TU" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Urutan</span>
                            <input type="number" name="sort_order" value="0" min="0" class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <div class="flex items-end">
                            <button type="submit" class="h-11 w-full rounded-xl bg-blue-700 px-4 text-sm font-bold text-white transition hover:bg-blue-800">Tambah</button>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('panitia.interviews.schedules.bulk-store') }}" class="rounded-2xl bg-blue-50 p-4 ring-1 ring-blue-100">
                    @csrf
                    <input type="hidden" name="q" value="{{ $search }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="ta" value="{{ $academicYear }}">
                    <input type="hidden" name="page" value="{{ $registrations->currentPage() }}">
                    <input type="hidden" name="is_active" value="1">

                    <h4 class="text-base font-bold text-slate-900">Tambah Massal</h4>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Tanggal Mulai</span>
                            <input type="date" name="start_date" min="2025-10-01" max="2026-07-31" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Tanggal Selesai</span>
                            <input type="date" name="end_date" min="2025-10-01" max="2026-07-31" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Sesi</span>
                            <input type="text" name="session_label" value="Jadwal Wawancara" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Jam</span>
                            <input type="text" name="interview_time" value="08.00 - 13.00 WIB" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Ruangan</span>
                            <input type="text" name="room" value="RUANGAN TU" required class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                        <label class="block">
                            <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Urutan</span>
                            <input type="number" name="sort_order" value="0" min="0" class="mt-2 h-11 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                        </label>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Hari yang Dibuat</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ([1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab'] as $dayNumber => $dayLabel)
                                <label class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-2 text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                    <input type="checkbox" name="days[]" value="{{ $dayNumber }}" checked class="h-4 w-4 rounded border-slate-300 text-blue-700 focus:ring-blue-500">
                                    {{ $dayLabel }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="mt-4 h-11 w-full rounded-xl bg-slate-900 px-4 text-sm font-bold text-white transition hover:bg-slate-800">Tambah Massal</button>
                </form>
            </div>

            <div class="mt-5 max-h-[34rem] overflow-auto rounded-2xl border border-slate-200">
                <table class="min-w-[1100px] divide-y divide-slate-200 text-sm">
                    <thead class="sticky top-0 z-10 bg-[#f5f8fc] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-[0.08em]">Tanggal</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-[0.08em]">Sesi</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-[0.08em]">Jam</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-[0.08em]">Ruangan</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-[0.08em]">Urutan</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-[0.08em]">Aktif</th>
                            <th class="px-4 py-3 text-left font-bold uppercase tracking-[0.08em]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($managedSchedules as $schedule)
                            <tr>
                                <td class="px-4 py-3">
                                    <form id="schedule-update-{{ $schedule->id }}" method="POST" action="{{ route('panitia.interviews.schedules.update', $schedule) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="q" value="{{ $search }}">
                                        <input type="hidden" name="status" value="{{ $status }}">
                                        <input type="hidden" name="ta" value="{{ $academicYear }}">
                                        <input type="hidden" name="page" value="{{ $registrations->currentPage() }}">
                                        <input type="date" name="interview_date" value="{{ $schedule->interview_date->toDateString() }}" min="2025-10-01" max="2026-07-31" required class="h-10 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                                    </form>
                                </td>
                                <td class="px-4 py-3">
                                    <input form="schedule-update-{{ $schedule->id }}" type="text" name="session_label" value="{{ $schedule->session_label }}" required class="h-10 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                                </td>
                                <td class="px-4 py-3">
                                    <input form="schedule-update-{{ $schedule->id }}" type="text" name="interview_time" value="{{ $schedule->interview_time }}" required class="h-10 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                                </td>
                                <td class="px-4 py-3">
                                    <input form="schedule-update-{{ $schedule->id }}" type="text" name="room" value="{{ $schedule->room }}" required class="h-10 w-full rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                                </td>
                                <td class="px-4 py-3">
                                    <input form="schedule-update-{{ $schedule->id }}" type="number" name="sort_order" value="{{ $schedule->sort_order }}" min="0" class="h-10 w-24 rounded-xl border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none">
                                </td>
                                <td class="px-4 py-3">
                                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                                        <input form="schedule-update-{{ $schedule->id }}" type="checkbox" name="is_active" value="1" @checked($schedule->is_active) class="h-4 w-4 rounded border-slate-300 text-blue-700 focus:ring-blue-500">
                                        Aktif
                                    </label>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <button form="schedule-update-{{ $schedule->id }}" type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white transition hover:bg-slate-800">Simpan</button>
                                        <form method="POST" action="{{ route('panitia.interviews.schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal wawancara ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="q" value="{{ $search }}">
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <input type="hidden" name="ta" value="{{ $academicYear }}">
                                            <input type="hidden" name="page" value="{{ $registrations->currentPage() }}">
                                            <button type="submit" class="rounded-xl border border-rose-200 px-4 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada jadwal wawancara. Tambahkan jadwal baru melalui form di atas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-[2rem] bg-white shadow-sm">
            <div class="border-b border-slate-200 p-6">
                <form method="GET" class="grid gap-4 lg:grid-cols-[minmax(0,1.2fr)_220px_220px_auto]">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama / no. registrasi / email..."
                        class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none"
                    >

                    <select name="status" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none">
                        <option value="">Semua Status Jadwal</option>
                        @foreach ($statusOptions as $statusKey => $label)
                            <option value="{{ $statusKey }}" @selected($status === $statusKey)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="ta" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none">
                        <option value="">Semua TA</option>
                        @foreach ($academicYearOptions as $yearOption)
                            <option value="{{ $yearOption }}" @selected($academicYear === $yearOption)>{{ $yearOption }}</option>
                        @endforeach
                    </select>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Terapkan</button>
                        <a
                            href="{{ route('panitia.interviews.export', ['q' => $search, 'status' => $status, 'ta' => $academicYear]) }}"
                            class="rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Ekspor Excel
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto rounded-b-[2rem]">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-[#f5f8fc] text-slate-500">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Pendaftar</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">No. Registrasi</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Status Jadwal</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Jadwal Terpilih Orang Tua</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Aksi Panitia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($registrations as $registration)
                            <tr class="align-top">
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">{{ $registration->full_name ?: '-' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $registration->user?->name ?? '-' }} • {{ $registration->user?->email ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-5 text-slate-700">{{ $registration->registration_number ?? '-' }}</td>
                                <td class="px-6 py-5">
                                    @php
                                        $badgeClasses = $registration->display_interview_status_tone === 'emerald'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700';
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $badgeClasses }}">
                                        {{ $registration->display_interview_status_label }}
                                    </span>
                                    @if ($registration->interview_selected_at)
                                        @php
                                            $completionClasses = $registration->display_interview_completion_tone === 'blue'
                                                ? 'bg-blue-100 text-blue-700'
                                                : 'bg-slate-100 text-slate-600';
                                        @endphp
                                        <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $completionClasses }}">
                                            {{ $registration->display_interview_completion_label }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-slate-700">
                                    @if ($registration->interview_selected_at)
                                        <div class="space-y-1">
                                            <p><span class="font-semibold">Hari:</span> {{ $registration->interview_day_name ?: '-' }}</p>
                                            <p><span class="font-semibold">Tanggal:</span> {{ optional($registration->interview_date)->translatedFormat('d F Y') ?: '-' }}</p>
                                            <p><span class="font-semibold">Jam:</span> {{ $registration->interview_time ?: '-' }}</p>
                                            <p><span class="font-semibold">Ruangan:</span> {{ $registration->interview_room ?: '-' }}</p>
                                        </div>
                                        <p class="mt-2 text-xs text-slate-500">
                                            Dipilih pada {{ optional($registration->interview_selected_at)->format('d-m-Y H:i') }} WIB
                                        </p>
                                    @else
                                        <p class="text-slate-500">Belum ada jadwal yang dipilih.</p>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if ($registration->interview_selected_at)
                                        @php
                                            $isInterviewLocked = $registration->interview_completed_at || filled($registration->interview_notes);
                                        @endphp

                                        <div class="space-y-3">
                                            <span class="inline-flex rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                                                Jadwal tidak dapat diubah
                                            </span>

                                            <form method="POST" action="{{ route('panitia.interviews.status.update', $registration) }}" class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="q" value="{{ $search }}">
                                                <input type="hidden" name="status" value="{{ $status }}">
                                                <input type="hidden" name="ta" value="{{ $academicYear }}">
                                                <input type="hidden" name="page" value="{{ $registrations->currentPage() }}">

                                                <label class="block">
                                                    <span class="text-xs font-bold uppercase tracking-[0.08em] text-slate-500">Catatan hasil wawancara</span>
                                                    <textarea
                                                        name="interview_notes"
                                                        rows="4"
                                                        @disabled($isInterviewLocked)
                                                        class="mt-2 w-full min-w-56 rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-sky-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-600"
                                                        placeholder="Tulis catatan hasil wawancara calon siswa..."
                                                    >{{ old('interview_notes', $registration->interview_notes) }}</textarea>
                                                </label>

                                                @if ($isInterviewLocked)
                                                    @if ($registration->interview_completed_at)
                                                        <p class="text-xs text-slate-500">Selesai pada {{ $registration->interview_completed_at->format('d-m-Y H:i') }} WIB</p>
                                                    @endif
                                                    <p class="rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">Catatan sudah disimpan dan tidak dapat diubah lagi.</p>
                                                @else
                                                    <button type="submit" name="interview_status" value="simpan_catatan" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                                        Simpan Catatan
                                                    </button>
                                                    <button type="submit" name="interview_status" value="selesai" class="rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800">
                                                        Tandai Selesai Wawancara
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('panitia.interviews.assign', $registration) }}" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="q" value="{{ $search }}">
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <input type="hidden" name="ta" value="{{ $academicYear }}">
                                            <input type="hidden" name="page" value="{{ $registrations->currentPage() }}">

                                            <select
                                                name="interview_schedule_key"
                                                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none"
                                                required
                                            >
                                                <option value="">Pilih Jadwal</option>
                                                @foreach ($scheduleOptions as $option)
                                                    <option value="{{ $option['key'] }}">
                                                        {{ $option['session_label'] }} - {{ $option['day_name'] }}, {{ $option['formatted_date'] }} ({{ $option['time'] }})
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                                                Simpan Jadwal
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada data pendaftar yang siap dijadwalkan wawancara.</td>
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
</x-panitia-layout>
