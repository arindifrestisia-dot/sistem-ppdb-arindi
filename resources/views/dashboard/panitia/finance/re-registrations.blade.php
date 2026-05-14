<x-panitia-layout title="Daftar Ulang">
    <section class="space-y-6">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Daftar Ulang</h2>
                    <p class="mt-2 text-sm text-slate-500">Keuangan</p>
                </div>

                <span class="inline-flex items-center rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                    TA {{ $stats['current_academic_year'] }}
                </span>
            </div>
        </div>

        <div class="grid gap-5 xl:grid-cols-3">
            <article class="rounded-[1.75rem] border-t-4 border-cyan-400 bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Total Siswa Diterima</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ $stats['accepted_students'] }}</p>
            </article>

            <article class="rounded-[1.75rem] border-t-4 border-emerald-400 bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Sudah Lunas</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ $stats['paid_count'] }}</p>
                <span class="mt-3 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-700">{{ number_format($stats['paid_percentage'], 1) }}%</span>
            </article>

            <article class="rounded-[1.75rem] border-t-4 border-rose-400 bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Belum Lunas</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ $stats['unpaid_count'] }}</p>
                <span class="mt-3 inline-flex rounded-full bg-rose-100 px-3 py-1 text-sm font-semibold text-rose-700">{{ number_format($stats['unpaid_percentage'], 1) }}%</span>
            </article>
        </div>

        <section class="rounded-[2rem] bg-white shadow-sm">
            <div class="border-b border-slate-200 p-6">
                <form method="GET" class="grid gap-4 lg:grid-cols-[minmax(0,1.2fr)_220px_220px_220px_auto]">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama siswa / no. formulir..."
                        class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none"
                    >

                    <select name="jenis" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none">
                        <option value="">Semua Jenis</option>
                        @foreach ($paymentTypeOptions as $typeKey => $label)
                            <option value="{{ $typeKey }}" @selected($paymentType === $typeKey)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none">
                        <option value="">Semua Status</option>
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
                            href="{{ route('panitia.finances.re-registrations.export', ['q' => $search, 'jenis' => $paymentType, 'status' => $status, 'ta' => $academicYear]) }}"
                            class="rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Ekspor
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto rounded-b-[2rem]">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-[#f5f8fc] text-slate-500">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">No. Formulir</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Nama Siswa</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Kelas</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Total Biaya</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Jenis Pembayaran</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Detail Cicilan</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Status Pelunasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($records as $record)
                            <tr class="align-top">
                                <td class="px-6 py-5 font-semibold text-slate-900">{{ $record->display_form_number }}</td>
                                <td class="px-6 py-5">
                                    <p class="font-bold text-slate-900">{{ $record->display_student_name }}</p>
                                </td>
                                <td class="px-6 py-5 text-slate-700">{{ $record->display_class }}</td>
                                <td class="px-6 py-5 text-slate-700">{{ $record->display_rereg_amount }}</td>
                                <td class="px-6 py-5">
                                    @php
                                        $paymentTypeClasses = $record->display_rereg_payment_type_tone === 'emerald'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-blue-100 text-blue-700';
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $paymentTypeClasses }}">
                                        {{ $record->display_rereg_payment_type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="space-y-2">
                                        @foreach ($record->display_installments as $installment)
                                            @php
                                                $installmentClasses = match ($installment['tone']) {
                                                    'emerald' => 'bg-emerald-100 text-emerald-700',
                                                    'amber' => 'bg-amber-100 text-amber-700',
                                                    default => 'bg-slate-100 text-slate-600',
                                                };
                                            @endphp
                                            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-600">
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $installmentClasses }}">{{ $installment['label'] }}</span>
                                                <span>{{ $installment['text'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClasses = $record->display_rereg_status_tone === 'emerald'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700';
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $statusClasses }}">
                                        {{ $record->display_rereg_status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">Belum ada data pembayaran daftar ulang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $records->links() }}
            </div>
        </section>
    </section>
</x-panitia-layout>
