@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-4 text-sm text-slate-600 sm:px-6">
            Anda berada di:
            <a href="{{ route('profile.dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">Fasilitas</span>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
        <section class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Fasilitas</p>
            <h1 class="mt-2 text-3xl font-extrabold text-slate-900 sm:text-4xl">Fasilitas Belajar RA Fadhilah</h1>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600">Konten pada halaman ini dikelola oleh panitia melalui dashboard khusus, sehingga informasi fasilitas sekolah dapat diperbarui tanpa mengubah kode halaman publik.</p>

            <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($facilityItems as $facility)
                    <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-slate-50">
                        <img src="{{ $facility->image_path ? asset('storage/' . $facility->image_path) : asset('image/berita1.png') }}" alt="{{ $facility->title }}" class="h-56 w-full object-cover">
                        <div class="p-5">
                            <h3 class="text-xl font-bold text-slate-900">{{ $facility->title }}</h3>
                            @php
                                $facilityDescription = $facility->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($facility->content), 140);
                            @endphp
                            @if (filled($facilityDescription))
                                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $facilityDescription }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="rounded-[1.75rem] border border-dashed border-slate-300 px-6 py-10 text-center text-slate-500 md:col-span-2 xl:col-span-3">
                        Belum ada data fasilitas yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </section>

        @include('profile.partials.contact-footer')
    </div>
</div>
@endsection
