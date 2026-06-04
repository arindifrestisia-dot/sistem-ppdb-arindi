@extends('layouts.app')

@section('title', $newsItem->title . ' - RA Fadhilah')

@section('content')
@php
    $body = trim((string) ($newsItem->content ?: $newsItem->excerpt));
    $paragraphs = collect(preg_split('/\R{2,}/', $body))
        ->map(fn ($paragraph) => trim($paragraph))
        ->filter();
    $coverImage = $newsItem->image_path ? asset('storage/' . $newsItem->image_path) : asset('image/berita.png');
    $galleryImages = $newsItem->images
        ->reject(fn ($image) => $image->image_path === $newsItem->image_path)
        ->values();
@endphp

<div class="min-h-screen bg-slate-50">
    <article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('blog.berita') }}" class="inline-flex items-center text-sm font-semibold text-[var(--brand-blue)] hover:text-blue-800">
            Kembali ke Berita
        </a>

        <header class="mt-6 border-b border-slate-200 pb-8">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-[var(--brand-blue)]">Berita Sekolah</p>
            <h1 class="mt-3 text-3xl font-black leading-tight text-slate-900 sm:text-5xl">{{ $newsItem->title }}</h1>
            <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 text-sm text-slate-500">
                <span>{{ optional($newsItem->published_at)->translatedFormat('d F Y') ?? 'Informasi Sekolah' }}</span>
                <span>Admin RA</span>
            </div>
        </header>

        <img
            src="{{ $coverImage }}"
            alt="{{ $newsItem->title }}"
            class="mt-8 max-h-[520px] w-full object-cover shadow-sm ring-1 ring-slate-200"
        >

        @if ($galleryImages->isNotEmpty())
            <section class="mt-6">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($galleryImages as $image)
                        <a
                            href="{{ asset('storage/' . $image->image_path) }}"
                            target="_blank"
                            rel="noopener"
                            class="group block overflow-hidden bg-white shadow-sm ring-1 ring-slate-200"
                        >
                            <img
                                src="{{ asset('storage/' . $image->image_path) }}"
                                alt="Foto kegiatan {{ $loop->iteration }} - {{ $newsItem->title }}"
                                class="aspect-square w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                            >
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($newsItem->excerpt)
            <p class="mt-8 border-l-4 border-[var(--brand-yellow)] bg-white px-5 py-4 text-lg font-semibold leading-8 text-slate-700 shadow-sm">
                {{ $newsItem->excerpt }}
            </p>
        @endif

        <div class="mt-8 space-y-5 rounded-[1.75rem] bg-white p-6 text-base leading-8 text-slate-700 shadow-sm ring-1 ring-slate-200 sm:p-8">
            @forelse ($paragraphs as $paragraph)
                <p>{{ $paragraph }}</p>
            @empty
                <p>Informasi lengkap untuk berita ini akan segera diperbarui.</p>
            @endforelse
        </div>

        @if ($relatedNews->isNotEmpty())
            <section class="mt-10 border-t border-slate-200 pt-8">
                <h2 class="text-2xl font-extrabold text-slate-900">Berita Lainnya</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-3">
                    @foreach ($relatedNews as $item)
                        <a href="{{ route('blog.berita.show', $item) }}" class="group overflow-hidden rounded-[1.25rem] bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-md">
                            <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('image/berita.png') }}" alt="{{ $item->title }}" class="h-36 w-full object-cover">
                            <div class="p-4">
                                <p class="text-xs text-slate-500">{{ optional($item->published_at)->translatedFormat('d F Y') ?? 'Informasi Sekolah' }}</p>
                                <h3 class="mt-2 text-sm font-bold leading-6 text-slate-800 group-hover:text-blue-700">{{ $item->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @include('profile.partials.contact-footer')
    </article>
</div>
@endsection
