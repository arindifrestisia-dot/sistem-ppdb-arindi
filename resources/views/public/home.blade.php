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

    $profileImage = data_get($galleryItems->first(), 'image', asset('image/berita.png'));

    $teacherItems = $teacherItems->isNotEmpty()
        ? $teacherItems->map(fn ($item) => [
            'name' => $item->title,
            'image' => $item->image_path ? asset('storage/' . $item->image_path) : asset('image/fotoguru.png'),
            'url' => route('profile.tenaga-pendidik.show', $item),
        ])
        : collect([
            ['name' => 'Guru Kelas A1', 'image' => asset('image/fotoguru.png'), 'url' => null],
            ['name' => 'Guru Kelas A2', 'image' => asset('image/fotoguru.png'), 'url' => null],
            ['name' => 'Guru Kelas B1', 'image' => asset('image/fotoguru.png'), 'url' => null],
            ['name' => 'Guru Kelas B2', 'image' => asset('image/fotoguru.png'), 'url' => null],
            ['name' => 'Guru Pendamping', 'image' => asset('image/fotoguru.png'), 'url' => null],
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

    $testimonials = $testimonialItems->isNotEmpty()
        ? $testimonialItems->map(fn ($item) => [
            'name' => $item->title,
            'quote' => $item->content,
            'image' => $item->image_path ? asset('storage/' . $item->image_path) : asset('image/contoh.fotobunda.png'),
        ])
        : collect([
            [
                'name' => 'Bunda Alleryk',
                'quote' => 'RA Fadhilah membantu anak kami tumbuh lebih percaya diri, nyaman belajar, dan terbiasa dengan pembiasaan adab serta ibadah sejak dini.',
                'image' => asset('image/contoh.fotobunda.png'),
            ],
            [
                'name' => 'Wali Murid RA Fadhilah',
                'quote' => 'Suasana sekolahnya hangat dan komunikatif. Guru-gurunya dekat dengan anak, dan kami sebagai orang tua merasa dilibatkan dalam proses pembelajaran.',
                'image' => asset('image/contoh.fotobunda.png'),
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

                <div class="relative mx-auto grid h-[760px] max-w-[1260px] gap-6 overflow-hidden px-4 py-8 sm:h-[720px] sm:px-6 lg:h-[620px] lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                    <div class="relative z-10 py-2">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/90 shadow-lg">
                                <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="h-11 w-11 object-contain">
                            </div>
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/90 shadow-lg">
                                <img src="{{ asset('image/logo_TK.png') }}" alt="Logo TK" class="h-11 w-11 object-contain">
                            </div>
                        </div>

                        <p
                            class="mt-5 text-xs font-black uppercase tracking-[0.24em] sm:text-sm"
                            :class="slide.theme === 'pink' ? 'text-[#a91f73]' : 'text-[var(--brand-yellow)]'"
                            x-text="slide.eyebrow"
                        ></p>
                        <h1
                            class="mt-4 text-[2.75rem] font-black uppercase leading-none drop-shadow-[0_4px_0_rgba(0,0,0,0.12)] sm:text-[4.25rem] lg:text-[4.5rem]"
                            :class="slide.theme === 'pink' ? 'text-white' : 'text-[var(--brand-yellow)]'"
                            x-text="slide.title"
                        ></h1>
                        <p
                            class="mt-3 max-w-3xl text-xl font-black uppercase tracking-[0.06em] sm:text-3xl lg:text-4xl"
                            :class="slide.theme === 'pink' ? 'text-[#2e9ae8]' : 'text-white'"
                            x-text="slide.subtitle"
                        ></p>

                        <div
                            class="mt-5 inline-flex min-w-[250px] max-w-full rounded-full px-6 py-3 text-center text-base font-black uppercase tracking-[0.14em] shadow-[0_16px_30px_rgba(0,0,0,0.14)] sm:min-w-[290px] sm:text-xl"
                            :class="slide.theme === 'pink' ? 'bg-[#4fa5e8] text-white' : 'bg-white text-[var(--brand-blue)]'"
                        >
                            <span x-text="slide.period"></span>
                        </div>

                        <p class="mt-5 max-w-3xl text-sm font-bold leading-7 text-white sm:text-lg lg:text-xl" x-text="slide.tagline"></p>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <a
                                x-show="slide.button"
                                :href="index === 1 ? '{{ url('/profile/sejarah') }}' : (index === 2 ? '{{ route('blog.kegiatan') }}' : '{{ route('ppdb.info') }}')"
                                class="inline-flex items-center justify-center bg-[var(--brand-yellow)] px-6 py-3 text-xs font-black uppercase tracking-[0.14em] text-[var(--brand-blue)] shadow-lg transition hover:bg-[#ffd857] sm:px-8 sm:py-4 sm:text-sm"
                                x-text="slide.button"
                            ></a>
                            <a
                                x-show="slide.secondary"
                                :href="index === 1 ? '{{ url('/profile/tenaga-pendidik') }}' : (index === 2 ? '{{ route('ppdb.info') }}' : '{{ url('/profile/sejarah') }}')"
                                class="inline-flex items-center justify-center border border-white/50 px-6 py-3 text-xs font-black uppercase tracking-[0.14em] text-white transition hover:bg-white/10 sm:px-8 sm:py-4 sm:text-sm"
                                x-text="slide.secondary"
                            ></a>
                        </div>
                    </div>

                    <div class="relative z-10">
                        <div class="relative mx-auto max-w-[590px]">
                            <div class="absolute -left-6 top-10 hidden h-24 w-24 rounded-full border-[10px] border-white/35 lg:block"></div>
                            <div class="absolute -right-4 bottom-20 hidden h-16 w-16 rounded-full bg-white/20 lg:block"></div>
                            <div class="absolute inset-y-4 right-0 w-[86%] rounded-[2rem] bg-white/10 blur-[2px]"></div>
                            <img :src="slide.image" :alt="slide.title" class="relative h-[250px] w-full rounded-[2rem] border-[6px] border-white/25 bg-white/10 object-cover object-center shadow-[0_30px_70px_rgba(0,0,0,0.2)] sm:h-[320px] lg:h-[400px]">

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

    <section class="bg-[#f5f9ff] px-4 py-10 sm:px-6 sm:py-14">
        <div class="relative mx-auto max-w-[1260px] overflow-hidden rounded-3xl bg-white shadow-[0_12px_35px_rgba(15,79,140,0.12)]">
            <div class="absolute left-0 top-0 h-full w-2 bg-[var(--brand-yellow)]"></div>

            <div class="grid gap-6 px-6 py-8 sm:px-10 lg:grid-cols-[180px_1fr] lg:items-center lg:gap-10 lg:px-12">
                <div class="mx-auto flex h-40 w-40 items-end justify-center overflow-hidden rounded-full bg-gradient-to-br from-blue-100 to-emerald-100 ring-8 ring-blue-50 lg:h-44 lg:w-44">
                    <img
                        src="{{ asset('image/kepala-sekolah-sri-dewi.png') }}"
                        alt="Ibunda Sri Dewi, S.E., Kepala RA Fadhilah"
                        class="h-full w-full object-cover object-top"
                    >
                </div>

                <div class="flex min-h-[176px] flex-col">
                    <div>
                        <h2 class="text-2xl font-black text-[var(--brand-blue)] sm:text-3xl">Sambutan Kepala Sekolah</h2>
                        <p class="mt-2 text-base font-black text-slate-800 sm:text-lg">Ibunda Sri Dewi, S.E.</p>
                        <p class="text-sm font-semibold text-slate-500">Kepala RA Fadhilah Pekanbaru</p>

                        <p class="mt-5 max-w-4xl text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                            Assalamu'alaikum Warahmatullahi Wabarakatuh. Puji syukur ke hadirat Allah Subhanahu wa Ta'ala atas limpahan rahmat dan karunia-Nya sehingga website resmi RA Fadhilah Pekanbaru dapat hadir sebagai sarana informasi, komunikasi, dan layanan bagi seluruh keluarga besar sekolah.
                        </p>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <a href="{{ url('/profile/kata-sambutan') }}" class="inline-flex items-center gap-2 bg-[var(--brand-blue)] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#0b4277]">
                            Selengkapnya
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden bg-[#f5f9ff]">
        <div class="absolute -left-24 top-12 h-64 w-64 rounded-full bg-[var(--brand-yellow)]/10"></div>
        <div class="absolute -right-28 bottom-0 h-80 w-80 rounded-full bg-[var(--brand-blue)]/10"></div>

        <div class="relative mx-auto grid max-w-[1400px] gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1fr_1.15fr] lg:items-center lg:gap-16 lg:py-20">
            <div class="relative mx-auto w-full max-w-[620px]">
                <div class="absolute -left-4 -top-4 h-full w-full bg-[var(--brand-yellow)]"></div>
                <div class="absolute -bottom-4 -right-4 h-full w-full border-4 border-[var(--brand-blue)]/20"></div>
                <img
                    src="{{ $profileImage }}"
                    alt="Kegiatan belajar di RA Fadhilah"
                    class="relative h-[460px] w-full object-cover shadow-[0_24px_60px_rgba(15,79,140,0.2)] sm:h-[560px] lg:h-[620px]"
                >
            </div>

            <div>
                <h2 class="text-3xl font-black leading-tight text-[var(--brand-blue)] sm:text-4xl lg:text-5xl">
                    Selamat Datang di RA Fadhilah
                </h2>
                <div class="mt-5 h-1.5 w-20 rounded-full bg-[var(--brand-yellow)]"></div>

                <div class="mt-8 space-y-5 text-justify text-base leading-8 text-slate-600 sm:text-lg sm:leading-9">
                    <p>
                        Raudhatul Athfal Fadhilah merupakan lembaga pendidikan anak usia dini yang setara dengan taman kanak-kanak. Sekolah ini berfokus pada pembentukan karakter, penanaman nilai-nilai keagamaan, serta pengembangan keterampilan dasar anak sejak usia dini. RA Fadhilah berlokasi di Jl. Muhajirin, Sidomulyo Barat, Kecamatan Tampan, Kota Pekanbaru, Provinsi Riau.
                    </p>
                    <p>
                        Bermula dari berdirinya Yayasan Darel Fadhilah yang menaungi lembaga pendidikan Islam terpadu, RA Fadhilah hadir sebagai bentuk komitmen yayasan dalam menyediakan pendidikan anak usia dini yang berkualitas, hangat, dan berlandaskan nilai-nilai keislaman. Seiring meningkatnya kebutuhan masyarakat terhadap pendidikan Islami, RA Fadhilah didirikan pada 11 Januari 2009 sebagai ruang tumbuh yang aman dan menyenangkan bagi anak-anak.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-[1260px] px-4 py-16 sm:px-6 lg:py-20">
            <div class="text-center">
                <h2 class="section-title">Visi dan Misi</h2>
                <div class="section-accent"></div>
                <p class="mt-4 text-sm text-slate-500 sm:text-base">Arah pendidikan Raudhatul Athfal Fadhilah</p>
            </div>

            <div class="mt-12 grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <article class="relative overflow-hidden rounded-3xl bg-[var(--brand-blue)] p-7 text-white shadow-[0_20px_45px_rgba(15,79,140,0.18)] sm:p-10">
                    <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-20 -left-12 h-52 w-52 rounded-full bg-[var(--brand-yellow)]/15"></div>

                    <div class="relative">
                        <span class="inline-flex rounded-full bg-[var(--brand-yellow)] px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-[var(--brand-blue)]">
                            Visi
                        </span>
                        <p class="mt-8 text-xl font-bold italic leading-9 sm:text-2xl sm:leading-10">
                            “Terwujudnya anak usia dini yang beriman dan bertakwa kepada Allah SWT, berakhlak mulia, sehat, cerdas, ceria, dan siap melanjutkan pendidikan ke jenjang berikutnya.”
                        </p>
                    </div>
                </article>

                <article class="rounded-3xl bg-[#f5f9ff] p-7 shadow-[0_16px_40px_rgba(15,79,140,0.08)] sm:p-10">
                    <h3 class="text-2xl font-black text-[var(--brand-blue)] sm:text-3xl">Misi</h3>
                    <div class="mt-6 space-y-4">
                        @foreach ([
                            'Menanamkan nilai-nilai keimanan dan ketakwaan sejak dini melalui pembiasaan ibadah dan akhlak mulia.',
                            'Mengembangkan potensi anak secara optimal, meliputi moral agama, fisik motorik, kognitif, bahasa, sosial emosional, dan seni.',
                            'Menciptakan lingkungan belajar yang nyaman, menyenangkan, dan Islami.',
                            'Membiasakan anak untuk mandiri, disiplin, dan bertanggung jawab.',
                            'Menjalin kerja sama yang baik antara sekolah, orang tua, dan masyarakat.',
                        ] as $mission)
                            <div class="flex gap-4">
                                <span class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[var(--brand-yellow)] text-sm font-black text-[var(--brand-blue)]">
                                    {{ $loop->iteration }}
                                </span>
                                <p class="text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">{{ $mission }}</p>
                            </div>
                        @endforeach
                    </div>

                </article>
            </div>
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
                    @if ($teacher['url'])
                        <a href="{{ $teacher['url'] }}" class="group block" aria-label="Lihat profil {{ $teacher['name'] }}">
                            <div class="mx-auto flex h-52 items-end justify-center overflow-hidden">
                                <img src="{{ $teacher['image'] }}" alt="{{ $teacher['name'] }}" class="h-full object-contain transition duration-300 group-hover:scale-105">
                            </div>
                            <h3 class="mt-4 text-2xl font-black text-slate-800 transition group-hover:text-[var(--brand-blue)]">{{ $teacher['name'] }}</h3>
                        </a>
                    @else
                        <div class="mx-auto flex h-52 items-end justify-center overflow-hidden">
                            <img src="{{ $teacher['image'] }}" alt="{{ $teacher['name'] }}" class="h-full object-contain">
                        </div>
                        <h3 class="mt-4 text-2xl font-black text-slate-800">{{ $teacher['name'] }}</h3>
                    @endif
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
            @keydown.left.prevent="prev()"
            @keydown.right.prevent="next()"
            class="mx-auto max-w-[1260px] px-4 py-16 focus:outline-none sm:px-6"
            tabindex="0"
        >
            <h2 class="section-title">Kegiatanku</h2>
            <div class="section-accent"></div>

            <div class="relative mt-12">
                <button
                    type="button"
                    @click="prev(); start()"
                    x-show="canSlide()"
                    class="absolute left-0 top-36 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/70 bg-white/75 text-[var(--brand-blue)] shadow-[0_6px_20px_rgba(15,23,42,0.16)] backdrop-blur-sm transition hover:bg-white hover:shadow-[0_8px_24px_rgba(15,23,42,0.22)] focus:outline-none focus:ring-4 focus:ring-white/60"
                    aria-label="Kegiatan sebelumnya"
                    style="display: none;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <div class="mx-6 overflow-hidden sm:mx-8 lg:mx-10">
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

                <button
                    type="button"
                    @click="next(); start()"
                    x-show="canSlide()"
                    class="absolute right-0 top-36 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/70 bg-white/75 text-[var(--brand-blue)] shadow-[0_6px_20px_rgba(15,23,42,0.16)] backdrop-blur-sm transition hover:bg-white hover:shadow-[0_8px_24px_rgba(15,23,42,0.22)] focus:outline-none focus:ring-4 focus:ring-white/60"
                    aria-label="Kegiatan berikutnya"
                    style="display: none;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
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
        <div
            x-data="testimonialSlider({{ $testimonials->count() }})"
            x-init="init()"
            @resize.window="updatePerView()"
            @keydown.left.prevent="prev()"
            @keydown.right.prevent="next()"
            class="mx-auto max-w-[1260px] px-4 focus:outline-none sm:px-6"
            tabindex="0"
        >
            <h2 class="text-center text-4xl font-black uppercase">Testimoni</h2>
            <p class="mt-4 text-center text-sm font-semibold text-white/80">Apa kata mereka tentang RA Fadhilah?</p>

            <div class="relative mt-12">
                <button
                    type="button"
                    @click="prev()"
                    x-show="canSlide()"
                    class="absolute left-0 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-white/15 text-white shadow-lg backdrop-blur-sm transition hover:bg-white/30 focus:outline-none focus:ring-4 focus:ring-white/20"
                    aria-label="Testimoni sebelumnya"
                    style="display: none;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <div class="mx-6 overflow-hidden sm:mx-9 lg:mx-12">
                    <div
                        class="flex items-stretch transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${activeIndex * (100 / perView)}%);`"
                    >
                @foreach ($testimonials as $testimonial)
                    <article class="flex min-h-64 shrink-0 basis-full flex-col justify-between border border-white/20 px-6 py-8 lg:basis-1/2">
                        <p class="text-lg font-semibold leading-9 text-white/95">“{{ $testimonial['quote'] }}”</p>
                        <div class="mt-8 flex items-center gap-4">
                            <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="h-16 w-16 rounded-full border-2 border-[var(--brand-yellow)] object-cover">
                            <p class="text-lg font-black uppercase">{{ $testimonial['name'] }}</p>
                        </div>
                    </article>
                @endforeach
                    </div>
                </div>

                <button
                    type="button"
                    @click="next()"
                    x-show="canSlide()"
                    class="absolute right-0 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/30 bg-white/15 text-white shadow-lg backdrop-blur-sm transition hover:bg-white/30 focus:outline-none focus:ring-4 focus:ring-white/20"
                    aria-label="Testimoni berikutnya"
                    style="display: none;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>

            <div class="mt-8 flex justify-center gap-2" x-show="canSlide()" style="display: none;">
                <template x-for="index in totalPages()" :key="index">
                    <button
                        type="button"
                        @click="goToPage(index - 1)"
                        class="h-2.5 rounded-full transition-all"
                        :class="currentPage() === index - 1 ? 'w-8 bg-[var(--brand-yellow)]' : 'w-2.5 bg-white/35 hover:bg-white/60'"
                        :aria-label="`Lihat halaman testimoni ${index}`"
                    ></button>
                </template>
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
            },
            stop() {
                if (this.intervalId) {
                    clearInterval(this.intervalId);
                    this.intervalId = null;
                }
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            },
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
            },
            goTo(index) {
                this.activeSlide = index;
            },
        };
    }

    function activitySlider(totalItems) {
        return {
            totalItems,
            activeIndex: 0,
            perView: 1,
            init() {
                this.updatePerView();
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

    function testimonialSlider(totalItems) {
        return {
            totalItems,
            activeIndex: 0,
            perView: 1,
            init() {
                this.updatePerView();
            },
            updatePerView() {
                this.perView = window.innerWidth >= 1024 ? 2 : 1;
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
