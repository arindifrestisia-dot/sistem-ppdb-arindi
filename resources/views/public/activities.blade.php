@extends('layouts.app')

@section('title', 'Kegiatan Sekolah - RA Fadhilah')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-3xl font-extrabold text-slate-900">Kegiatan Sekolah</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">Kegiatan yang ditampilkan di halaman ini dikelola dari dashboard panitia dan bisa diperbarui kapan saja.</p>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            @forelse ($activityItems as $item)
                <article class="overflow-hidden rounded-[1.75rem] bg-white shadow-sm ring-1 ring-slate-200">
                    <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('image/berita1.png') }}" alt="{{ $item->title }}" class="h-56 w-full object-cover">
                    <div class="p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600">{{ optional($item->published_at)->translatedFormat('d F Y') ?? 'Kegiatan Sekolah' }}</p>
                        <h2 class="mt-3 text-xl font-bold text-slate-900">{{ $item->title }}</h2>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->content), 130) }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.75rem] border border-dashed border-slate-300 px-6 py-10 text-center text-slate-500 lg:col-span-3">
                    Belum ada data kegiatan yang dipublikasikan.
                </div>
            @endforelse
        </div>

        @include('profile.partials.contact-footer')
    </div>
</div>
@endsection
