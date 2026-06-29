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
                            Export CSV
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
                                                <input type="hidden" name="interview_status" value="{{ $registration->interview_completed_at ? 'belum_selesai' : 'selesai' }}">

                                                @if ($registration->interview_completed_at)
                                                    <p class="text-xs text-slate-500">Selesai pada {{ $registration->interview_completed_at->format('d-m-Y H:i') }} WIB</p>
                                                    <button type="submit" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                                        Tandai Belum Selesai
                                                    </button>
                                                @else
                                                    <button type="submit" class="rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800">
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
