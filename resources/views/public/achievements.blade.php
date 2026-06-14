@extends('layouts.app')

@section('title', 'Informasi Prestasi Siswa - RA Fadhilah')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="border-b border-slate-200 pb-4">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-[var(--brand-blue)]">Informasi Sekolah</p>
            <h1 class="mt-2 text-3xl font-extrabold text-slate-800">Prestasi Siswa</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">Capaian membanggakan peserta didik dan keluarga besar RA Fadhilah.</p>
        </div>

        <div class="mt-8 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($achievementItems as $item)
                <article class="group">
                    <a href="{{ route('blog.prestasi.show', $item) }}" class="block overflow-hidden bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 group-hover:-translate-y-1 group-hover:shadow-md">
                        <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : asset('image/berita2.png') }}" alt="{{ $item->title }}" class="h-52 w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    </a>
                    <div class="pt-4">
                        <h2 class="text-base font-semibold leading-6 text-slate-800 group-hover:text-blue-700">
                            <a href="{{ route('blog.prestasi.show', $item) }}">{{ $item->title }}</a>
                        </h2>
                        <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-500">
                            <span>{{ optional($item->published_at)->translatedFormat('d F Y') ?? 'Prestasi Sekolah' }}</span>
                            <span>Admin RA</span>
                        </div>
                        <p class="mt-3 text-sm leading-7 text-slate-600">
                            {{ $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->content), 130) }}
                        </p>
                        <a href="{{ route('blog.prestasi.show', $item) }}" class="mt-4 inline-flex items-center justify-center bg-[var(--brand-yellow)] px-4 py-2 text-xs font-black text-white">
                            Read More
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.75rem] border border-dashed border-slate-300 px-6 py-10 text-center text-slate-500 sm:col-span-2 lg:col-span-3">
                    Belum ada data prestasi yang dipublikasikan.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
