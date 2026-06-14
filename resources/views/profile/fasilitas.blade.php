@extends('layouts.app')

@section('content')
@php
    $facilityHighlights = [
        ['title' => 'Ruang Kepala Sekolah', 'desc' => 'Ruang pelayanan dan koordinasi untuk mendukung manajemen sekolah.', 'image' => 'image/contoh.fotobunda.png'],
        ['title' => 'Ruang Kelas', 'desc' => 'Ruang belajar yang nyaman untuk kegiatan bermain sambil belajar.', 'image' => 'image/poster-tk.jpg'],
        ['title' => 'Aula dan Kegiatan', 'desc' => 'Area serbaguna untuk kegiatan bersama, pentas, dan pembinaan karakter.', 'image' => 'image/berita.png'],
        ['title' => 'Area Bermain', 'desc' => 'Lingkungan bermain yang mendukung motorik, eksplorasi, dan interaksi sosial anak.', 'image' => 'image/berita1.png'],
    ];
@endphp

<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 text-sm text-slate-600">
            Anda berada di:
            <a href="{{ url('/profile/dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Fasilitas</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.7fr)_minmax(300px,0.9fr)]">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Fasilitas</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-slate-800 leading-tight">Fasilitas Belajar RA Fadhilah</h1>
                <div class="mt-6 h-1.5 w-24 rounded-full bg-gradient-to-r from-blue-700 to-emerald-500"></div>

                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl bg-blue-50 p-5">
                        <p class="font-semibold text-blue-800">Ruang Utama</p>
                        <ul class="mt-3 space-y-2 text-sm text-slate-700">
                            <li>1 ruang kepala sekolah</li>
                            <li>1 ruang guru</li>
                            <li>4 ruang kelas</li>
                            <li>1 ruang UKS</li>
                            <li>1 aula</li>
                        </ul>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-5">
                        <p class="font-semibold text-emerald-800">Fasilitas Pendukung</p>
                        <ul class="mt-3 space-y-2 text-sm text-slate-700">
                            <li>2 ruang kamar mandi</li>
                            <li>1 ruang ibadah</li>
                            <li>1 ruang gudang</li>
                            <li>Halaman bermain</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-2">
                    @foreach ($facilityHighlights as $facility)
                        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                            <img src="{{ asset($facility['image']) }}" alt="{{ $facility['title'] }}" class="h-56 w-full object-cover">
                            <div class="p-5">
                                <h3 class="text-xl font-bold text-slate-800">{{ $facility['title'] }}</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600">{{ $facility['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside>
                @include('profile.partials.sidebar-info')
            </aside>
        </div>

        <div class="mt-8">
            <section class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-200 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Lingkungan Belajar</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">Yang Didukung Fasilitas Sekolah</h3>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-blue-50 p-5">
                        <p class="font-semibold text-blue-800">Belajar Nyaman</p>
                        <p class="mt-2 text-sm text-slate-600">Ruang yang tertata membantu anak belajar dengan aman dan menyenangkan.</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-5">
                        <p class="font-semibold text-emerald-800">Aktif dan Kreatif</p>
                        <p class="mt-2 text-sm text-slate-600">Area kegiatan mendorong eksplorasi, gerak aktif, dan pembelajaran tematik.</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 p-5">
                        <p class="font-semibold text-amber-800">Pembiasaan Islami</p>
                        <p class="mt-2 text-sm text-slate-600">Fasilitas sekolah mendukung kebiasaan ibadah dan pembentukan karakter sejak dini.</p>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>
@endsection
