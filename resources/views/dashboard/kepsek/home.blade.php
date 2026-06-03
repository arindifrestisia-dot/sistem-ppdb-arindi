<x-kepsek-layout title="Beranda Kepsek">
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
                    <h2 class="text-xl font-bold text-slate-900">Status Daftar Ulang</h2>
                    <p class="mt-2 text-sm text-slate-500">Perbandingan siswa lulus yang sudah dan belum menyelesaikan daftar ulang.</p>
                </div>
                <span class="rounded-full bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                    {{ array_sum($chartData['reRegistration']['series']) }} siswa
                </span>
            </div>
            <div id="reRegistrationChart" class="mt-6 h-[320px]"></div>
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
                    <p class="mt-2 text-sm text-slate-500">Ringkasan calon siswa yang baru masuk untuk kebutuhan monitoring kepala sekolah.</p>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">No. Registrasi</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Status Berkas</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Hasil Seleksi</th>
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
                                <td class="px-4 py-3 text-slate-600">{{ str_replace('_', ' ', $registration->selection_result) }}</td>
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

        <section class="rounded-[2rem] bg-blue-950 p-6 text-white shadow-sm">
            <h2 class="text-2xl font-bold">Ringkasan Konten Sekolah</h2>
            <div class="mt-6 space-y-4">
                @foreach ($contentSummary as $item)
                    <div class="rounded-3xl bg-white/10 p-4">
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-300">{{ $item['label'] }}</p>
                        <p class="mt-3 text-3xl font-extrabold">{{ $item['count'] }}</p>
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

        function renderBarChart() {
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
                    formatter: (value) => `${value.toFixed(1)}%`,
                    offsetY: -20,
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '11px',
                        fontWeight: 700,
                        colors: ['#0f172a'],
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '48%',
                        dataLabels: {
                            position: 'top',
                        },
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
                renderEmptyState('genderChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('verificationChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('reRegistrationChart', 'Library chart tidak berhasil dimuat.');
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

            renderDonutChart(
                'reRegistrationChart',
                dashboardChartData.reRegistration.labels,
                dashboardChartData.reRegistration.series,
                ['#059669', '#f97316']
            );

            renderBarChart();
            renderTreemapChart();
        });
    </script>
</x-kepsek-layout>
