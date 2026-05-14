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
    </style>
</head>
<body class="bg-[#cfe0f8] text-slate-900">
    <div class="flex min-h-screen flex-col md:flex-row">
        @php($activeMenu = 'wawancara')
        @include('dashboard.panel-ortu.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('dashboard.panel-ortu.partials.topbar')

            <main class="flex-1 px-5 py-6 md:px-8">
                <div class="mx-auto max-w-6xl">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h1 class="text-3xl font-extrabold text-blue-950 md:text-5xl">Pilih Jadwal Wawancara</h1>
                            <p class="mt-2 text-lg text-slate-500">Silakan pilih jadwal terbaik untuk ananda setelah pembelian formulir dan formulir pendaftaran berhasil dikirim.</p>
                        </div>
                        @if ($isInterviewAvailable && $registration?->interview_selected_at)
                            <span class="inline-flex rounded-2xl bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-700">Jadwal Sudah Dipilih</span>
                        @elseif ($isInterviewAvailable)
                            <span class="inline-flex rounded-2xl bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">Menunggu Pilihan Jadwal</span>
                        @else
                            <span class="inline-flex rounded-2xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Jadwal Belum Tersedia</span>
                        @endif
                    </div>

                    @if (session('status'))
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($isInterviewAvailable && $registration?->interview_selected_at)
                        <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-600">Jadwal Terpilih</p>
                                    <h2 class="mt-2 text-2xl font-bold text-blue-950">{{ $registration->full_name }}</h2>
                                    <p class="mt-2 text-slate-500">Berikut jadwal wawancara yang sudah dipilih untuk ananda.</p>
                                </div>
                                <div class="rounded-[1.75rem] bg-blue-950 px-6 py-5 text-white shadow-lg">
                                    <p class="text-sm text-sky-100">Dipilih pada</p>
                                    <p class="mt-2 text-lg font-bold">{{ optional($registration->interview_selected_at)->format('d-m-Y H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-2xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Hari</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ $registration->interview_day_name ?: '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Tanggal</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ optional($registration->interview_date)->translatedFormat('d F Y') ?: '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Jam</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ $registration->interview_time ?: '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-5">
                                    <p class="text-sm font-medium text-slate-500">Ruangan</p>
                                    <p class="mt-2 text-lg font-bold text-slate-800">{{ $registration->interview_room ?: '-' }}</p>
                                </div>
                            </div>
                        </section>
                    @endif

                    <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-[0_14px_30px_rgba(15,23,42,0.12)] ring-1 ring-blue-100 md:p-8">
                        @if (! $isInterviewAvailable)
                            <div class="rounded-[1.75rem] border border-amber-200 bg-amber-50 px-6 py-8 text-center">
                                <h2 class="text-2xl font-bold text-amber-800">Jadwal wawancara belum tersedia</h2>
                                <p class="mt-3 text-sm text-amber-700">Jadwal wawancara akan tampil setelah orang tua menyelesaikan pembelian formulir dan mengirim formulir pendaftaran.</p>
                                <a href="{{ route('data-diri') }}" class="mt-5 inline-flex rounded-full bg-amber-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-amber-700">
                                    Lengkapi Formulir
                                </a>
                            </div>
                        @else
                            <div class="border-b border-slate-200 pb-4">
                                <h2 class="text-2xl font-bold text-blue-950">Daftar Slot Wawancara</h2>
                                <p class="mt-1 text-sm text-slate-500">Pilih satu sesi yang tersedia. Setelah jadwal disimpan, pilihan sesi tidak dapat diubah lagi.</p>
                            </div>

                            @if ($registration?->interview_selected_at)
                                <div class="mt-6 rounded-[1.75rem] border border-emerald-200 bg-emerald-50 px-6 py-6">
                                    <h3 class="text-xl font-bold text-emerald-800">Pilihan sesi sudah final</h3>
                                    <p class="mt-3 text-sm leading-7 text-emerald-700">Sesi wawancara yang sudah dipilih tidak dapat diganti lagi. Jika ada kebutuhan khusus, silakan hubungi panitia PPDB.</p>
                                </div>
                            @else
                                <form action="{{ route('wawancara.update') }}" method="POST" class="mt-6">
                                    @csrf

                                    <div class="grid gap-5 lg:grid-cols-2 xl:grid-cols-3">
                                        @foreach ($scheduleOptions as $option)
                                            @php($isSelected = old('interview_schedule_key', $registration->interview_schedule_key) === $option['key'])
                                            <label class="group cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="interview_schedule_key"
                                                    value="{{ $option['key'] }}"
                                                    class="peer sr-only"
                                                    @checked($isSelected)
                                                >

                                                <div class="h-full rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 transition duration-200 peer-checked:border-blue-700 peer-checked:bg-blue-950 peer-checked:text-white group-hover:-translate-y-1 group-hover:shadow-[0_18px_30px_rgba(15,23,42,0.12)]">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div>
                                                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-600 peer-checked:text-sky-100">{{ $option['session_label'] }}</p>
                                                            <h3 class="mt-2 text-xl font-bold text-blue-950 peer-checked:text-white">{{ $option['day_name'] }}</h3>
                                                        </div>
                                                    </div>

                                                    <div class="mt-5 space-y-3 text-sm">
                                                        <div class="rounded-2xl bg-white/80 px-4 py-3 text-slate-700 ring-1 ring-slate-200 peer-checked:bg-white/10 peer-checked:text-slate-100 peer-checked:ring-white/15">
                                                            <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 peer-checked:text-sky-100">Tanggal</span>
                                                            <span class="mt-1 block text-base font-semibold">{{ $option['formatted_date'] }}</span>
                                                        </div>
                                                        <div class="rounded-2xl bg-white/80 px-4 py-3 text-slate-700 ring-1 ring-slate-200 peer-checked:bg-white/10 peer-checked:text-slate-100 peer-checked:ring-white/15">
                                                            <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 peer-checked:text-sky-100">Jam</span>
                                                            <span class="mt-1 block text-base font-semibold">{{ $option['time'] }}</span>
                                                        </div>
                                                        <div class="rounded-2xl bg-white/80 px-4 py-3 text-slate-700 ring-1 ring-slate-200 peer-checked:bg-white/10 peer-checked:text-slate-100 peer-checked:ring-white/15">
                                                            <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 peer-checked:text-sky-100">Ruangan</span>
                                                            <span class="mt-1 block text-base font-semibold">{{ $option['room'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-8 flex justify-end">
                                        <button
                                            type="submit"
                                            class="rounded-full bg-gradient-to-r from-indigo-500 to-blue-800 px-8 py-4 text-base font-extrabold uppercase tracking-wide text-white shadow-[0_18px_35px_rgba(37,99,235,0.25)] transition hover:opacity-95"
                                        >
                                            Simpan Jadwal Wawancara
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endif
                    </section>
                </div>
            </main>

            @include('dashboard.panel-ortu.partials.footer')
        </div>
    </div>
</body>
</html>
