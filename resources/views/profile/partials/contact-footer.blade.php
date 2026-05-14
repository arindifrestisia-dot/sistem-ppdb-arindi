<section class="mt-8 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
    <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.9fr)]">
        <div class="px-6 py-10 sm:px-10 lg:px-12 lg:py-12">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--brand-emerald)]">Kontak Sekolah</p>
            <h3 class="mt-3 text-3xl font-black text-[var(--brand-navy)]">Raudhatul Athfal Fadhilah</h3>
            <p class="mt-4 max-w-2xl text-sm leading-8 text-slate-600">
                Untuk informasi profil sekolah, kegiatan belajar, dan layanan PPDB, silakan hubungi kami melalui kontak di bawah ini.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.5rem] bg-slate-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Alamat</p>
                    <p class="mt-2 text-sm leading-7 text-slate-700">CCV9+42C, Jl. Muhajirin, Sidomulyo Barat, Kec. Tampan, Kota Pekanbaru, Riau 28294</p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Email</p>
                    <a href="mailto:admin@rafadhilah.sch.id" class="mt-2 inline-block text-sm font-semibold text-[var(--brand-emerald)] hover:text-[var(--brand-navy)]">admin@rafadhilah.sch.id</a>
                </div>
                <div class="rounded-[1.5rem] bg-slate-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Telepon</p>
                    <p class="mt-2 text-sm text-slate-700">(0548) 27483</p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">WhatsApp</p>
                    <a href="https://wa.me/628115860111" target="_blank" rel="noreferrer" class="mt-2 inline-block text-sm font-semibold text-[var(--brand-emerald)] hover:text-[var(--brand-navy)]">+62 811 5860 111</a>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('ppdb.info') }}" class="inline-flex items-center justify-center rounded-full bg-[var(--brand-gold)] px-5 py-3 text-xs font-bold uppercase tracking-[0.16em] text-[var(--brand-navy)]">
                    Portal PPDB
                </a>
                <a href="{{ url('/profile/kontak-kami') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 px-5 py-3 text-xs font-bold uppercase tracking-[0.16em] text-slate-700">
                    Kontak Lengkap
                </a>
            </div>
        </div>

        <div class="bg-[linear-gradient(135deg,#0f2d46,#176b5b)] px-6 py-10 text-center text-white sm:px-10 lg:px-12 lg:py-12">
            <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="mx-auto h-32 w-32 object-contain">
            <h3 class="mt-6 text-2xl font-black">RA FADHILAH</h3>
            <p class="mt-2 text-sm font-semibold uppercase tracking-[0.24em] text-white/70">Yayasan Darel Fadhilah</p>

            <div class="mt-8 grid grid-cols-2 gap-4 text-center">
                <div class="rounded-[1.4rem] bg-white/10 px-4 py-5 backdrop-blur-sm">
                    <p class="text-3xl font-black">A</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.18em] text-white/70">Akreditasi</p>
                </div>
                <div class="rounded-[1.4rem] bg-white/10 px-4 py-5 backdrop-blur-sm">
                    <p class="text-3xl font-black">2009</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.18em] text-white/70">Berdiri</p>
                </div>
            </div>

            <p class="mt-8 text-sm leading-7 text-white/80">
                Mewujudkan generasi yang Islami, berakhlak mulia, cerdas, dan mandiri.
            </p>
        </div>
    </div>

    <div class="bg-[#0b2234] px-6 py-5 text-center text-sm text-white/75">
        Copyright &copy; 2026 Raudhatul Athfal Fadhilah Pekanbaru. All rights reserved.
    </div>
</section>
