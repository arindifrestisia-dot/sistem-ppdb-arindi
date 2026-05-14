@extends('layouts.app')

@section('content')
@php
    $teachers = [
        ['name' => 'Ibunda Sri Dewi, S.E.', 'role' => 'Kepala RA', 'photo' => 'image/contoh.fotobunda.png'],
        ['name' => 'Nurisa, S.Pd.', 'role' => 'Guru Kelas A', 'photo' => 'image/fotoguru.png'],
        ['name' => 'Dwi Rahayu, S.Pd.AUD', 'role' => 'Guru Kelas B', 'photo' => 'image/fotoguru.png'],
        ['name' => 'Fitri Handayani', 'role' => 'Guru Pendamping', 'photo' => 'image/fotoguru.png'],
        ['name' => 'Rahmawati', 'role' => 'Guru Agama', 'photo' => 'image/fotoguru.png'],
        ['name' => 'Tenaga Pendidik RA Fadhilah', 'role' => 'Guru Pendamping', 'photo' => 'image/fotoguru.png'],
    ];
@endphp

<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 text-sm text-slate-600">
            Anda berada di:
            <a href="{{ url('/profile/dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Tenaga Pendidik</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.7fr)_minmax(300px,0.9fr)]">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Tenaga Pendidik</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-800 leading-tight">Tim Pendidik RA Fadhilah</h1>
                <div class="mt-6 h-1.5 w-24 rounded-full bg-gradient-to-r from-blue-700 to-emerald-500"></div>

                <p class="mt-8 text-slate-700 leading-8">RA Fadhilah didukung oleh tenaga pendidik yang berkomitmen mendampingi tumbuh kembang anak dengan pendekatan yang hangat, Islami, dan menyenangkan. Setiap guru berperan dalam membangun lingkungan belajar yang aman sekaligus menumbuhkan karakter positif pada peserta didik.</p>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($teachers as $teacher)
                        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                            <img src="{{ asset($teacher['photo']) }}" alt="{{ $teacher['name'] }}" class="h-72 w-full object-cover">
                            <div class="p-5 text-center">
                                <h3 class="text-lg font-bold text-slate-800">{{ $teacher['name'] }}</h3>
                                <p class="mt-2 text-sm font-medium text-blue-700">{{ $teacher['role'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <aside>
                @include('profile.partials.sidebar-info')
            </aside>
        </div>

        <div class="mt-8">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Peran Pendidik</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">Komitmen Guru di RA Fadhilah</h3>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-blue-50 p-5">
                        <p class="font-semibold text-blue-800">Mendampingi</p>
                        <p class="mt-2 text-sm text-slate-600">Guru mendampingi anak belajar sesuai tahapan perkembangan dan kebutuhan masing-masing.</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-5">
                        <p class="font-semibold text-emerald-800">Menanamkan Adab</p>
                        <p class="mt-2 text-sm text-slate-600">Pembiasaan akhlak, sopan santun, dan nilai Islami menjadi bagian penting dalam setiap kegiatan.</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-5">
                        <p class="font-semibold text-amber-800">Berkolaborasi</p>
                        <p class="mt-2 text-sm text-slate-600">Sekolah dan orang tua berjalan bersama untuk mendukung pertumbuhan anak secara optimal.</p>
                    </div>
                </div>
            </section>
        </div>

        @include('profile.partials.contact-footer')
    </div>
</div>
@endsection
