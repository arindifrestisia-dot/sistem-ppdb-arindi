<footer class="bg-[var(--brand-blue)] text-white">
    @php
        $profileLogo = $publicSchoolProfile[\App\Models\SchoolContent::TYPE_PROFILE_LOGO] ?? null;
        $profileName = $publicSchoolProfile[\App\Models\SchoolContent::TYPE_PROFILE_NAME] ?? null;
        $profileContact = $publicSchoolProfile[\App\Models\SchoolContent::TYPE_PROFILE_CONTACT] ?? null;
        $footerSchoolName = $profileName?->title ?: 'Fadhilah';
        $footerSchoolSubtitle = $profileName?->excerpt ?: 'Yayasan Darel Fadhilah';
        $footerSchoolSummary = $profileName?->content ?: 'Mewujudkan generasi yang Islami, berakhlak mulia, cerdas, ceria, dan mandiri melalui pendidikan anak usia dini yang berkualitas.';
        $footerSchoolEmail = $profileContact?->excerpt ?: 'admin@rafadhilah.sch.id';
        $footerLogoUrl = $profileLogo?->image_path ? asset('storage/' . $profileLogo->image_path) : asset('image/logo_RA.png');
    @endphp
    <div class="h-2 bg-[var(--brand-emerald)]">
        <div class="h-full w-32 bg-[var(--brand-yellow)]"></div>
    </div>

    <div class="mx-auto grid max-w-[1260px] grid-cols-1 gap-10 px-6 py-14 sm:px-8 md:grid-cols-2 lg:grid-cols-[1.35fr_0.7fr_1.25fr_0.85fr] lg:gap-16 lg:py-16">
        <div>
            <div class="flex items-center gap-4">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-white/10 p-2 ring-1 ring-white/15">
                    <img src="{{ $footerLogoUrl }}" alt="Logo {{ $footerSchoolName }}" class="h-full w-full object-contain">
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-[var(--brand-yellow)]">Raudhatul Athfal</p>
                    <h2 class="mt-1 text-2xl font-black">{{ $footerSchoolName }}</h2>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.14em] text-white/65">{{ $footerSchoolSubtitle }}</p>
                </div>
            </div>

            <p class="mt-6 max-w-sm text-sm leading-7 text-white/75">
                {{ $footerSchoolSummary }}
            </p>

            <div class="mt-6 flex flex-wrap gap-3">
                <div class="rounded-xl bg-white/10 px-5 py-3 text-center ring-1 ring-white/10">
                    <p class="text-xl font-black text-[var(--brand-yellow)]">A</p>
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Akreditasi</p>
                </div>
                <div class="rounded-xl bg-white/10 px-5 py-3 text-center ring-1 ring-white/10">
                    <p class="text-xl font-black text-[var(--brand-yellow)]">2009</p>
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Tahun Berdiri</p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-black">Tautan</h3>
            <div class="mt-5 grid gap-3 text-sm text-white/75">
                <a href="{{ route('profile.dashboard') }}" class="transition hover:text-[var(--brand-yellow)]">Beranda</a>
                <a href="{{ route('profile.program-kegiatan-ra') }}" class="transition hover:text-[var(--brand-yellow)]">Program Kegiatan</a>
                <a href="{{ route('profile.fasilitas') }}" class="transition hover:text-[var(--brand-yellow)]">Fasilitas</a>
                <a href="{{ route('blog.prestasi') }}" class="transition hover:text-[var(--brand-yellow)]">Prestasi</a>
                <a href="{{ route('ppdb.info') }}" class="transition hover:text-[var(--brand-yellow)]">PPDB</a>
                <a href="{{ url('/profile/kontak-kami') }}" class="transition hover:text-[var(--brand-yellow)]">Kontak</a>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-black">Informasi Kontak</h3>
            <div class="mt-5 space-y-4 text-sm leading-6 text-white/75">
                <div class="flex gap-3">
                    <svg class="mt-1 h-4 w-4 shrink-0 text-[var(--brand-yellow)]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9.69 18.933 9.7 18.94a.75.75 0 0 0 .6 0l.01-.007C10.965 18.46 16.75 14.05 16.75 8A6.75 6.75 0 1 0 3.25 8c0 6.05 5.785 10.46 6.44 10.933ZM10 10.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" clip-rule="evenodd" />
                    </svg>
                    <p>CCV9+42C, Jl. Muhajirin, Sidomulyo Barat, Kec. Tuah Madani, Kota Pekanbaru, Riau 28294</p>
                </div>
                <a href="tel:+628216207736" class="flex gap-3 transition hover:text-white">
                    <svg class="mt-1 h-4 w-4 shrink-0 text-[var(--brand-yellow)]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="m1.885 3.056.638-.638a2.25 2.25 0 0 1 3.183 0l1.376 1.376a2.25 2.25 0 0 1 .43 2.584l-.613 1.226a.75.75 0 0 0 .14.865l4.492 4.492a.75.75 0 0 0 .865.14l1.226-.613a2.25 2.25 0 0 1 2.584.43l1.376 1.376a2.25 2.25 0 0 1 0 3.183l-.638.638c-1.272 1.272-3.189 1.727-4.845.95a24.056 24.056 0 0 1-11.164-11.164c-.777-1.656-.322-3.573.95-4.845Z" clip-rule="evenodd" />
                    </svg>
                    <span>0821 6207 736</span>
                </a>
                <a href="https://wa.me/6282286817315" target="_blank" rel="noreferrer" class="flex gap-3 transition hover:text-white">
                    <svg class="mt-1 h-4 w-4 shrink-0 text-[var(--brand-yellow)]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M10 2a7.5 7.5 0 0 0-6.52 11.2L2.5 17.5l4.42-.92A7.5 7.5 0 1 0 10 2Zm0 13.5a5.97 5.97 0 0 1-3.05-.83l-.27-.16-2.18.45.48-2.1-.18-.28A6 6 0 1 1 10 15.5Z" />
                    </svg>
                    <span>0822 8681 7315</span>
                </a>
                <a href="mailto:admin@rafadhilah.sch.id" class="flex gap-3 break-all transition hover:text-white">
                    <svg class="mt-1 h-4 w-4 shrink-0 text-[var(--brand-yellow)]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M3 4.5A2.5 2.5 0 0 0 .5 7v6A2.5 2.5 0 0 0 3 15.5h14a2.5 2.5 0 0 0 2.5-2.5V7A2.5 2.5 0 0 0 17 4.5H3Zm0 1.5h14c.22 0 .425.06.6.165L10 11.1 2.4 6.165A1.17 1.17 0 0 1 3 6Z" />
                    </svg>
                    <span>{{ $footerSchoolEmail }}</span>
                </a>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-black">Jam Operasional</h3>
            <div class="mt-5 space-y-4 text-sm leading-7 text-white/75">
                <div>
                    <p class="font-bold text-white">Senin - Sabtu</p>
                    <p>08.00 - 13.00 WIB</p>
                </div>
                <div>
                    <p class="font-bold text-white">Minggu & Hari Libur</p>
                    <p>Tutup</p>
                </div>
            </div>

            <a href="{{ route('register') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-[var(--brand-yellow)] px-6 py-3 text-xs font-black uppercase tracking-[0.16em] text-[var(--brand-blue)] transition hover:bg-white">
                Daftar Sekarang
            </a>
        </div>
    </div>

    <div class="border-t border-white/10 bg-[var(--brand-navy)] px-6 py-5 text-center text-xs font-medium text-white/65">
        Copyright &copy; 2026 Raudhatul Athfal Fadhilah Pekanbaru. All rights reserved.
    </div>
</footer>
