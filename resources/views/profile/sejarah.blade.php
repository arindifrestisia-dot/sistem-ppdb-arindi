@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 text-sm text-slate-600">
            Anda berada di:
            <a href="{{ url('/profile/dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Sejarah</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div>
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Sejarah</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-800 leading-tight">Perjalanan RA Fadhilah</h1>
                <div class="mt-6 h-1.5 w-24 rounded-full bg-gradient-to-r from-blue-700 to-emerald-500"></div>

                <div class="mt-8 space-y-5 text-justify leading-8 text-slate-700">
                    <p>Bermula dari berdirinya <strong>Yayasan Darel Fadhilah</strong> yang menaungi lembaga pendidikan Islam terpadu, RA Fadhilah hadir sebagai bentuk komitmen yayasan dalam memberikan layanan pendidikan anak usia dini yang berkualitas, hangat, dan berlandaskan nilai-nilai keislaman.</p>

                    <p>Seiring meningkatnya kebutuhan masyarakat terhadap pendidikan anak usia dini yang Islami, pada <strong>11 Januari 2009</strong> didirikanlah <strong>Raudhatul Athfal (RA) Fadhilah</strong>. Sejak awal, sekolah ini dirancang menjadi ruang tumbuh yang aman dan menyenangkan bagi anak-anak untuk belajar, bermain, dan membangun karakter mulia.</p>

                    <p>RA Fadhilah terus berkembang sebagai lembaga yang menyeimbangkan pembinaan spiritual, sosial, emosional, dan kognitif anak. Proses pembelajaran dirancang agar anak tidak hanya siap memasuki jenjang pendidikan dasar, tetapi juga terbiasa dengan pembiasaan Islami dalam kehidupan sehari-hari.</p>

                    <p>Lingkungan belajar yang nyaman, dukungan orang tua, serta tenaga pendidik yang berdedikasi menjadi kekuatan utama dalam perjalanan sekolah ini. Dari waktu ke waktu, RA Fadhilah terus melakukan pengembangan kurikulum, metode belajar, dan fasilitas penunjang agar pelayanan pendidikan semakin relevan dengan kebutuhan peserta didik.</p>

                    <p>Dengan semangat mencetak generasi yang <strong>Islami, berakhlak mulia, cerdas, dan mandiri</strong>, RA Fadhilah berkomitmen untuk terus tumbuh sebagai lembaga pendidikan anak usia dini yang dipercaya masyarakat dan memberi kontribusi nyata bagi masa depan anak-anak bangsa.</p>
                </div>
            </section>

        </div>

        <div class="mt-8">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Tonggak Perjalanan</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">Perkembangan RA Fadhilah</h3>

                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-sm font-semibold text-blue-700">2009</p>
                        <p class="mt-2 text-slate-700">RA Fadhilah mulai hadir sebagai lembaga pendidikan anak usia dini di bawah naungan Yayasan Darel Fadhilah.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-sm font-semibold text-blue-700">Penguatan Karakter</p>
                        <p class="mt-2 text-slate-700">Sekolah mengembangkan pembelajaran yang menanamkan nilai Islami, kemandirian, dan kebiasaan positif sejak dini.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-sm font-semibold text-blue-700">Pengembangan Layanan</p>
                        <p class="mt-2 text-slate-700">Fasilitas, kurikulum, serta kolaborasi dengan orang tua terus diperkuat agar kualitas pendidikan semakin optimal.</p>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>
@endsection
