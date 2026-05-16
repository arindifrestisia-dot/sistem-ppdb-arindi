@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 text-sm text-slate-600">
            Anda berada di:
            <a href="{{ url('/profile/dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Kata Sambutan</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.7fr)_minmax(300px,0.9fr)]">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start gap-6">
                    <div class="w-full sm:w-56 shrink-0">
                        <div class="rounded-3xl bg-gradient-to-br from-blue-50 via-white to-emerald-50 p-3 ring-1 ring-blue-100 shadow-sm">
                            <img
                                src="{{ asset('image/kepala-sekolah-sri-dewi.png') }}"
                                alt="Foto Kepala RA Fadhilah"
                                class="w-full rounded-2xl object-cover"
                            >
                        </div>
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Kata Sambutan</p>
                        <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-800 leading-tight">
                            Kepala Raudhatul Athfal Fadhilah
                        </h1>
                        <div class="mt-4 space-y-1">
                            <h2 class="text-xl font-bold text-blue-800">Ibunda Sri Dewi, S.E.</h2>
                            <p class="text-slate-500">Kepala RA Fadhilah Pekanbaru</p>
                        </div>

                        <div class="mt-6 h-1.5 w-24 rounded-full bg-gradient-to-r from-blue-700 to-emerald-500"></div>
                    </div>
                </div>

                <div class="mt-8 space-y-4 text-justify leading-8 text-slate-700">
                    <p>Assalamu'alaikum Warahmatullahi Wabarakatuh.</p>

                    <p>Puji syukur ke hadirat Allah Subhanahu wa Ta'ala atas limpahan rahmat dan karunia-Nya sehingga website resmi RA Fadhilah Pekanbaru dapat hadir sebagai sarana informasi, komunikasi, dan layanan bagi seluruh keluarga besar sekolah.</p>

                    <p>Kami menyampaikan terima kasih kepada semua pihak yang telah memberikan dukungan, tenaga, dan pemikiran dalam proses pengembangan website ini. Kehadiran laman ini diharapkan menjadi media yang memudahkan masyarakat untuk mengenal lebih dekat program, kegiatan, serta nilai-nilai pendidikan yang tumbuh di RA Fadhilah.</p>

                    <p>Di era digital saat ini, website sekolah menjadi pintu informasi terdepan untuk menampilkan perkembangan lembaga, publikasi kegiatan, dan berbagai pencapaian peserta didik. Melalui media ini, kami ingin membangun komunikasi yang lebih terbuka, hangat, dan bermanfaat antara sekolah, orang tua, serta masyarakat luas.</p>

                    <p>Kami berharap website RA Fadhilah Pekanbaru dapat menjadi ruang yang mendukung proses pembelajaran, mempererat silaturahmi, dan menumbuhkan semangat bersama dalam mendidik generasi yang Islami, cerdas, kreatif, dan berakhlakul karimah.</p>

                    <p>Kami menyadari bahwa website ini masih akan terus berkembang. Oleh karena itu, kritik, saran, dan masukan yang membangun sangat kami harapkan agar layanan informasi sekolah ini semakin baik dari waktu ke waktu.</p>

                    <p>Terima kasih atas kepercayaan dan kerja sama seluruh orang tua, pendidik, tenaga kependidikan, dan mitra sekolah. Semoga setiap langkah yang kita ikhtiarkan senantiasa mendapat ridha Allah Subhanahu wa Ta'ala.</p>

                    <p class="pt-2 font-semibold text-slate-800">Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50">
                            <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="h-10 w-10 object-contain">
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">RA Fadhilah</h3>
                            <p class="text-sm text-slate-500">Yayasan Darel Fadhilah</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4 text-sm">
                        <div class="border-b border-slate-100 pb-3">
                            <p class="font-semibold text-slate-500">Alamat</p>
                            <p class="mt-1 text-slate-700">CCV9+42C, Jl. Muhajirin, Sidomulyo Barat, Kec. Tampan, Kota Pekanbaru, Riau 28294</p>
                        </div>
                        <div class="grid grid-cols-[110px_1fr] gap-y-3 gap-x-4 text-slate-700">
                            <span class="font-semibold text-slate-500">NPSN</span>
                            <span>69731079</span>

                            <span class="font-semibold text-slate-500">Akreditasi</span>
                            <span>A</span>

                            <span class="font-semibold text-slate-500">Kepala RA</span>
                            <span>Ibunda Sri Dewi, S.E.</span>

                            <span class="font-semibold text-slate-500">Telepon</span>
                            <span>0821 6207 736</span>

                            <span class="font-semibold text-slate-500">Email</span>
                            <a href="mailto:admin@rafadhilah.sch.id" class="text-blue-700 hover:text-blue-800">admin@rafadhilah.sch.id</a>

                            <span class="font-semibold text-slate-500">WhatsApp</span>
                            <div class="space-y-1">
                                <a href="https://wa.me/628216207736" target="_blank" class="block text-blue-700 hover:text-blue-800">0821 6207 736</a>
                                <a href="https://wa.me/6282286817315" target="_blank" class="block text-blue-700 hover:text-blue-800">0822 8681 7315</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-900 via-blue-800 to-emerald-700 rounded-3xl p-6 text-white shadow-lg">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-100">Fokus Pendidikan</p>
                    <h3 class="mt-2 text-2xl font-bold leading-snug">Membentuk anak yang ceria, mandiri, dan berakhlak Islami sejak dini.</h3>
                    <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                        <div class="rounded-2xl bg-white/10 px-3 py-4 backdrop-blur-sm">
                            <p class="text-2xl font-extrabold">A</p>
                            <p class="mt-1 text-xs text-blue-100">Akreditasi</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 px-3 py-4 backdrop-blur-sm">
                            <p class="text-2xl font-extrabold">6</p>
                            <p class="mt-1 text-xs text-blue-100">Rombel</p>
                        </div>
                        <div class="rounded-2xl bg-white/10 px-3 py-4 backdrop-blur-sm">
                            <p class="text-2xl font-extrabold">90</p>
                            <p class="mt-1 text-xs text-blue-100">Siswa</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Informasi Tambahan</p>
                        <h3 class="mt-2 text-2xl font-bold text-slate-800">Kalender Akademik Ringkas</h3>
                    </div>
                    <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">April 2026</span>
                </div>

                <div class="mt-6 grid grid-cols-7 gap-2 text-center text-sm">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <div class="rounded-xl bg-slate-100 py-2 font-semibold text-slate-600">{{ $day }}</div>
                    @endforeach

                    @foreach ([null, null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30] as $date)
                        <div class="rounded-xl py-3 {{ $date === 8 ? 'bg-blue-700 font-bold text-white shadow-sm' : 'bg-white text-slate-700 ring-1 ring-slate-200' }}">
                            {{ $date ?? '' }}
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-blue-50 p-4">
                        <p class="font-semibold text-blue-800">Penerimaan Peserta Didik Baru</p>
                        <p class="mt-2 text-sm text-slate-600">Informasi pendaftaran dan persyaratan dapat diakses melalui menu PPDB.</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-4">
                        <p class="font-semibold text-emerald-800">Komunikasi Orang Tua</p>
                        <p class="mt-2 text-sm text-slate-600">Sekolah membuka ruang komunikasi aktif untuk mendukung tumbuh kembang anak.</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-4">
                        <p class="font-semibold text-amber-800">Kegiatan Tematik</p>
                        <p class="mt-2 text-sm text-slate-600">Pembelajaran dikemas menyenangkan melalui aktivitas bermain, ibadah, dan kreativitas.</p>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Tautan Cepat</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">Profil RA Fadhilah</h3>

                <div class="mt-6 space-y-3">
                    <a href="{{ url('/profile/sejarah') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800">
                        <span>Sejarah Sekolah</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ url('/profile/visi-misi') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800">
                        <span>Visi, Misi, dan Tujuan</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ url('/profile/tenaga-pendidik') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800">
                        <span>Tenaga Pendidik</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ url('/profile/kontak-kami') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800">
                        <span>Kontak Kami</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ url('/ppdb/info') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800">
                        <span>Informasi PPDB</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
