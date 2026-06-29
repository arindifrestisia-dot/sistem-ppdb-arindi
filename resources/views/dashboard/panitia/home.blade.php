@php
    $isHeadmasterDashboard = auth()->user()?->isKepsek();
    $dashboardLayoutComponent = $isHeadmasterDashboard ? 'kepsek-layout' : 'panitia-layout';
    $dashboardRoutePrefix = $isHeadmasterDashboard ? 'kepsek' : 'panitia';
    $paymentSummaryHref = $isHeadmasterDashboard
        ? route('kepsek.registrations.index', ['segment' => 'daftar_ulang'])
        : route('panitia.finances.re-registrations.index', ['status' => 'lunas']);
@endphp

<x-dynamic-component :component="$dashboardLayoutComponent" :title="$isHeadmasterDashboard ? 'Beranda Kepsek' : 'Beranda Panitia'">
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

    <section class="mt-6 rounded-[2rem] bg-white p-5 shadow-sm ring-1 ring-slate-100">
        <div class="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
            <div class="flex gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $classQuota['is_full'] ? 'bg-rose-100 text-rose-600' : 'bg-orange-100 text-orange-600' }}">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M7 7a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm9 2a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM4 20a6 6 0 0 1 12 0H4Zm11.3-7.5A7.5 7.5 0 0 1 19 19.1V20h3a5.5 5.5 0 0 0-6.7-7.5Z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-sky-500">Status Kuota</p>
                        <span class="rounded-full {{ $classQuota['is_full'] ? 'bg-rose-100 text-rose-700' : 'bg-orange-100 text-orange-700' }} px-3 py-1 text-xs font-extrabold">
                            {{ $classQuota['percentage'] }}% terisi
                        </span>
                    </div>
                    <h2 class="mt-1 text-2xl font-extrabold text-slate-900">{{ $classQuota['is_full'] ? 'Kuota Penuh' : 'Masih Tersedia' }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Tahun Ajaran {{ $classQuota['academic_year'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 lg:min-w-[360px]">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold text-slate-400">Terisi</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $classQuota['filled'] }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold text-slate-400">Kuota</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $classQuota['capacity'] }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-xs font-bold text-slate-400">Sisa</p>
                    <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $classQuota['remaining'] }}</p>
                </div>
            </div>
        </div>

        <div class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100">
            <div
                class="h-full rounded-full {{ $classQuota['is_full'] ? 'bg-rose-500' : 'bg-orange-500' }}"
                style="width: {{ min($classQuota['percentage'], 100) }}%"
            ></div>
        </div>
        <p class="mt-3 text-sm text-slate-500">
            {{ $classQuota['remaining'] }} kursi tersisa dari kuota {{ $classQuota['capacity'] }} siswa.
        </p>
    </section>

    <section class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route($dashboardRoutePrefix . '.registrations.index', ['segment' => 'saat_ini']) }}" class="group rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-emerald-100">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m5 12 4 4L19 6"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-indigo-400">Persentase Kelulusan</p>
                    <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $summaryCards['graduation']['percentage'] }}</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">{{ $summaryCards['graduation']['detail'] }}</p>
                </div>
            </div>
        </a>

        <a href="{{ $paymentSummaryHref }}" class="group rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-sky-100">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm2 5h6V6H9v2Zm0 5h6v-2H9v2Zm0 5h4v-2H9v2Z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-indigo-400">Pembayaran Selesai</p>
                    <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $summaryCards['payment']['percentage'] }}</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">{{ $summaryCards['payment']['detail'] }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route($dashboardRoutePrefix . '.registrations.index', ['segment' => 'saat_ini']) }}" class="group rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-violet-100">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="10" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M17 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-indigo-400">Rasio Laki-laki : Perempuan</p>
                    <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $summaryCards['gender']['ratio'] }}</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">{{ $summaryCards['gender']['detail'] }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route($dashboardRoutePrefix . '.registrations.index', ['segment' => 'calon']) }}" class="group rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-orange-100">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-orange-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M8 3h8v5a4 4 0 0 1-2.1 3.52A4 4 0 0 1 16 15v6H8v-6a4 4 0 0 1 2.1-3.48A4 4 0 0 1 8 8V3Zm2 2v3a2 2 0 0 0 4 0V5h-4Zm0 14h4v-4a2 2 0 0 0-4 0v4Z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-indigo-400">Pendaftar Belum Diputuskan</p>
                    <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $summaryCards['undecided']['percentage'] }}</p>
                    <p class="mt-1 text-sm font-medium text-slate-500">{{ $summaryCards['undecided']['detail'] }}</p>
                </div>
            </div>
        </a>
    </section>

    <section class="mt-6 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-100">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-500">Tahapan PPDB</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Progress Pendaftaran RA Fadhilah</h2>
            </div>
            <span class="w-fit rounded-full bg-blue-50 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.14em] text-blue-700">
                Data Sistem
            </span>
        </div>

        <div class="mt-6 space-y-5">
            @foreach ($stageProgress as $stage)
                <div>
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm font-bold text-slate-700">{{ $stage['label'] }}</p>
                        <p class="shrink-0 text-sm font-extrabold text-slate-900">
                            {{ $stage['count'] }} <span class="font-bold text-slate-500">({{ number_format($stage['percentage'], 1, ',', '.') }}%)</span>
                        </p>
                    </div>
                    <div class="mt-3 h-3 overflow-hidden rounded-full bg-blue-50">
                        <div class="h-full rounded-full bg-blue-600" style="width: {{ min($stage['percentage'], 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_minmax(360px,0.75fr)]">
        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Jumlah Siswa Terdaftar per Tahun</h2>
                    <p class="mt-2 text-sm text-slate-500">Isi tahun 2019-2025 langsung dari dashboard. Tahun 2026-2027 dihitung otomatis dari pendaftaran sistem.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-fit rounded-full bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">
                        2019 - 2027
                    </span>
                    @unless ($isHeadmasterDashboard)
                        <button
                            type="button"
                            id="toggleAnnualManualInput"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100"
                            aria-controls="annualManualInputForm"
                            aria-expanded="false"
                            title="Ubah data manual 2019-2025"
                        >
                            <span class="sr-only">Ubah data manual tahun 2019 sampai 2025</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                            </svg>
                        </button>
                    @endunless
                </div>
            </div>
            <div id="annualRegistrationChart" class="mt-6 h-[360px]"></div>

            @unless ($isHeadmasterDashboard)
                <form
                    id="annualManualInputForm"
                    method="POST"
                    action="{{ route('panitia.dashboard.annual-student-counts.update') }}"
                    class="mt-6 {{ $errors->has('counts') || $errors->has('counts.*') ? '' : 'hidden' }} rounded-3xl border border-slate-200 bg-slate-50 p-5"
                >
                    @csrf
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900">Ubah Jumlah Siswa 2019-2025</h3>
                            <p class="mt-1 text-sm text-slate-500">Angka yang disimpan akan langsung dipakai pada grafik batang di atas.</p>
                        </div>
                        <button type="submit" class="w-fit rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Simpan Jumlah
                        </button>
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 2xl:grid-cols-7">
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
            @endunless
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
            <div id="regionTreemapChart" class="mt-6 h-[360px]"></div>
        </section>
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
                    <h2 class="text-xl font-bold text-slate-900">Usia Pendaftar</h2>
                    <p class="mt-2 text-sm text-slate-500">Distribusi usia dihitung otomatis dari tanggal lahir calon siswa.</p>
                </div>
                <span class="rounded-full bg-cyan-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">
                    {{ $chartData['ageDistribution']['total'] }} data
                </span>
            </div>
            <div id="ageDistributionChart" class="mt-6 h-[320px]"></div>
            <div class="mt-4 rounded-3xl bg-indigo-50 px-5 py-4 text-sm font-semibold text-slate-600">
                {{ $chartData['ageDistribution']['note'] }}
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Pekerjaan Ibu</h2>
                    <p class="mt-2 text-sm text-slate-500">Latar belakang pendaftar berdasarkan pekerjaan ibu.</p>
                </div>
                <span class="rounded-full bg-cyan-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">
                    {{ $chartData['motherJobs']['total'] }} data
                </span>
            </div>
            <div id="motherJobsChart" class="mt-6 h-[320px]"></div>
            <div class="mt-4 rounded-3xl bg-indigo-50 px-5 py-4 text-sm font-semibold text-slate-600">
                {{ $chartData['motherJobs']['note'] }}
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Pekerjaan Ayah</h2>
                    <p class="mt-2 text-sm text-slate-500">Latar belakang pendaftar berdasarkan pekerjaan ayah.</p>
                </div>
                <span class="rounded-full bg-cyan-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">
                    {{ $chartData['fatherJobs']['total'] }} data
                </span>
            </div>
            <div id="fatherJobsChart" class="mt-6 h-[320px]"></div>
            <div class="mt-4 rounded-3xl bg-indigo-50 px-5 py-4 text-sm font-semibold text-slate-600">
                {{ $chartData['fatherJobs']['note'] }}
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Penghasilan Orang Tua</h2>
                    <p class="mt-2 text-sm text-slate-500">Distribusi penghasilan ayah dan ibu dari biodata pendaftaran.</p>
                </div>
                <span class="rounded-full bg-cyan-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">
                    {{ $chartData['parentIncomes']['total'] }} data
                </span>
            </div>
            <div id="parentIncomesChart" class="mt-6 h-[320px]"></div>
            <div class="mt-4 rounded-3xl bg-indigo-50 px-5 py-4 text-sm font-semibold text-slate-600">
                {{ $chartData['parentIncomes']['note'] }}
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Pendidikan Orang Tua</h2>
                    <p class="mt-2 text-sm text-slate-500">Distribusi pendidikan ayah dan ibu dari biodata pendaftaran.</p>
                </div>
                <span class="rounded-full bg-cyan-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">
                    {{ $chartData['parentEducations']['total'] }} data
                </span>
            </div>
            <div id="parentEducationsChart" class="mt-6 h-[320px]"></div>
            <div class="mt-4 rounded-3xl bg-indigo-50 px-5 py-4 text-sm font-semibold text-slate-600">
                {{ $chartData['parentEducations']['note'] }}
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Perbandingan Status Pendaftar</h2>
                    <p class="mt-2 text-sm text-slate-500">Ringkasan keputusan pendaftar berdasarkan data seleksi PPDB.</p>
                </div>
                <span class="rounded-full bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                    {{ array_sum($chartData['applicantStatus']['series']) }} pendaftar
                </span>
            </div>
            <div id="applicantStatusChart" class="mt-6 h-[320px]"></div>
            <div class="mt-4 rounded-3xl bg-indigo-50 px-5 py-4 text-sm font-semibold text-slate-600">
                {{ $chartData['applicantStatus']['note'] }}
            </div>
        </section>

        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Tren Pendaftar Bulanan</h2>
                    <p class="mt-2 text-sm text-slate-500">Jumlah pendaftar yang masuk per bulan berdasarkan data sistem.</p>
                </div>
                <span class="rounded-full bg-fuchsia-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-fuchsia-700">
                    {{ array_sum($chartData['monthlyTrend']['series']) }} pendaftar
                </span>
            </div>
            <div id="monthlyTrendChart" class="mt-6 h-[320px]"></div>
            <div class="mt-4 rounded-3xl bg-fuchsia-50 px-5 py-4 text-sm font-semibold text-slate-600">
                {{ $chartData['monthlyTrend']['note'] }}
            </div>
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
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_380px]">
        <section class="rounded-[2rem] bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Pendaftaran Terbaru</h2>
                    <p class="mt-2 text-sm text-slate-500">Data ini langsung mengambil calon siswa dari dashboard orang tua.</p>
                </div>
                <a href="{{ route($dashboardRoutePrefix . '.registrations.index') }}" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Kelola Data</a>
            </div>

            <div class="mt-6 overflow-x-auto rounded-3xl border border-slate-200">
                <table class="min-w-[680px] divide-y divide-slate-200 text-sm">
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
                                    <a href="{{ route($dashboardRoutePrefix . '.registrations.show', $registration) }}" class="font-semibold text-sky-700">Lihat detail</a>
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
                            <a href="{{ route($dashboardRoutePrefix . '.contents.index', ['type' => $type]) }}" class="text-sm font-semibold text-amber-300">Kelola</a>
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
                    formatter: (value) => `${value}`,
                    offsetY: -22,
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: ['#0f172a'],
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: '46%',
                        dataLabels: {
                            position: 'top',
                        },
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

        function renderAgeDistributionChart() {
            const { labels, series } = dashboardChartData.ageDistribution;

            if (!hasNonZeroSeries(series)) {
                renderEmptyState('ageDistributionChart', 'Grafik usia akan muncul setelah data tanggal lahir tersedia.');
                return;
            }

            new ApexCharts(document.querySelector('#ageDistributionChart'), {
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                },
                series: [{
                    name: 'Pendaftar',
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
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: (value) => `${value.toFixed(0)}`,
                    },
                },
                colors: ['#22c1dc', '#f8b45c', '#64c987'],
                dataLabels: {
                    enabled: true,
                    formatter: (value) => `${value}`,
                    offsetY: -18,
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: ['#0f172a'],
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '52%',
                        distributed: true,
                        dataLabels: {
                            position: 'top',
                        },
                    },
                },
                legend: {
                    show: false,
                },
                tooltip: {
                    y: {
                        formatter: (value) => `${value} pendaftar`,
                    },
                },
                grid: {
                    borderColor: '#e2e8f0',
                },
            }).render();
        }

        function renderCategoricalBarChart(elementId, chartData, emptyMessage) {
            const { labels, series } = chartData;

            if (!hasNonZeroSeries(series)) {
                renderEmptyState(elementId, emptyMessage);
                return;
            }

            new ApexCharts(document.querySelector(`#${elementId}`), {
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                },
                series: [{
                    name: 'Data',
                    data: series,
                }],
                xaxis: {
                    categories: labels,
                    labels: {
                        rotate: -12,
                        trim: true,
                        style: {
                            fontFamily: 'Poppins, sans-serif',
                            fontSize: '11px',
                        },
                    },
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: (value) => `${value.toFixed(0)}`,
                    },
                },
                colors: ['#22c1dc', '#f8b45c', '#64c987', '#fb7185', '#8b5cf6', '#38bdf8'],
                dataLabels: {
                    enabled: true,
                    formatter: (value) => `${value}`,
                    offsetY: -18,
                    style: {
                        fontFamily: 'Poppins, sans-serif',
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: ['#0f172a'],
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '52%',
                        distributed: true,
                        dataLabels: {
                            position: 'top',
                        },
                    },
                },
                legend: {
                    show: false,
                },
                tooltip: {
                    y: {
                        formatter: (value) => `${value} data`,
                    },
                },
                grid: {
                    borderColor: '#e2e8f0',
                },
            }).render();
        }

        function renderMonthlyTrendChart() {
            const { labels, series } = dashboardChartData.monthlyTrend;

            if (!hasNonZeroSeries(series)) {
                renderEmptyState('monthlyTrendChart', 'Tren pendaftar bulanan akan muncul setelah data pendaftar tersedia.');
                return;
            }

            new ApexCharts(document.querySelector('#monthlyTrendChart'), {
                chart: {
                    type: 'line',
                    height: 320,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                },
                series: [{
                    name: 'Pendaftar',
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
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        formatter: (value) => `${value.toFixed(0)}`,
                    },
                },
                colors: ['#d946ef'],
                stroke: {
                    curve: 'smooth',
                    width: 4,
                },
                markers: {
                    size: 5,
                    strokeWidth: 3,
                    strokeColors: '#d946ef',
                    colors: ['#ffffff'],
                    hover: {
                        size: 7,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    type: 'solid',
                    opacity: 1,
                },
                tooltip: {
                    y: {
                        formatter: (value) => `${value} pendaftar`,
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
            const manualInputToggle = document.getElementById('toggleAnnualManualInput');
            const manualInputForm = document.getElementById('annualManualInputForm');

            if (manualInputToggle && manualInputForm) {
                const setManualInputVisibility = (shouldShow) => {
                    manualInputForm.classList.toggle('hidden', !shouldShow);
                    manualInputToggle.setAttribute('aria-expanded', shouldShow ? 'true' : 'false');
                    manualInputToggle.classList.toggle('border-sky-200', shouldShow);
                    manualInputToggle.classList.toggle('bg-sky-50', shouldShow);
                    manualInputToggle.classList.toggle('text-sky-700', shouldShow);

                    if (shouldShow) {
                        manualInputForm.querySelector('input')?.focus();
                    }
                };

                setManualInputVisibility(!manualInputForm.classList.contains('hidden'));

                manualInputToggle.addEventListener('click', () => {
                    setManualInputVisibility(manualInputForm.classList.contains('hidden'));
                });
            }

            if (typeof ApexCharts === 'undefined') {
                renderEmptyState('annualRegistrationChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('genderChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('ageDistributionChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('motherJobsChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('fatherJobsChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('parentIncomesChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('parentEducationsChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('applicantStatusChart', 'Library chart tidak berhasil dimuat.');
                renderEmptyState('monthlyTrendChart', 'Library chart tidak berhasil dimuat.');
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

            renderAnnualRegistrationChart();
            renderAgeDistributionChart();
            renderCategoricalBarChart('motherJobsChart', dashboardChartData.motherJobs, 'Grafik pekerjaan ibu akan muncul setelah biodata orang tua terisi.');
            renderCategoricalBarChart('fatherJobsChart', dashboardChartData.fatherJobs, 'Grafik pekerjaan ayah akan muncul setelah biodata orang tua terisi.');
            renderCategoricalBarChart('parentIncomesChart', dashboardChartData.parentIncomes, 'Grafik penghasilan orang tua akan muncul setelah biodata orang tua terisi.');
            renderCategoricalBarChart('parentEducationsChart', dashboardChartData.parentEducations, 'Grafik pendidikan orang tua akan muncul setelah biodata orang tua terisi.');
            renderDonutChart(
                'applicantStatusChart',
                dashboardChartData.applicantStatus.labels,
                dashboardChartData.applicantStatus.series,
                ['#2563eb', '#8b5cf6', '#64748b']
            );
            renderMonthlyTrendChart();
            renderClassQuotaChart();
            renderTreemapChart();
        });
    </script>
</x-dynamic-component>
