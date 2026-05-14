<x-panitia-layout title="Beranda Panitia">
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">
        <article class="rounded-3xl bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-700">Pendaftar</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-900">{{ $stats['total_pendaftar'] }}</p>
        </article>
        <article class="rounded-3xl bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Menunggu</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-900">{{ $stats['berkas_menunggu'] }}</p>
        </article>
        <article class="rounded-3xl bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700">Terverifikasi</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-900">{{ $stats['berkas_terverifikasi'] }}</p>
        </article>
        <article class="rounded-3xl bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Lulus</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-900">{{ $stats['total_lulus'] }}</p>
        </article>
        <article class="rounded-3xl bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-rose-700">Tidak Lulus</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-900">{{ $stats['total_tidak_lulus'] }}</p>
        </article>
    </div>

    <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Jumlah Siswa Terdaftar per Tahun</h2>
                <p class="mt-2 text-sm text-slate-500">Isi tahun 2019-2025 langsung dari dashboard. Tahun 2026-2027 dihitung otomatis dari pendaftaran sistem.</p>
            </div>
            <span class="w-fit rounded-full bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">
                2019 - 2027
            </span>
        </div>
        <div id="annualRegistrationChart" class="mt-6 h-[360px]"></div>

        <form method="POST" action="{{ route('panitia.dashboard.annual-student-counts.update') }}" class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
            @csrf
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-bold text-slate-900">Input Manual Tahun 2019-2025</h3>
                    <p class="mt-1 text-sm text-slate-500">Angka yang disimpan akan langsung dipakai pada grafik batang di atas.</p>
                </div>
                <button type="submit" class="w-fit rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Simpan Jumlah
                </button>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7">
                @foreach ($chartData['annualRegistrations']['manualInputs'] as $year => $total)
                    <label class="block">
                        <span class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">{{ $year }}</span>
                        <input
                            type="number"
                            name="counts[{{ $year }}]"
                            value="{{ old("counts.$year", $total) }}"
                            min="0"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                        >
                    </label>
                @endforeach
            </div>

            @error('counts')
                <p class="mt-4 text-sm font-semibold text-rose-600">{{ $message }}</p>
            @enderror
        </form>
    </section>

    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Komposisi Gender Pendaftar</h2>
                    <p class="mt-2 text-sm text-slate-500">Jumlah siswa perempuan dan laki-laki diambil langsung dari data pendaftaran.</p>
                </div>
                <span class="rounded-full bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">
                    {{ array_sum($chartData['gender']['series']) }} siswa
                </span>
            </div>
            <div id="genderChart" class="mt-6 h-[320px]"></div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Status Verifikasi Berkas</h2>
                    <p class="mt-2 text-sm text-slate-500">Ringkasan berkas terverifikasi, dalam proses, dan ditolak.</p>
                </div>
                <span class="rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                    {{ array_sum($chartData['verification']['series']) }} berkas
                </span>
            </div>
            <div id="verificationChart" class="mt-6 h-[320px]"></div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Persentase Kuota Kelas</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $chartData['classQuota']['note'] }}</p>
                </div>
                <span class="rounded-full bg-indigo-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-700">
                    A, B1, B2, B3
                </span>
            </div>
            <div id="classQuotaChart" class="mt-6 h-[320px]"></div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Asal Daerah Siswa</h2>
                    <p class="mt-2 text-sm text-slate-500">Treemap menampilkan konsentrasi asal daerah berdasarkan data siswa yang masuk.</p>
                </div>
                <span class="rounded-full bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                    {{ count($chartData['regions']) }} daerah
                </span>
            </div>
            <div id="regionTreemapChart" class="mt-6 h-[340px]"></div>
        </section>
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_380px]">
        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Pendaftaran Terbaru</h2>
                    <p class="mt-2 text-sm text-slate-500">Data ini langsung mengambil calon siswa dari dashboard orang tua.</p>
                </div>
                <a href="{{ route('panitia.registrations.index') }}" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Kelola Data</a>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">No. Registrasi</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Status Berkas</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($recentRegistrations as $registration)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-800">{{ $registration->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $registration->user?->email }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $registration->registration_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ str_replace('_', ' ', $registration->verification_status) }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('panitia.registrations.show', $registration) }}" class="font-semibold text-sky-700">Lihat detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-5 text-center text-slate-500">Belum ada pendaftaran masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-[2rem] bg-slate-900 p-6 text-white shadow-sm">
            <h2 class="text-2xl font-bold">Ringkasan Konten Sekolah</h2>
            <div class="mt-6 space-y-4">
                @foreach ($contentSummary as $type => $item)
                    <div class="rounded-3xl bg-white/10 p-4">
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-300">{{ $item['label'] }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-3xl font-extrabold">{{ $item['count'] }}</p>
                            <a href="{{ route('panitia.contents.index', ['type' => $type]) }}" class="text-sm font-semibold text-amber-300">Kelola</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const dashboardChartData = @json($chartData);

        function renderEmptyState(elementId, message) {
            const element = document.getElementById(elementId);

            if (!element) {
                return;
            }

            element.innerHTML = `
                <div style="display:flex;height:100%;align-items:center;justify-content:center;border:1px dashed #cbd5e1;border-radius:1.5rem;background:#f8fafc;padding:1rem;text-align:center;color:#64748b;font-size:0.875rem;">
                    ${message}
                </div>
            `;
        }

        function hasNonZeroSeries(series) {
            return Array.isArray(series) && series.some((value) => Number(value) > 0);
        }

        function renderDonutChart(elementId, labels, series, colors) {
            if (!hasNonZeroSeries(series)) {
                renderEmptyState(elementId, 'Belum ada data siswa untuk ditampilkan.');
                return;
            }

            new ApexCharts(document.querySelector(`#${elementId}`), {
                chart: {
                    type: 'donut',
                    height: 320,
                    toolbar: { show: false },
                },
                series,
                labels,
                colors,
                legend: {
                    position: 'bottom',
                    fontFamily: 'Poppins, sans-serif',
                },
                dataLabels: {
                    enabled: true,
                    formatter: (value) => `${value.toFixed(1)}%`,
                },
                stroke: {
                    width: 0,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '64%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: () => series.reduce((total, item) => total + Number(item), 0),
                                },
                            },
                        },
                    },
                },
                tooltip: {
                    y: {
                        formatter: (value) => `${value} siswa`,
                    },
                },
            }).render();
        }

        function renderAnnualRegistrationChart() {
            const { labels, series } = dashboardChartData.annualRegistrations;

            if (!hasNonZeroSeries(series)) {
                renderEmptyState('annualRegistrationChart', 'Grafik akan muncul setelah angka manual atau data pendaftaran sistem tersedia.');
                return;
            }

            new ApexCharts(document.querySelector('#annualRegistrationChart'), {
                chart: {
                    type: 'bar',
                    height: 360,
                    toolbar: { show: false },
                },
                series: [{
                    name: 'Siswa Terdaftar',
                    data: series,
                }],
                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            fontFamily: 'Poppins, sans-serif',
                        },
                    },
                },
                yaxis: {
                    labels: {
                        formatter: (value) => `${value.toFixed(0)} siswa`,
                    },
                },
                colors: ['#0ea5e9'],
                dataLabels: {
                    enabled: true,
                    formatter: (value) => `${value} siswa`,
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '12px',
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '46%',
                    },
                },
                tooltip: {
                    y: {
                        formatter: (value, { dataPointIndex }) => {
                            const year = Number(labels[dataPointIndex]);
                            const source = year >= 2026 ? 'data sistem' : 'data manual';

                            return `${value} siswa (${source})`;
                        },
                    },
                },
                grid: {
                    borderColor: '#e2e8f0',
                },
            }).render();
        }

        function renderClassQuotaChart() {
            const { labels, series, counts } = dashboardChartData.classQuota;

            if (!hasNonZeroSeries(counts)) {
                renderEmptyState('classQuotaChart', 'Persentase kuota kelas akan muncul setelah data siswa tersedia.');
                return;
            }

            new ApexCharts(document.querySelector('#classQuotaChart'), {
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                },
                series: [{
                    name: 'Persentase',
                    data: series,
                }],
                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            fontFamily: 'Poppins, sans-serif',
                        },
                    },
                },
                yaxis: {
                    max: 100,
                    labels: {
                        formatter: (value) => `${value.toFixed(0)}%`,
                    },
                },
                colors: ['#4f46e5'],
                dataLabels: {
                    enabled: true,
                    formatter: (value, { dataPointIndex }) => `${value.toFixed(1)}% (${counts[dataPointIndex]})`,
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px',
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '48%',
                    },
                },
                tooltip: {
                    y: {
                        formatter: (value, { dataPointIndex }) => `${value.toFixed(1)}% - ${counts[dataPointIndex]} siswa`,
                    },
                },
                grid: {
                    borderColor: '#e2e8f0',
                },
            }).render();
        }

        function renderTreemapChart() {
            if (!dashboardChartData.regions.length) {
                renderEmptyState('regionTreemapChart', 'Asal daerah siswa belum tersedia untuk divisualisasikan.');
                return;
            }

            new ApexCharts(document.querySelector('#regionTreemapChart'), {
                chart: {
                    type: 'treemap',
                    height: 340,
                    toolbar: { show: false },
                },
                series: [{
                    data: dashboardChartData.regions,
                }],
                legend: {
                    show: false,
                },
                colors: ['#0f766e', '#0ea5e9', '#f59e0b', '#8b5cf6', '#ef4444'],
                dataLabels: {
                    enabled: true,
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '13px',
                    },
                    formatter: (text, opts) => {
                        const value = opts.value;
                        return [`${text}`, `${value} siswa`];
                    },
                },
            }).render();
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof ApexCharts === 'undefined') {
                renderEmptyState('annualRegistrationChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('genderChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('verificationChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('classQuotaChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('regionTreemapChart', 'Library chart tidak berhasil dimuat.');
                return;
            }

            renderDonutChart(
                'genderChart',
                dashboardChartData.gender.labels,
                dashboardChartData.gender.series,
                ['#ec4899', '#2563eb']
            );

            renderDonutChart(
                'verificationChart',
                dashboardChartData.verification.labels,
                dashboardChartData.verification.series,
                ['#10b981', '#f59e0b', '#ef4444']
            );

            renderAnnualRegistrationChart();
            renderClassQuotaChart();
            renderTreemapChart();
        });
    </script>
</x-panitia-layout>
