@extends('layouts.app')

@section('title', 'Raudhatul Athfal Fadhilah | Profil Sekolah')

@section('content')
@php
    $heroImage = $informationItems->first()?->image_path ? asset('storage/' . $informationItems->first()->image_path) : asset('image/poster-tk.jpg');
    $heroSlides = [
        [
            'eyebrow' => 'Pendaftaran Siswa Baru',
            'title' => 'Open Registration',
            'subtitle' => 'Raudhatul Athfal Fadhilah',
            'period' => 'Tahun Ajaran 2026/2027',
            'tagline' => 'Beradab, ceria, mandiri, dan berakhlak Islami.',
            'button' => 'Register Now',
            'secondary' => 'Lihat Profil',
            'image' => asset('image/poster-tk.jpg'),
            'theme' => 'blue',
        ],
        [
            'eyebrow' => 'Selamat Datang',
            'title' => 'Profil Sekolah',
            'subtitle' => 'RA Fadhilah Pekanbaru',
            'period' => 'Sekolah Anak Usia Dini Islami',
            'tagline' => 'Membangun generasi cerdas, mandiri, dan berkarakter melalui pembelajaran yang hangat dan menyenangkan.',
            'button' => 'Jelajahi Sekolah',
            'secondary' => 'Tenaga Pendidik',
            'image' => asset('image/berita1.png'),
            'theme' => 'pink',
        ],
        [
            'eyebrow' => 'Program Unggulan',
            'title' => 'Belajar Sambil Bermain',
            'subtitle' => 'Kreatif, Aktif, dan Menyenangkan',
            'period' => 'Dengan Pembiasaan Adab dan Ibadah',
            'tagline' => 'Kegiatan tematik, literasi, motorik, dan pembinaan akhlak dirancang untuk mendukung tumbuh kembang anak secara utuh.',
            'button' => 'Lihat Kegiatan',
            'secondary' => 'Portal PPDB',
            'image' => asset('image/berita2.png'),
            'theme' => 'emerald',
        ],
    ];

    $newsItems = $informationItems->isNotEmpty()
        ? $informationItems->take(6)->map(fn ($item) => [
            'title' => $item->title,
            'date' => optional($item->published_at)->translatedFormat('d F Y') ?? 'Informasi Sekolah',
            'excerpt' => $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->content), 135),
            'image' => $item->image_path ? asset('storage/' . $item->image_path) : asset('image/berita.png'),
            'url' => route('blog.berita.show', $item),
        ])
        : collect([
            ['title' => 'Puncak tema dan pentas kreativitas peserta didik RA Fadhilah', 'date' => '12 Februari 2026', 'excerpt' => 'Kegiatan pentas menjadi ruang bagi anak untuk tampil percaya diri, ceria, dan berani mengekspresikan karya di depan orang tua serta guru.', 'image' => asset('image/berita.png'), 'url' => route('blog.berita')],
            ['title' => 'Pembiasaan ibadah harian dan adab Islami di lingkungan sekolah', 'date' => '14 Januari 2026', 'excerpt' => 'Rutinitas doa, adab keseharian, dan pembelajaran agama dibangun secara hangat agar anak tumbuh dekat dengan nilai-nilai Islami.', 'image' => asset('image/berita1.png'), 'url' => route('blog.berita')],
            ['title' => 'Kegiatan luar kelas untuk menumbuhkan kemandirian anak', 'date' => '20 Desember 2025', 'excerpt' => 'Belajar tidak hanya di kelas. Anak diajak bereksplorasi melalui kegiatan tematik dan pengalaman langsung yang menyenangkan.', 'image' => asset('image/berita2.png'), 'url' => route('blog.berita')],
            ['title' => 'Kolaborasi sekolah dan keluarga dalam proses tumbuh kembang', 'date' => '8 Desember 2025', 'excerpt' => 'Komunikasi aktif antara guru dan orang tua membantu sekolah menyiapkan layanan yang lebih personal dan dekat dengan kebutuhan peserta didik.', 'image' => asset('image/berita.png'), 'url' => route('blog.berita')],
            ['title' => 'Peringatan hari besar Islam bersama keluarga besar sekolah', 'date' => '21 November 2025', 'excerpt' => 'Peringatan hari besar Islam menjadi momen pembelajaran yang menyenangkan dan sarat makna bagi seluruh peserta didik.', 'image' => asset('image/berita1.png'), 'url' => route('blog.berita')],
            ['title' => 'Program pembelajaran kreatif dan menyenangkan sepanjang semester', 'date' => '4 November 2025', 'excerpt' => 'Sekolah menghadirkan pendekatan belajar melalui bermain agar anak aktif, fokus, dan berkembang sesuai tahap usianya.', 'image' => asset('image/berita2.png'), 'url' => route('blog.berita')],
        ]);

    $galleryItems = $galleryItems->isNotEmpty()
        ? $galleryItems->map(fn ($item) => [
            'title' => $item->title,
            'image' => $item->image_path ? asset('storage/' . $item->image_path) : asset('image/berita.png'),
        ])
        : collect([
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/berita.png')],
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/berita1.png')],
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/berita2.png')],
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/poster-tk.jpg')],
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/berita1.png')],
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/berita.png')],
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/berita2.png')],
            ['title' => 'Galeri RA Fadhilah', 'image' => asset('image/poster-tk.jpg')],
        ]);

    $teacherItems = $teacherItems->isNotEmpty()
        ? $teacherItems->map(fn ($item) => [
            'name' => $item->title,
            'image' => $item->image_path ? asset('storage/' . $item->image_path) : asset('image/fotoguru.png'),
        ])
        : collect([
            ['name' => 'Guru Kelas A1', 'image' => asset('image/fotoguru.png')],
            ['name' => 'Guru Kelas A2', 'image' => asset('image/fotoguru.png')],
            ['name' => 'Guru Kelas B1', 'image' => asset('image/fotoguru.png')],
            ['name' => 'Guru Kelas B2', 'image' => asset('image/fotoguru.png')],
            ['name' => 'Guru Pendamping', 'image' => asset('image/fotoguru.png')],
        ]);

    $activityMenu = $activityItems->isNotEmpty()
        ? $activityItems->map(fn ($item) => [
            'title' => $item->title,
            'image' => $item->image_path ? asset('storage/' . $item->image_path) : asset('image/berita1.png'),
            'excerpt' => $item->excerpt,
        ])
        : collect([
            ['title' => 'Practical Life', 'image' => asset('image/berita1.png'), 'excerpt' => null],
            ['title' => 'Agama dan Ibadah', 'image' => asset('image/berita2.png'), 'excerpt' => null],
            ['title' => 'Literasi', 'image' => asset('image/berita.png'), 'excerpt' => null],
            ['title' => 'Motorik', 'image' => asset('image/poster-tk.jpg'), 'excerpt' => null],
        ]);

    $testimonials = collect([
        [
            'name' => 'Bunda Alleryk',
            'quote' => 'RA Fadhilah membantu anak kami tumbuh lebih percaya diri, nyaman belajar, dan terbiasa dengan pembiasaan adab serta ibadah sejak dini.',
        ],
        [
            'name' => 'Wali Murid RA Fadhilah',
            'quote' => 'Suasana sekolahnya hangat dan komunikatif. Guru-gurunya dekat dengan anak, dan kami sebagai orang tua merasa dilibatkan dalam proses pembelajaran.',
        ],
    ]);

    $footerNews = $newsItems->take(5);
    $calendarMonth = now()->startOfMonth();
    $calendarTitle = $calendarMonth->translatedFormat('F Y');
    $calendarLeadingEmptyDays = $calendarMonth->dayOfWeekIso - 1;
    $calendarCells = collect(array_fill(0, $calendarLeadingEmptyDays, null))
        ->concat(range(1, $calendarMonth->daysInMonth));
    $calendarTrailingEmptyDays = (7 - ($calendarCells->count() % 7)) % 7;
    $calendarCells = $calendarCells
        ->concat(array_fill(0, $calendarTrailingEmptyDays, null))
        ->values();
    $today = now();
@endphp

<div class="bg-white">
    <section
        x-data="heroPosterSlider({{ Js::from($heroSlides) }})"
        x-init="start()"
        class="relative overflow-hidden bg-[#f5f9ff] text-white"
    >
        <template x-for="(slide, index) in slides" :key="index">
            <div
                x-show="activeSlide === index"
                x-transition:enter="transition-opacity ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="relative"
                style="display: none;"
            >
                <div
                    class="absolute inset-0"
                    :class="{
                        'bg-[linear-gradient(90deg,#0c56a6_0%,#11559d_52%,#1b6eb9_100%)]': slide.theme === 'blue',
                        'bg-[linear-gradient(90deg,#f5a7df_0%,#efa1dd_44%,#f4c4e6_100%)]': slide.theme === 'pink',
                        'bg-[linear-gradient(90deg,#0d7b72_0%,#1a8b74_45%,#4ab39a_100%)]': slide.theme === 'emerald'
                    }"
                ></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.18),transparent_24%),radial-gradient(circle_at_bottom_right,rgba(255,255,255,0.12),transparent_20%)]"></div>
                <div class="absolute left-[-8%] top-0 h-full w-[60%] rounded-r-[46%] border-r-[26px] border-white/65 bg-white/12"></div>
                <div class="absolute right-[-12%] top-[-12%] h-[150%] w-[56%] rounded-l-[44%] border-l-[18px] border-white/60 bg-white/10"></div>
                <div class="absolute bottom-[-15%] right-[-2%] h-48 w-[60%] rounded-t-[100%] border-t-[20px] border-white/75 bg-white/15"></div>

                <div class="relative mx-auto grid min-h-[620px] max-w-[1260px] gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                    <div class="relative z-10 py-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/90 shadow-lg">
                                <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="h-11 w-11 object-contain">
                            </div>
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/90 shadow-lg">
                                <img src="{{ asset('image/logo_TK.png') }}" alt="Logo TK" class="h-11 w-11 object-contain">
                            </div>
                        </div>

                        <p
                            class="mt-8 text-sm font-black uppercase tracking-[0.28em]"
                            :class="slide.theme === 'pink' ? 'text-[#a91f73]' : 'text-[var(--brand-yellow)]'"
                            x-text="slide.eyebrow"
                        ></p>
                        <h1
                            class="mt-4 font-black uppercase leading-none drop-shadow-[0_4px_0_rgba(0,0,0,0.12)]"
                            :class="slide.theme === 'pink' ? 'text-[3.7rem] text-white sm:text-[5.4rem] lg:text-[6.4rem]' : 'text-[3.5rem] text-[var(--brand-yellow)] sm:text-[5rem] lg:text-[6rem]'"
                            x-text="slide.title"
                        ></h1>
                        <p
                            class="mt-4 max-w-3xl text-2xl font-black uppercase tracking-[0.06em] sm:text-4xl lg:text-5xl"
                            :class="slide.theme === 'pink' ? 'text-[#2e9ae8]' : 'text-white'"
                            x-text="slide.subtitle"
                        ></p>

                        <div
                            class="mt-8 inline-flex min-w-[290px] max-w-full rounded-full px-8 py-4 text-center text-xl font-black uppercase tracking-[0.18em] shadow-[0_16px_30px_rgba(0,0,0,0.14)] sm:text-2xl"
                            :class="slide.theme === 'pink' ? 'bg-[#4fa5e8] text-white' : 'bg-white text-[var(--brand-blue)]'"
                        >
                            <span x-text="slide.period"></span>
                        </div>

                        <p class="mt-8 max-w-3xl text-lg font-bold leading-9 text-white sm:text-2xl" x-text="slide.tagline"></p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a
                                x-show="slide.button"
                                :href="index === 1 ? '{{ url('/profile/sejarah') }}' : (index === 2 ? '{{ route('blog.kegiatan') }}' : '{{ route('ppdb.info') }}')"
                                class="inline-flex items-center justify-center bg-[var(--brand-yellow)] px-8 py-4 text-sm font-black uppercase tracking-[0.16em] text-[var(--brand-blue)] shadow-lg transition hover:bg-[#ffd857]"
                                x-text="slide.button"
                            ></a>
                            <a
                                x-show="slide.secondary"
                                :href="index === 1 ? '{{ url('/profile/tenaga-pendidik') }}' : (index === 2 ? '{{ route('ppdb.info') }}' : '{{ url('/profile/sejarah') }}')"
                                class="inline-flex items-center justify-center border border-white/50 px-8 py-4 text-sm font-black uppercase tracking-[0.16em] text-white transition hover:bg-white/10"
                                x-text="slide.secondary"
                            ></a>
                        </div>
                    </div>

                    <div class="relative z-10">
                        <div class="relative mx-auto max-w-[590px]">
                            <div class="absolute -left-6 top-10 hidden h-24 w-24 rounded-full border-[10px] border-white/35 lg:block"></div>
                            <div class="absolute -right-4 bottom-20 hidden h-16 w-16 rounded-full bg-white/20 lg:block"></div>
                            <div class="absolute inset-y-4 right-0 w-[86%] rounded-[2rem] bg-white/10 blur-[2px]"></div>
                            <img :src="slide.image" :alt="slide.title" class="relative h-[340px] w-full rounded-[2rem] border-[6px] border-white/25 object-cover shadow-[0_30px_70px_rgba(0,0,0,0.2)] sm:h-[430px] lg:h-[470px]">

                            <div class="absolute bottom-5 left-5 right-5 rounded-[1.6rem] bg-white/16 px-5 py-4 backdrop-blur-md">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.26em] text-white/80">RA Fadhilah</p>
                                        <p class="mt-2 text-lg font-black uppercase text-white sm:text-2xl">Profil & PPDB Sekolah</p>
                                    </div>
                                    <span class="hidden rounded-full bg-white/90 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-[var(--brand-blue)] sm:inline-flex">
                                        Slide <span class="ml-1" x-text="activeSlide + 1"></span>/3
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="prev()"
                        class="absolute left-3 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-[var(--brand-blue)] shadow-lg transition hover:bg-white sm:left-5"
                        aria-label="Slide sebelumnya"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <button
                        type="button"
                        @click="next()"
                        class="absolute right-3 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 text-[var(--brand-blue)] shadow-lg transition hover:bg-white sm:right-5"
                        aria-label="Slide berikutnya"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>

        <div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 gap-3 sm:bottom-6">
            <template x-for="(slide, index) in slides" :key="'dot-' + index">
                <button
                    type="button"
                    @click="goTo(index)"
                    class="h-3 rounded-full transition-all duration-300"
                    :class="activeSlide === index ? 'w-10 bg-[var(--brand-yellow)]' : 'w-3 bg-white/70'"
                    :aria-label="'Pilih slide ' + (index + 1)"
                ></button>
            </template>
        </div>

        <div class="relative bg-white text-slate-700">
            <div class="mx-auto grid max-w-[1260px] gap-4 px-4 py-4 sm:px-6 md:grid-cols-4">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded bg-[var(--brand-blue)]/10"></div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Alamat</p>
                        <p class="text-sm font-semibold">Jl. Muhajirin, Pekanbaru</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded bg-[var(--brand-blue)]/10"></div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Phone</p>
                        <p class="text-sm font-semibold">0821 6207 736</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded bg-[var(--brand-blue)]/10"></div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">WhatsApp</p>
                        <p class="text-sm font-semibold">0822 8681 7315</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded bg-[var(--brand-blue)]/10"></div>
                    <div>
                        <p class="text-xs font-bold uppercase text-slate-400">Website</p>
                        <p class="text-sm font-semibold">rafadhilah.sch.id</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden bg-[linear-gradient(180deg,rgba(12,73,134,0.97),rgba(12,73,134,0.9))] py-16 text-center text-white">
        <div class="absolute inset-0 opacity-15" style="background-image: url('{{ asset('image/poster-tk.jpg') }}'); background-size: cover; background-position: center;"></div>
        <div class="relative mx-auto max-w-[1260px] px-4 sm:px-6">
            <h2 class="text-3xl font-black uppercase sm:text-5xl">Beradab dan Berkemajuan</h2>
            <a href="{{ route('ppdb.info') }}" class="mt-8 inline-flex items-center justify-center bg-[var(--brand-yellow)] px-10 py-4 text-lg font-black text-white shadow-lg">
                Penerimaan Murid Baru
            </a>
        </div>
    </section>

    <section class="mx-auto max-w-[1260px] px-4 py-16 sm:px-6">
        <h2 class="section-title">Berita Terbaru</h2>
        <div class="section-accent"></div>

        <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($newsItems as $item)
                <article class="overflow-hidden border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="h-56 w-full object-cover">
                    <div class="p-6">
                        <h3 class="min-h-[72px] text-2xl font-black uppercase leading-8 text-slate-800">{{ $item['title'] }}</h3>
                        <div class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-400">
                            <span>{{ $item['date'] }}</span>
                            <span>Admin RA</span>
                            <span>Views</span>
                        </div>
                        <p class="mt-4 text-sm leading-8 text-slate-600">{{ $item['excerpt'] }}</p>
                        <a href="{{ $item['url'] }}" class="mt-6 inline-flex items-center justify-center bg-[var(--brand-yellow)] px-5 py-3 text-sm font-black text-white">
                            Read More
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-[1260px] px-4 py-16 sm:px-6">
            <h2 class="section-title">Gallery</h2>
            <div class="section-accent"></div>
            <p class="mt-4 text-center text-sm text-slate-500">Galeri kegiatan di sekolah kami</p>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($galleryItems as $image)
                    <div class="overflow-hidden bg-slate-100">
                        <img src="{{ $image['image'] }}" alt="{{ $image['title'] }}" class="h-64 w-full object-cover transition duration-500 hover:scale-105">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-[1260px] px-4 py-16 sm:px-6">
        <h2 class="section-title">Guru Pengajar</h2>
        <div class="section-accent"></div>

        <div class="mt-14 grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            @foreach ($teacherItems as $teacher)
                <article class="text-center">
                    <div class="mx-auto flex h-52 items-end justify-center overflow-hidden">
                        <img src="{{ $teacher['image'] }}" alt="{{ $teacher['name'] }}" class="h-full object-contain">
                    </div>
                    <h3 class="mt-4 text-2xl font-black text-slate-800">{{ $teacher['name'] }}</h3>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-white">
        <div
            x-data="activitySlider({{ $activityMenu->count() }})"
            x-init="init()"
            @resize.window="updatePerView()"
            @mouseenter="stop()"
            @mouseleave="start()"
            class="mx-auto max-w-[1260px] px-4 py-16 sm:px-6"
        >
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div class="flex-1">
                    <h2 class="section-title">Kegiatanku</h2>
                    <div class="section-accent"></div>
                </div>

                <div class="flex justify-center gap-3 sm:justify-end" x-show="canSlide()" style="display: none;">
                    <button
                        type="button"
                        @click="prev()"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--brand-blue)] shadow-sm transition hover:border-[var(--brand-yellow)] hover:bg-[var(--brand-yellow)] hover:text-white"
                        aria-label="Kegiatan sebelumnya"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="next()"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-[var(--brand-blue)] shadow-sm transition hover:border-[var(--brand-yellow)] hover:bg-[var(--brand-yellow)] hover:text-white"
                        aria-label="Kegiatan berikutnya"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mt-12 overflow-hidden">
                <div
                    class="flex transition-transform duration-500 ease-out"
                    :style="`transform: translateX(-${activeIndex * (100 / perView)}%);`"
                >
                    @foreach ($activityMenu as $item)
                        <article class="shrink-0 basis-full px-0 text-center sm:basis-1/2 sm:px-3 lg:basis-1/4">
                            <div class="overflow-hidden">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="h-72 w-full object-cover">
                            </div>
                            <div class="-mt-4 mx-6 bg-white px-4 py-4 shadow-md">
                                <h3 class="text-2xl font-black text-slate-800">{{ $item['title'] }}</h3>
                                @if ($item['excerpt'])
                                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ \Illuminate\Support\Str::limit($item['excerpt'], 90) }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex justify-center gap-2" x-show="canSlide()" style="display: none;">
                <template x-for="index in totalPages()" :key="index">
                    <button
                        type="button"
                        @click="goToPage(index - 1)"
                        class="h-2.5 rounded-full transition-all"
                        :class="currentPage() === index - 1 ? 'w-8 bg-[var(--brand-yellow)]' : 'w-2.5 bg-slate-300 hover:bg-slate-400'"
                        :aria-label="`Lihat slide kegiatan ${index}`"
                    ></button>
                </template>
            </div>
        </div>
    </section>

    <section class="mt-10 bg-[var(--brand-blue)] py-16 text-white">
        <div class="mx-auto max-w-[1260px] px-4 sm:px-6">
            <h2 class="text-center text-4xl font-black uppercase">Testimoni</h2>
            <p class="mt-4 text-center text-sm font-semibold text-white/80">Apa kata mereka tentang RA Fadhilah?</p>

            <div class="mt-12 grid gap-6 lg:grid-cols-2">
                @foreach ($testimonials as $testimonial)
                    <article class="border border-white/20 px-6 py-8">
                        <p class="text-lg font-semibold leading-9 text-white/95">“{{ $testimonial['quote'] }}”</p>
                        <div class="mt-8 flex items-center gap-4">
                            <img src="{{ asset('image/contoh.fotobunda.png') }}" alt="{{ $testimonial['name'] }}" class="h-16 w-16 rounded-full border-2 border-[var(--brand-yellow)] object-cover">
                            <p class="text-lg font-black uppercase">{{ $testimonial['name'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-[1260px] px-4 py-10 sm:px-6">
            <div class="border-l-4 border-[var(--brand-yellow)] pl-4">
                <h2 class="text-2xl font-black uppercase text-[var(--brand-blue)]">Peta Lokasi</h2>
            </div>

            <div class="mt-6 overflow-hidden border border-slate-200">
                <iframe
                    src="https://www.google.com/maps?q=Raudhatul+Athfal+Fadhilah+Pekanbaru&output=embed"
                    width="100%"
                    height="430"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                ></iframe>
            </div>
        </div>
    </section>

    <footer class="bg-[#f7f4ec]">
        <div class="mx-auto grid max-w-[1260px] gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_1fr_320px]">
            <div>
                <div class="border-l-4 border-[var(--brand-yellow)] pl-4">
                    <h3 class="text-2xl font-black uppercase text-[var(--brand-blue)]">RA Fadhilah</h3>
                </div>
                <p class="mt-6 text-base leading-9 text-slate-700">
                    Disinilah generasi Islami meraih masa depan yang cerah. RA Fadhilah membangun keunggulan, karakter, dan keceriaan belajar untuk mewujudkan anak-anak muslim yang siap bertumbuh.
                </p>
                <p class="mt-6 text-base leading-9 text-slate-700">
                    <strong>Alamat:</strong> CCV9+42C, Jl. Muhajirin, Sidomulyo Barat, Kec. Tampan, Kota Pekanbaru, Riau 28294
                </p>
            </div>

            <div>
                <div class="border-l-4 border-[var(--brand-yellow)] pl-4">
                    <h3 class="text-2xl font-black uppercase text-[var(--brand-blue)]">Berita Sekolah</h3>
                </div>
                <ol class="mt-6 space-y-4 text-base leading-8 text-slate-700">
                    @foreach ($footerNews as $index => $item)
                        <li>{{ $index + 1 }}. {{ $item['title'] }}, {{ $item['date'] }}</li>
                    @endforeach
                </ol>
            </div>

            <div>
                <div class="border border-[#c89e4d] bg-[#d8b16a]/30 p-4">
                    <h3 class="text-center text-2xl font-black uppercase text-[#7a5b1d]">Calendar 2026</h3>
                    <div class="mt-4 bg-[#8b5d18] p-4 text-slate-800">
                        <div class="rounded-2xl border border-white/30 bg-white p-4 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-black uppercase tracking-[0.12em] text-[#7a5b1d]">Kalender Aktif</p>
                                <span class="rounded-full bg-[#f7f4ec] px-3 py-1 text-xs font-bold uppercase text-[#7a5b1d]">{{ $calendarTitle }}</span>
                            </div>

                            <div class="mt-4 grid grid-cols-7 gap-2 text-center text-xs">
                                @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                                    <div class="rounded-lg bg-[#f7f4ec] py-2 font-bold text-[#7a5b1d]">{{ $day }}</div>
                                @endforeach

                                @foreach ($calendarCells as $date)
                                    <div class="rounded-lg py-2 {{ $date && $today->isSameDay($calendarMonth->copy()->day($date)) ? 'bg-[#8b5d18] font-black text-white shadow-sm' : 'bg-white text-slate-700 ring-1 ring-slate-200' }}">
                                        {{ $date ?? '' }}
                                    </div>
                                @endforeach
                            </div>

                            <p class="mt-4 text-center text-xs font-semibold text-slate-500">
                                Tanggal hari ini ditandai lebih gelap.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[var(--brand-blue)] px-4 py-5 text-center text-sm font-semibold text-white">
              RAUDHATUL ATHFAL FADHILAH | Ditenagai oleh Bagian Humas dan IT RA Fadhilah © 2026
        </div>
    </footer>
</div>

<script>
    function heroPosterSlider(slides) {
        return {
            slides,
            activeSlide: 0,
            intervalId: null,
            start() {
                this.stop();
                this.intervalId = setInterval(() => {
                    this.next();
                }, 10000);
            },
            stop() {
                if (this.intervalId) {
                    clearInterval(this.intervalId);
                    this.intervalId = null;
                }
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                this.start();
            },
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                this.start();
            },
            goTo(index) {
                this.activeSlide = index;
                this.start();
            },
        };
    }

    function activitySlider(totalItems) {
        return {
            totalItems,
            activeIndex: 0,
            perView: 1,
            intervalId: null,
            init() {
                this.updatePerView();
                this.start();
            },
            updatePerView() {
                if (window.innerWidth >= 1024) {
                    this.perView = 4;
                } else if (window.innerWidth >= 640) {
                    this.perView = 2;
                } else {
                    this.perView = 1;
                }

                this.activeIndex = Math.min(this.activeIndex, this.maxIndex());
            },
            maxIndex() {
                return Math.max(this.totalItems - this.perView, 0);
            },
            canSlide() {
                return this.maxIndex() > 0;
            },
            totalPages() {
                return Math.ceil(this.totalItems / this.perView);
            },
            currentPage() {
                if (this.activeIndex >= this.maxIndex()) {
                    return this.totalPages() - 1;
                }

                return Math.floor(this.activeIndex / this.perView);
            },
            start() {
                this.stop();

                if (! this.canSlide()) {
                    return;
                }

                this.intervalId = setInterval(() => {
                    this.next();
                }, 6000);
            },
            stop() {
                if (this.intervalId) {
                    clearInterval(this.intervalId);
                    this.intervalId = null;
                }
            },
            next() {
                this.activeIndex = this.activeIndex >= this.maxIndex() ? 0 : this.activeIndex + 1;
            },
            prev() {
                this.activeIndex = this.activeIndex <= 0 ? this.maxIndex() : this.activeIndex - 1;
            },
            goToPage(page) {
                this.activeIndex = Math.min(page * this.perView, this.maxIndex());
            },
        };
    }
</script>
@endsection
