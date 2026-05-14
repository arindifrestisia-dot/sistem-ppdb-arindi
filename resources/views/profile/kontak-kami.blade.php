@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 text-sm text-slate-600">
            Anda berada di:
            <a href="{{ url('/profile/dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Kontak Kami</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.7fr)_minmax(300px,0.9fr)]">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Kontak Kami</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-800 leading-tight">Terhubung dengan RA Fadhilah</h1>
                <div class="mt-6 h-1.5 w-24 rounded-full bg-gradient-to-r from-blue-700 to-emerald-500"></div>

                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Alamat</p>
                        <p class="mt-3 leading-7 text-slate-700">CCV9+42C, Jl. Muhajirin, Sidomulyo Barat, Kec. Tampan, Kota Pekanbaru, Riau 28294</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Email</p>
                        <a href="mailto:admin@rafadhilah.sch.id" class="mt-3 block text-blue-700 hover:text-blue-800">admin@rafadhilah.sch.id</a>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Telepon</p>
                        <p class="mt-3 text-slate-700">(0548) 27483</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">WhatsApp</p>
                        <a href="https://wa.me/628115860111" target="_blank" class="mt-3 block text-blue-700 hover:text-blue-800">+62 811 5860 111</a>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 md:col-span-2">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Jam Kerja</p>
                        <p class="mt-3 text-slate-700">Senin sampai Jumat, pukul 07.00 - 16.00 WIB</p>
                    </div>
                </div>

                <div class="mt-8 rounded-3xl overflow-hidden ring-1 ring-slate-200 shadow-sm">
                    <iframe
                        src="https://www.google.com/maps?q=Jl.+Muhajirin,+Sidomulyo+Bar.,+Tampan,+Pekanbaru,+Riau+28294&output=embed"
                        width="100%"
                        height="380"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
                </div>
            </section>

            <aside>
                @include('profile.partials.sidebar-info')
            </aside>
        </div>

        <div class="mt-8">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Layanan Informasi</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">Hal yang Bisa Ditanyakan</h3>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-blue-50 p-5">
                        <p class="font-semibold text-blue-800">Informasi PPDB</p>
                        <p class="mt-2 text-sm text-slate-600">Jadwal, syarat pendaftaran, dan alur penerimaan peserta didik baru.</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-5">
                        <p class="font-semibold text-emerald-800">Kegiatan Sekolah</p>
                        <p class="mt-2 text-sm text-slate-600">Informasi program pembelajaran, agenda, dan kegiatan tematik RA Fadhilah.</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-5">
                        <p class="font-semibold text-amber-800">Layanan Orang Tua</p>
                        <p class="mt-2 text-sm text-slate-600">Komunikasi terkait perkembangan anak dan kebutuhan administrasi sekolah.</p>
                    </div>
                </div>
            </section>
        </div>

        @include('profile.partials.contact-footer')
    </div>
</div>
@endsection
