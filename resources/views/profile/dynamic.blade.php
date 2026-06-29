@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-4 text-sm text-slate-600 sm:px-6">
            Anda berada di:
            <a href="{{ url('/profile/dashboard') }}" class="font-semibold text-blue-700 hover:text-blue-800">Beranda</a>
            <span class="mx-2">/</span>
            <span class="text-slate-800">{{ $profileLabel }}</span>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10">
        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">{{ $profileLabel }}</p>
            <h1 class="mt-2 text-3xl font-extrabold leading-tight text-slate-800 sm:text-4xl">{{ $profileTitle }}</h1>
            <div class="mt-6 h-1.5 w-24 rounded-full bg-gradient-to-r from-blue-700 to-emerald-500"></div>

            @if ($profileContent->image_path)
                <div class="mt-8 overflow-hidden rounded-3xl bg-slate-100">
                    <img src="{{ asset('storage/' . $profileContent->image_path) }}" alt="{{ $profileTitle }}" class="max-h-[420px] w-full object-cover">
                </div>
            @endif

            @if ($profileContent->excerpt)
                <p class="mt-8 text-lg font-semibold leading-8 text-slate-700">{{ $profileContent->excerpt }}</p>
            @endif

            @if ($profileContent->content)
                <div class="mt-8 space-y-5 whitespace-pre-line text-justify leading-8 text-slate-700">
                    {{ $profileContent->content }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
