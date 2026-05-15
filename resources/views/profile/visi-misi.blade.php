@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 text-sm text-slate-600">
            Anda berada di:
            <a href="{{ url('/profile/dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Visi, Misi, dan Tujuan</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.7fr)_minmax(300px,0.9fr)]">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Arah Pendidikan</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-800 leading-tight">Visi, Misi, dan Tujuan RA Fadhilah</h1>
                <div class="mt-6 h-1.5 w-24 rounded-full bg-gradient-to-r from-blue-700 to-emerald-500"></div>

                <div class="mt-8 space-y-8 text-slate-700">
                    <div class="rounded-3xl bg-blue-50 p-6 ring-1 ring-blue-100">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-700">Visi</p>
                        <p class="mt-3 text-lg font-semibold italic leading-8 text-slate-800">"Terwujudnya anak usia dini yang beriman dan bertakwa kepada Allah SWT, berakhlak mulia, sehat, cerdas, ceria, dan siap melanjutkan pendidikan ke jenjang berikutnya."</p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Misi</h2>
                        <div class="mt-4 grid gap-4">
                            <div class="rounded-2xl border border-slate-200 p-5">Menanamkan nilai-nilai keimanan dan ketakwaan sejak dini melalui pembiasaan ibadah dan akhlak mulia.</div>
                            <div class="rounded-2xl border border-slate-200 p-5">Mengembangkan potensi anak secara optimal, meliputi moral agama, fisik motorik, kognitif, bahasa, sosial emosional, dan seni.</div>
                            <div class="rounded-2xl border border-slate-200 p-5">Menciptakan lingkungan belajar yang nyaman, menyenangkan, dan Islami.</div>
                            <div class="rounded-2xl border border-slate-200 p-5">Membiasakan anak untuk mandiri, disiplin, dan bertanggung jawab.</div>
                            <div class="rounded-2xl border border-slate-200 p-5">Menjalin kerja sama yang baik antara sekolah, orang tua, dan masyarakat.</div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Tujuan</h2>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl bg-white ring-1 ring-slate-200 p-5">Menghasilkan peserta didik yang berakhlak mulia.</div>
                            <div class="rounded-2xl bg-white ring-1 ring-slate-200 p-5">Membiasakan anak membaca Al-Qur'an dan melaksanakan praktik salat berjamaah sejak dini.</div>
                            <div class="rounded-2xl bg-white ring-1 ring-slate-200 p-5">Mempersiapkan peserta didik yang cerdas dan terampil untuk melanjutkan ke jenjang pendidikan dasar.</div>
                            <div class="rounded-2xl bg-white ring-1 ring-slate-200 p-5">Mengoptimalkan potensi anak usia dini agar terbentuk perilaku Islami dan kemampuan dasar sesuai tahap perkembangannya.</div>
                            <div class="rounded-2xl bg-white ring-1 ring-slate-200 p-5">Mengembangkan kecerdasan spiritual, intelektual, emosional, kinestetik, dan sosial peserta didik.</div>
                            <div class="rounded-2xl bg-white ring-1 ring-slate-200 p-5">Mengembangkan kemampuan kognitif dan fisik motorik agar siap memasuki pendidikan dasar.</div>
                        </div>
                    </div>
                </div>
            </section>

            <aside>
                @include('profile.partials.sidebar-info')
            </aside>
        </div>

        <div class="mt-8">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Nilai Utama</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">Karakter yang Dibangun</h3>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-blue-50 p-5">
                        <p class="font-semibold text-blue-800">Islami</p>
                        <p class="mt-2 text-sm text-slate-600">Menumbuhkan kebiasaan ibadah, adab, dan akhlak mulia dalam keseharian anak.</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-5">
                        <p class="font-semibold text-emerald-800">Cerdas</p>
                        <p class="mt-2 text-sm text-slate-600">Mengembangkan rasa ingin tahu, kreativitas, dan kemampuan berpikir sesuai usia anak.</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-5">
                        <p class="font-semibold text-amber-800">Mandiri</p>
                        <p class="mt-2 text-sm text-slate-600">Melatih keberanian, tanggung jawab, dan kemandirian sebagai bekal menuju jenjang berikutnya.</p>
                    </div>
                </div>
            </section>
        </div>

        @include('profile.partials.contact-footer')
    </div>
</div>
@endsection
