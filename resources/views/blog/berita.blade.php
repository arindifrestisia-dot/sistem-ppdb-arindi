@extends('layouts.app')

@section('title', 'Informasi Berita - RA Fadhilah')

@section('content')
@php
    $newsItems = [
        ['title' => 'Puncak Tema dan Pentas Anak RA Fadhilah Tahun Ajaran 2025/2026', 'image' => 'image/berita.png', 'date' => '12 Februari 2026'],
        ['title' => 'Peringatan Maulid Nabi Muhammad SAW di Lingkungan RA Fadhilah', 'image' => 'image/berita1.png', 'date' => '14 November 2025'],
        ['title' => 'Pelatihan Guru dan Penguatan Pembelajaran PAUD Islami', 'image' => 'image/berita2.png', 'date' => '14 Desember 2025'],
        ['title' => 'Kunjungan Edukatif Anak RA Fadhilah untuk Belajar di Luar Kelas', 'image' => 'image/berita1.png', 'date' => '14 Desember 2025'],
        ['title' => 'Kegiatan Semester Ganjil Bersama Orang Tua dan Yayasan', 'image' => 'image/berita2.png', 'date' => '14 Desember 2025'],
        ['title' => 'Milad dan Gebyar Kebersamaan Keluarga Besar RA Fadhilah', 'image' => 'image/berita.png', 'date' => '14 Desember 2025'],
        ['title' => 'Semarak Hari Besar Nasional di RA Fadhilah Penuh Keceriaan', 'image' => 'image/berita2.png', 'date' => '14 Desember 2025'],
        ['title' => 'Seminar Parenting untuk Mendukung Tumbuh Kembang Anak', 'image' => 'image/berita1.png', 'date' => '11 Desember 2025'],
        ['title' => 'Perpisahan dan Pelepasan Peserta Didik RA Fadhilah Angkatan 2025', 'image' => 'image/berita.png', 'date' => '11 Desember 2025'],
    ];
@endphp
<div class="min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-3xl font-extrabold text-slate-800">Berita</h1>
        </div>

        <div class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($newsItems as $item)
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
