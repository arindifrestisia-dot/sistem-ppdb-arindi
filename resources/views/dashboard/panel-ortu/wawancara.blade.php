<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Wawancara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .calendar-grid { grid-template-columns: repeat(7, minmax(0, 1fr)); }
    </style>
</head>
<body class="bg-[#cfe0f8] text-slate-900">
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'wawancara')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-4 py-6 md:px-8">
                <div class="mx-auto max-w-7xl">
                    @if (session('status'))
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (! $isInterviewAvailable)
                        <section class="rounded-[1.5rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="rounded-[1.25rem] border border-amber-200 bg-amber-50 px-6 py-8 text-center">
                                <h1 class="text-2xl font-bold text-amber-800">Jadwal wawancara belum tersedia</h1>
                                <p class="mt-3 text-sm text-amber-700">Jadwal wawancara akan tampil setelah orang tua menyelesaikan pembelian formulir dan mengirim formulir pendaftaran.</p>
                                <a href="{{ route('data-diri') }}" class="mt-5 inline-flex rounded-full bg-amber-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-amber-700">
                                    Lengkapi Formulir
                                </a>
                            </div>
                        </section>
                    @elseif ($registration?->interview_selected_at)
                        <section class="rounded-[1.5rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-sky-600">Jadwal Terpilih</p>
                                    <h1 class="mt-2 text-2xl font-extrabold text-blue-950 md:text-4xl">{{ $registration->full_name }}</h1>
                                    <p class="mt-2 text-sm text-slate-500">Tanggal wawancara yang sudah dipilih tidak dapat diganti lagi.</p>
                                </div>
                                <div class="rounded-[1rem] bg-blue-950 px-6 py-5 text-white shadow-lg">
                                    <p class="text-sm text-sky-100">Dipilih pada</p>
                                    <p class="mt-2 text-lg font-bold">{{ optional($registration->interview_selected_at)->format('d-m-Y H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Hari</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ $registration->interview_day_name ?: '-' }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Tanggal</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ optional($registration->interview_date)->translatedFormat('d F Y') ?: '-' }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Jam</p>
                                    <p class="mt-2 text-lg font-bold leading-7 text-slate-800">Silahkan datang ke sekolah RA FADHILAH pada jam 08.00 - 13.00</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Ruangan</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">RUANGAN TU</p>
                                </div>
                            </div>
                        </section>
                    @else
                        <section
                            class="rounded-[1.5rem] bg-white p-5 shadow-[0_18px_45px_rgba(15,23,42,0.14)] ring-1 ring-blue-100 md:p-8"
                            x-data="interviewCalendar()"
                            x-init="init()"
                        >
                            <div class="flex flex-col gap-5 border-b border-slate-200 pb-5 lg:flex-row lg:items-end lg:justify-between">
                                <div>
                                    <p class="text-xs font-extrabold uppercase tracking-[0.35em] text-sky-600">Jadwal Wawancara</p>
                                    <h1 class="mt-2 text-2xl font-extrabold text-blue-950 md:text-3xl">Pilih Tanggal Wawancara</h1>
                                    <p class="mt-1 text-sm text-slate-500">Pilih tanggal yang tersedia, lalu klik tombol konfirmasi jadwal.</p>
                                </div>

                                <label class="block w-full max-w-xs">
                                    <span class="text-xs font-bold uppercase tracking-[0.28em] text-slate-500">Bulan</span>
                                    <select
                                        x-model="selectedMonth"
                                        @change="selectFirstAvailableDate()"
                                        class="mt-2 h-12 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-blue-950 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    >
                                        <template x-for="month in months" :key="month.key">
                                            <option :value="month.key" x-text="month.label"></option>
                                        </template>
                                    </select>
                                </label>
                            </div>

                            <form action="{{ route('wawancara.update') }}" method="POST" class="mt-6">
                                @csrf
                                <input type="hidden" name="interview_schedule_key" :value="selectedSessionKey">

                                <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_360px]">
                                    <div class="rounded-[1.25rem] border border-slate-200 bg-slate-50 p-4 md:p-6">
                                        <div class="grid calendar-grid gap-2 text-center text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-500 md:gap-3">
                                            <template x-for="day in dayLabels" :key="day">
                                                <div class="py-2" x-text="day"></div>
                                            </template>
                                        </div>

                                        <div class="mt-2 grid calendar-grid gap-2 md:gap-3">
                                            <template x-for="cell in calendarDays" :key="cell.key">
                                                <button
                                                    type="button"
                                                    @click="cell.available && selectDate(cell.date)"
                                                    :disabled="! cell.available"
                                                    class="min-h-[4.25rem] rounded-xl border text-center transition md:min-h-[5rem]"
                                                    :class="dayClass(cell)"
                                                >
                                                    <span class="block text-sm font-extrabold" x-text="cell.day"></span>
                                                    <span
                                                        x-show="cell.available"
                                                        class="mx-auto mt-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold"
                                                        :class="selectedDate === cell.date ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-600'"
                                                    >
                                                    tersedia
                                                    </span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <aside class="rounded-[1.25rem] border border-slate-200 bg-white p-4 shadow-[0_16px_34px_rgba(15,23,42,0.10)] md:p-5">
                                        <p class="text-xs font-extrabold uppercase tracking-[0.32em] text-sky-600">Tanggal Dipilih</p>
                                        <h2 class="mt-2 text-2xl font-extrabold text-blue-950" x-text="selectedDateLabel"></h2>
                                        <p class="mt-1 text-sm text-slate-500" x-text="selectedDateSummary"></p>

                                        <div x-show="selectedSession" class="mt-5 space-y-3">
                                            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                                                <p class="text-xs font-extrabold uppercase tracking-[0.28em] text-blue-600">Jam</p>
                                                <p class="mt-2 text-base font-extrabold leading-7 text-blue-950" x-text="selectedSession?.time"></p>
                                            </div>
                                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                                <p class="text-xs font-extrabold uppercase tracking-[0.28em] text-slate-500">Ruangan</p>
                                                <p class="mt-2 text-lg font-extrabold text-blue-950" x-text="selectedSession?.room"></p>
                                            </div>
                                        </div>

                                        <div class="mt-5 rounded-xl bg-blue-50 p-4">
                                            <p class="text-xs font-extrabold uppercase tracking-[0.28em] text-blue-600">Pilihan Anda</p>
                                            <p class="mt-2 text-sm font-bold text-blue-950" x-text="selectedChoiceText"></p>
                                        </div>

                                        <button
                                            type="submit"
                                            class="mt-4 h-12 w-full rounded-xl px-5 text-sm font-extrabold uppercase tracking-wide text-white transition"
                                            :class="selectedSessionKey ? 'bg-blue-700 shadow-[0_12px_24px_rgba(37,99,235,0.22)] hover:bg-blue-800' : 'cursor-not-allowed bg-slate-300'"
                                            :disabled="! selectedSessionKey"
                                        >
                                            Konfirmasi Jadwal
                                        </button>
                                    </aside>
                                </div>
                            </form>
                        </section>
                    @endif
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>

    @if ($isInterviewAvailable && ! $registration?->interview_selected_at)
        <script>
            function interviewCalendar() {
                return {
                    months: @json($scheduleCalendar['months']),
                    slotsByDate: @json($scheduleCalendar['slotsByDate']),
                    selectedMonth: '',
                    selectedDate: '',
                    selectedSessionKey: '',
                    selectedSession: null,
                    dayLabels: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    init() {
                        this.selectedMonth = this.months[0]?.key || '';
                        this.selectFirstAvailableDate();
                    },
                    get calendarDays() {
                        if (! this.selectedMonth) {
                            return [];
                        }

                        const [year, month] = this.selectedMonth.split('-').map(Number);
                        const firstDay = new Date(year, month - 1, 1);
                        const lastDay = new Date(year, month, 0);
                        const cells = [];

                        for (let i = 0; i < firstDay.getDay(); i++) {
                            cells.push({ key: `blank-${i}`, day: '', date: '', available: false, blank: true });
                        }

                        for (let day = 1; day <= lastDay.getDate(); day++) {
                            const date = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                            cells.push({
                                key: date,
                                day,
                                date,
                                available: Boolean(this.slotsByDate[date]),
                                blank: false,
                            });
                        }

                        return cells;
                    },
                    get selectedSlots() {
                        return this.slotsByDate[this.selectedDate] || [];
                    },
                    get selectedDateLabel() {
                        return this.selectedSlots[0]?.formatted_date || 'Pilih tanggal';
                    },
                    get selectedDateSummary() {
                        return this.selectedSlots.length
                            ? `${this.selectedSlots[0].day_name}, tanggal tersedia untuk wawancara`
                            : 'Klik tanggal pada kalender.';
                    },
                    get selectedChoiceText() {
                        if (! this.selectedSession) {
                            return 'Belum ada tanggal dipilih.';
                        }

                        return `${this.selectedSession.formatted_date} - ${this.selectedSession.room}`;
                    },
                    slotCount(date) {
                        return (this.slotsByDate[date] || []).length;
                    },
                    selectDate(date) {
                        if (! this.slotsByDate[date]) {
                            return;
                        }

                        this.selectedDate = date;
                        this.selectedSession = this.slotsByDate[date][0];
                        this.selectedSessionKey = this.selectedSession.key;
                    },
                    selectFirstAvailableDate() {
                        const date = Object.keys(this.slotsByDate).find((item) => item.startsWith(this.selectedMonth));
                        this.selectedDate = date || '';
                        this.selectedSession = date ? this.slotsByDate[date][0] : null;
                        this.selectedSessionKey = this.selectedSession?.key || '';
                    },
                    dayClass(cell) {
                        if (cell.blank) {
                            return 'border-transparent bg-transparent';
                        }

                        if (! cell.available) {
                            return 'cursor-not-allowed border-slate-100 bg-white text-slate-300';
                        }

                        if (this.selectedDate === cell.date) {
                            return 'border-blue-800 bg-blue-800 text-white shadow-[0_12px_24px_rgba(30,64,175,0.22)]';
                        }

                        return 'border-slate-200 bg-white text-blue-950 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-sm';
                    },
                };
            }
        </script>
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endif
</body>
</html>
