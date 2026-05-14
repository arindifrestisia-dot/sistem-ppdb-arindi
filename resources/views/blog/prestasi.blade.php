@extends('layouts.app')

@section('title', 'Informasi Prestasi Siswa - RA Fadhilah')

@section('content')
@php
    $achievementItems = [
        ['title' => 'Anak RA Fadhilah Tampil Percaya Diri pada Pentas Seni dan Kreasi', 'image' => 'image/berita1.png', 'date' => '12 Februari 2026'],
        ['title' => 'Prestasi Hafalan Doa dan Surah Pendek pada Kegiatan Keagamaan', 'image' => 'image/berita2.png', 'date' => '14 November 2025'],
        ['title' => 'Pendidik RA Fadhilah Meraih Penghargaan dalam Pelatihan PAUD', 'image' => 'image/berita.png', 'date' => '14 Desember 2025'],
        ['title' => 'Peserta Didik Aktif dan Mandiri dalam Kegiatan Kunjungan Edukatif', 'image' => 'image/berita2.png', 'date' => '14 Desember 2025'],
        ['title' => 'Kebersamaan Siswa dan Orang Tua Menguatkan Prestasi Karakter Anak', 'image' => 'image/berita1.png', 'date' => '14 Desember 2025'],
        ['title' => 'Semangat Milad Sekolah Menumbuhkan Kreativitas dan Keberanian Anak', 'image' => 'image/berita.png', 'date' => '14 Desember 2025'],
        ['title' => 'Siswa RA Fadhilah Tampil Ceria pada Peringatan Hari Besar Nasional', 'image' => 'image/berita.png', 'date' => '14 Desember 2025'],
        ['title' => 'Program Parenting Mendukung Capaian Belajar Anak di Rumah dan Sekolah', 'image' => 'image/berita1.png', 'date' => '11 Desember 2025'],
        ['title' => 'Kelulusan Peserta Didik RA Fadhilah Menjadi Momen Prestasi Membanggakan', 'image' => 'image/berita2.png', 'date' => '11 Desember 2025'],
    ];
@endphp
<div class="min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-3xl font-extrabold text-slate-800">Prestasi Siswa</h1>
        </div>

        <div class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($achievementItems as $item)
                <article class="group">
                    <div class="overflow-hidden bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 group-hover:-translate-y-1 group-hover:shadow-md">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" class="h-52 w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    </div>
                    <div class="pt-3">
                        <h2 class="text-base font-semibold leading-6 text-slate-800 group-hover:text-blue-700">
                            {{ $item['title'] }}
                        </h2>
                        <p class="mt-2 text-xs text-slate-500">{{ $item['date'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        @include('profile.partials.contact-footer')
    </div>
</div>
@endsection
