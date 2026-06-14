@extends('layouts.app')

@section('title', 'Program Kegiatan RA - RA Fadhilah')

@section('content')
@php
    $programs = [
        'Tahfidz Surat Pendek',
        "Iqro'",
        'Praktek Ibadah',
        'Perkenalan Bahasa Arab Dasar',
        'Perkenalan Bahasa Inggris Dasar',
        'Calistung',
        'Hafalan Hadist & Doa Sehari-hari',
        'Outing Class (Berenang, Kebun Binatang, Museum, Puswil, dan Agrowisata, dll)',
    ];

    $learningStrategies = [
        'Adab First',
        'Active Learning',
        'Deep Learning',
        'Outing Class',
        'Integrasi Nilai Islam',
        'Pembinaan Prestasi',
        'Kolaborasi Orang Tua',
    ];
@endphp

<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-4 text-sm text-slate-600 sm:px-6">
            Anda berada di:
            <a href="{{ route('profile.dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Program Kegiatan RA</span>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Program Kegiatan</p>
            <h1 class="mt-2 text-3xl font-extrabold text-slate-900 sm:text-4xl">Program Kegiatan RA Fadhilah</h1>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600">
                Program kegiatan dirancang untuk membangun kemampuan agama, bahasa, kemandirian, dan kesiapan belajar anak melalui kegiatan yang menyenangkan.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($programs as $index => $program)
                    <article class="flex min-h-28 gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--brand-yellow)] text-sm font-black text-[var(--brand-blue)]">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div>
                            <h2 class="text-lg font-bold leading-7 text-slate-900">{{ $program }}</h2>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12 border-t border-slate-200 pt-8">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Strategi Pembelajaran</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900 sm:text-3xl">Strategi Pembelajaran RA Fadhilah</h2>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($learningStrategies as $index => $strategy)
                        <article class="flex min-h-24 gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-sm font-black text-white">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div>
                                <h3 class="text-lg font-bold leading-7 text-slate-900">{{ $strategy }}</h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
