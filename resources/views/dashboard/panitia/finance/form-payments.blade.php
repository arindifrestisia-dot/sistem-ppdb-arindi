<x-panitia-layout title="Bayar Formulir">
    <section class="space-y-6">
        <div class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Bayar Formulir</h2>
                    <p class="mt-2 text-sm text-slate-500">Keuangan</p>
                </div>

                <span class="inline-flex items-center rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">
                    TA {{ $stats['current_academic_year'] }}
                </span>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-[1.75rem] border-t-4 border-cyan-400 bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Total Formulir Terjual</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ $stats['total_forms'] }}</p>
            </article>

            <article class="rounded-[1.75rem] border-t-4 border-emerald-400 bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Total Pemasukan</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ 'Rp ' . number_format($stats['total_income'] / 1000000, 1, ',', '.') . ' jt' }}</p>
                <p class="mt-3 text-sm text-slate-400">@Rp 150.000 / formulir</p>
            </article>

            <article class="rounded-[1.75rem] border-t-4 border-blue-400 bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Formulir Terisi Lengkap</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ $stats['completed_forms'] }}</p>
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 font-semibold text-blue-700">
                        {{ number_format($stats['completion_rate'], 1, ',', '.') }}%
                    </span>
                    <span>dari total formulir terjual</span>
                </div>
            </article>

            <article class="rounded-[1.75rem] border-t-4 border-amber-400 bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-medium text-slate-400">Belum Dikonfirmasi</p>
                <p class="mt-2 text-5xl font-extrabold text-slate-950">{{ $stats['unconfirmed_count'] }}</p>
                <span class="mt-3 inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">Perlu verifikasi</span>
            </article>
        </div>

        <section class="rounded-[2rem] bg-white shadow-sm">
            <div class="border-b border-slate-200 p-6">
                <form method="GET" class="grid gap-4 lg:grid-cols-[minmax(0,1.2fr)_220px_220px_auto]">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama / no. formulir..."
                        class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-sky-500 focus:outline-none"
                    >

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
                            href="{{ route('panitia.finances.form-payments.export', ['q' => $search, 'status' => $status, 'ta' => $academicYear]) }}"
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
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Nama Pembeli</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Nama Calon Siswa</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Tanggal Bayar</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Jumlah</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Metode</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Status</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-[0.08em]">Status Pengisian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($payments as $payment)
                            <tr class="align-top">
                                <td class="px-6 py-5 font-semibold text-slate-900">{{ $payment->display_form_number }}</td>
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">{{ $payment->display_buyer_name }}</p>
                                </td>
                                <td class="px-6 py-5 text-slate-700">{{ $payment->display_student_name }}</td>
                                <td class="px-6 py-5 text-slate-700">{{ $payment->display_payment_date }}</td>
                                <td class="px-6 py-5 text-slate-700">{{ $payment->display_form_amount }}</td>
                                <td class="px-6 py-5 text-slate-700">{{ $payment->display_form_method }}</td>
                                <td class="px-6 py-5">
                                    @php
                                        $badgeClasses = $payment->display_payment_status_tone === 'emerald'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700';
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $badgeClasses }}">
                                        {{ $payment->display_payment_status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $fillingBadgeClasses = $payment->display_filling_status_tone === 'emerald'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-amber-100 text-amber-700';
                                    @endphp
                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-sm font-semibold {{ $fillingBadgeClasses }}">
                                        {{ $payment->display_filling_status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-slate-500">Belum ada data pembayaran formulir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $payments->links() }}
            </div>
        </section>
    </section>
</x-panitia-layout>
