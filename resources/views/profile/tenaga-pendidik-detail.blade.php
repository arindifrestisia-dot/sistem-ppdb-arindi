@extends('layouts.app')

@section('title', $teacher->title . ' | Tenaga Pendidik RA Fadhilah')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-4 text-sm text-slate-600 sm:px-6">
            Anda berada di:
            <a href="{{ route('profile.dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ url('/profile/tenaga-pendidik') }}" class="font-semibold text-blue-700 hover:text-blue-800">Tenaga Pendidik</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">{{ $teacher->title }}</span>
        </div>
    </div>

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:py-16">
        <section class="overflow-hidden rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200">
            <div class="grid lg:grid-cols-[minmax(300px,0.8fr)_minmax(0,1.2fr)]">
                <div class="flex min-h-[440px] items-end justify-center bg-gradient-to-br from-slate-50 to-blue-50 p-8">
                    <img
                        src="{{ $teacher->image_path ? asset('storage/' . $teacher->image_path) : asset('image/fotoguru.png') }}"
                        alt="{{ $teacher->title }}"
                        class="max-h-[520px] w-full max-w-md object-contain"
                    >
                </div>

                <div class="flex flex-col justify-center p-8 sm:p-12 lg:p-16">
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-emerald-600">Profil Guru</p>
                    <h1 class="mt-4 text-4xl font-black leading-tight text-[var(--brand-blue)] sm:text-5xl">{{ $teacher->title }}</h1>
                    <div class="mt-6 h-1.5 w-24 rounded-full bg-[var(--brand-yellow)]"></div>

                    <dl class="mt-10 space-y-6">
                        <div class="border-b border-slate-200 pb-5">
                            <dt class="text-sm font-bold uppercase tracking-[0.16em] text-slate-400">NIP Guru</dt>
                            <dd class="mt-2 text-xl font-bold text-slate-800">{{ $teacher->content ?: 'Belum tersedia' }}</dd>
                        </div>
                        <div class="border-b border-slate-200 pb-5">
                            <dt class="text-sm font-bold uppercase tracking-[0.16em] text-slate-400">Status</dt>
                            <dd class="mt-2 text-xl font-bold text-slate-800">{{ $teacher->excerpt ?: 'Tenaga Pendidik' }}</dd>
                        </div>
                    </dl>

                    <a href="{{ url('/profile/tenaga-pendidik') }}" class="mt-10 inline-flex w-fit items-center gap-2 rounded-full bg-[var(--brand-blue)] px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                        <span aria-hidden="true">&larr;</span>
                        <span>Kembali ke Daftar Guru</span>
                    </a>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
