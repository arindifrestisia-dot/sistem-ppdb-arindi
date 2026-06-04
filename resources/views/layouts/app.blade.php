<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Raudhatul Athfal Fadhilah | Profil Sekolah & PPDB')</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen overflow-x-hidden bg-white text-slate-900">
    @php
        $mainNavItemClass = 'px-4 py-4 text-sm font-semibold transition hover:bg-white/10';
        $mainNavActiveClass = 'bg-[var(--brand-yellow)] px-5 py-4 text-sm font-bold text-white';
    @endphp

    <header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 shadow-sm">
        <div class="bg-white">
            <div class="mx-auto flex max-w-[1260px] flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                <a href="{{ route('profile.dashboard') }}" class="flex min-w-0 items-center gap-4">
                    <img src="{{ asset('image/logo_RA.png') }}" alt="Logo RA Fadhilah" class="h-16 w-16 object-contain sm:h-20 sm:w-20">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[var(--brand-emerald)]">Raudhatul Athfal</p>
                        <h1 class="truncate text-2xl font-black text-[var(--brand-blue)] sm:text-4xl">FADHILAH</h1>
                        <p class="truncate text-xs font-semibold text-slate-500 sm:text-sm">Pekanbaru, Riau</p>
                    </div>
                </a>

                <div class="hidden items-center gap-4 lg:flex">
                    <div class="flex items-center gap-3 rounded-md border border-slate-200 px-4 py-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded border border-[var(--brand-blue)] text-[var(--brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8.25 10.2 13.5a3 3 0 0 0 3.6 0L21 8.25M4.5 19.5h15a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 19.5 4.5h-15A1.5 1.5 0 0 0 3 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Email</p>
                            <a href="mailto:admin@rafadhilah.sch.id" class="text-sm font-semibold text-slate-800">admin@rafadhilah.sch.id</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 rounded-md border border-slate-200 px-4 py-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded border border-[var(--brand-blue)] text-[var(--brand-blue)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.965-.852-1.089l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.143-7.143 1.125 1.125 0 0 1 .38-1.21l1.293-.97c.37-.278.53-.755.417-1.173L7.21 3.102A1.125 1.125 0 0 0 6.122 2.25H4.75A2.25 2.25 0 0 0 2.5 4.5v2.25Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Phone</p>
                            <p class="text-sm font-semibold text-slate-800">Telp: 0821 6207 736, WA: 0822 8681 7315</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 lg:hidden">
                    <a
                        href="{{ auth()->check() ? route('dashboard') : route('ppdb.info') }}"
                        class="inline-flex items-center justify-center rounded-full bg-[var(--brand-yellow)] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[var(--brand-blue)]"
                    >
                        Portal PPDB
                    </a>

                    <button
                        type="button"
                        @click="mobileOpen = !mobileOpen"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-md bg-[var(--brand-blue)] text-white"
                        aria-label="Buka menu navigasi"
                    >
                        <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                        <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <nav class="bg-[var(--brand-blue)] text-white">
            <div class="mx-auto hidden max-w-[1260px] items-center justify-between gap-6 px-4 sm:px-6 lg:flex">
                <div class="flex items-center">
                    <a href="{{ route('profile.dashboard') }}" class="{{ request()->routeIs('profile.dashboard') || request()->routeIs('home') ? $mainNavActiveClass : $mainNavItemClass }}">Home</a>
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            @keydown.escape.window="open = false"
                            class="flex items-center gap-2 px-4 py-4 text-sm font-semibold transition hover:bg-white/10"
                        >
                            Tentang Kami
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.293l3.71-4.06a.75.75 0 1 1 1.1 1.02l-4.25 4.65a.75.75 0 0 1-1.1 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            @click.outside="open = false"
                            x-transition
                            class="absolute left-0 top-full z-50 min-w-[320px] overflow-hidden bg-[#071c2f] py-3 text-white shadow-2xl"
                            style="display: none;"
                        >
                            <a href="{{ url('/profile/kata-sambutan') }}" class="block px-8 py-5 text-[15px] font-medium text-white/80 transition hover:bg-white/5 hover:text-white">Kata Sambutan</a>
                            <a href="{{ url('/profile/visi-misi') }}" class="block px-8 py-5 text-[15px] font-medium text-white/80 transition hover:bg-white/5 hover:text-white">Visi-Misi dan Tujuan RA Fadhilah</a>
                            <a href="{{ url('/profile/tenaga-pendidik') }}" class="block px-8 py-5 text-[15px] font-medium text-white/80 transition hover:bg-white/5 hover:text-white">Tenaga Pendidik</a>
                            <a href="{{ url('/profile/sejarah') }}" class="block px-8 py-5 text-[15px] font-medium text-white/80 transition hover:bg-white/5 hover:text-white">Sejarah</a>
                            <a href="{{ route('profile.program-kegiatan-ra') }}" class="block px-8 py-5 text-[15px] font-medium text-white/80 transition hover:bg-white/5 hover:text-white">Program Kegiatan RA</a>
                        </div>
                    </div>
                    <a href="{{ route('blog.berita') }}" class="{{ request()->is('blog/berita*') ? $mainNavActiveClass : $mainNavItemClass }}">Berita</a>
                    <a href="{{ route('blog.prestasi') }}" class="{{ request()->routeIs('blog.prestasi') ? $mainNavActiveClass : $mainNavItemClass }}">Prestasi</a>
                    <a href="{{ route('profile.fasilitas') }}" class="{{ request()->routeIs('profile.fasilitas') ? $mainNavActiveClass : $mainNavItemClass }}">Fasilitas</a>
                    <a href="{{ route('ppdb.info') }}" class="{{ request()->routeIs('ppdb.info') ? $mainNavActiveClass : $mainNavItemClass }}">Penerimaan Murid Baru</a>
                    <a href="{{ url('/profile/kontak-kami') }}" class="{{ request()->is('profile/kontak-kami') ? $mainNavActiveClass : $mainNavItemClass }}">Kontak Kami</a>
                </div>

                <div class="flex items-center gap-4">
                    <a
                        href="{{ auth()->check() ? route('dashboard') : route('ppdb.info') }}"
                        class="inline-flex items-center justify-center rounded-full bg-[var(--brand-yellow)] px-5 py-2 text-xs font-black uppercase tracking-[0.16em] text-[var(--brand-blue)] shadow-md"
                    >
                        Portal PPDB
                    </a>
                    <button type="button" class="p-2 text-white/90" aria-label="Cari">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                        </svg>
                    </button>
                </div>
            </div>

                <div x-show="mobileOpen" x-transition class="border-t border-white/10 px-4 py-4 sm:px-6 lg:hidden" style="display: none;">
                    <div class="grid gap-2">
                        <a href="{{ route('profile.dashboard') }}" class="rounded-md px-4 py-3 text-sm font-semibold hover:bg-white/10">Home</a>
                        <div class="rounded-md bg-white/5 px-4 py-3">
                            <p class="text-sm font-semibold">Tentang Kami</p>
                            <div class="mt-3 grid gap-1 pl-2 text-sm text-white/80">
                                <a href="{{ url('/profile/kata-sambutan') }}" class="rounded-md px-3 py-2 hover:bg-white/10 hover:text-white">Kata Sambutan</a>
                                <a href="{{ url('/profile/visi-misi') }}" class="rounded-md px-3 py-2 hover:bg-white/10 hover:text-white">Visi-Misi dan Tujuan RA Fadhilah</a>
                                <a href="{{ url('/profile/tenaga-pendidik') }}" class="rounded-md px-3 py-2 hover:bg-white/10 hover:text-white">Tenaga Pendidik</a>
                                <a href="{{ url('/profile/sejarah') }}" class="rounded-md px-3 py-2 hover:bg-white/10 hover:text-white">Sejarah</a>
                                <a href="{{ route('profile.program-kegiatan-ra') }}" class="rounded-md px-3 py-2 hover:bg-white/10 hover:text-white">Program Kegiatan RA</a>
                            </div>
                        </div>
                        <a href="{{ route('blog.berita') }}" class="rounded-md px-4 py-3 text-sm font-semibold hover:bg-white/10">Berita</a>
                        <a href="{{ route('blog.prestasi') }}" class="rounded-md px-4 py-3 text-sm font-semibold hover:bg-white/10">Prestasi</a>
                        <a href="{{ route('profile.fasilitas') }}" class="rounded-md px-4 py-3 text-sm font-semibold hover:bg-white/10">Fasilitas</a>
                        <a href="{{ route('ppdb.info') }}" class="rounded-md px-4 py-3 text-sm font-semibold hover:bg-white/10">Penerimaan Murid Baru</a>
                    <a href="{{ url('/profile/kontak-kami') }}" class="rounded-md px-4 py-3 text-sm font-semibold hover:bg-white/10">Kontak Kami</a>
                </div>
            </div>
        </nav>

        <div class="h-5 bg-[linear-gradient(90deg,#2f8f45_0%,#71c35b_50%,#2f8f45_100%)]"></div>
    </header>

    <main>
        @yield('content')
    </main>

    <div x-data="schoolChatWidget()" class="fixed bottom-5 right-5 z-[70]">
        <button
            type="button"
            @click="open = !open"
            class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[var(--brand-blue)] to-[#1e6db3] text-white shadow-[0_18px_40px_rgba(15,45,70,0.3)] transition hover:scale-105"
            aria-label="Buka chatbot sekolah"
        >
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" viewBox="0 0 24 24" fill="none">
                <path d="M12 3C7.03 3 3 6.58 3 11c0 2.1.92 4.01 2.42 5.44L4.5 21l4.17-2.08c1.03.29 2.16.45 3.33.45 4.97 0 9-3.58 9-8s-4.03-8.37-9-8.37Z" fill="currentColor" fill-opacity="0.22"/>
                <path d="M8.25 10.25h7.5M8.25 13.25h5.25M9.75 7.5h4.5" stroke="white" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 3C7.03 3 3 6.58 3 11c0 2.1.92 4.01 2.42 5.44L4.5 21l4.17-2.08c1.03.29 2.16.45 3.33.45 4.97 0 9-3.58 9-8s-4.03-8.37-9-8.37Z" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
            </svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 scale-95"
            class="absolute bottom-20 right-0 w-[calc(100vw-2.5rem)] max-w-[380px] overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-[0_24px_60px_rgba(15,23,42,0.22)]"
            style="display: none;"
        >
            <div class="bg-gradient-to-r from-[var(--brand-blue)] via-[#1f5a95] to-[#1d78b8] px-5 py-4 text-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100">Chat Sekolah</p>
                        <h3 class="mt-2 text-xl font-extrabold">Asisten RA Fadhilah</h3>
                        <p class="mt-1 text-sm text-blue-50">Siap membantu pertanyaan seputar sekolah dan PPDB.</p>
                    </div>
                    <span class="mt-1 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-bold text-white ring-1 ring-white/15">
                        Online
                    </span>
                </div>
            </div>

            <div class="bg-[linear-gradient(180deg,#eff6ff_0%,#ffffff_18%,#ffffff_100%)] px-4 py-4">
                <div class="mb-4 flex flex-wrap gap-2">
                    <template x-for="question in quickQuestions" :key="question">
                        <button
                            type="button"
                            @click="sendMessage(question)"
                            class="rounded-full border border-blue-200 bg-white px-3 py-2 text-xs font-semibold text-[var(--brand-blue)] transition hover:border-blue-300 hover:bg-blue-50"
                            x-text="question"
                        ></button>
                    </template>
                </div>

                <div class="max-h-[320px] space-y-3 overflow-y-auto pr-1">
                    <template x-for="(message, index) in messages" :key="index">
                        <div :class="message.from === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div
                                :class="message.from === 'user'
                                    ? 'max-w-[85%] rounded-[1.4rem] rounded-br-md bg-[var(--brand-blue)] px-4 py-3 text-sm font-medium text-white shadow-sm'
                                    : 'max-w-[85%] rounded-[1.4rem] rounded-bl-md bg-white px-4 py-3 text-sm text-slate-700 ring-1 ring-slate-200 shadow-sm'"
                                x-text="message.text"
                            ></div>
                        </div>
                    </template>

                    <div x-show="isLoading" class="flex justify-start" style="display: none;">
                        <div class="max-w-[85%] rounded-[1.4rem] rounded-bl-md bg-white px-4 py-3 text-sm text-slate-500 ring-1 ring-slate-200 shadow-sm">
                            Asisten sedang mengetik...
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 bg-white px-4 py-4">
                <form @submit.prevent="sendMessage()" class="flex items-end gap-3">
                    <label class="sr-only" for="school-chat-input">Tulis pertanyaan</label>
                    <textarea
                        id="school-chat-input"
                        x-model="input"
                        rows="1"
                        placeholder="Tulis pertanyaan Anda..."
                        :disabled="isLoading"
                        class="min-h-[52px] flex-1 resize-none rounded-[1.25rem] border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-[var(--brand-blue)] focus:bg-white focus:ring-2 focus:ring-blue-100"
                    ></textarea>
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[var(--brand-yellow)] text-[var(--brand-blue)] shadow-[0_10px_24px_rgba(228,183,79,0.3)] transition hover:bg-[#f0c544]"
                        aria-label="Kirim pesan"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3.105 3.105a.75.75 0 0 1 .82-.163l12.5 5a.75.75 0 0 1 0 1.392l-12.5 5A.75.75 0 0 1 2.9 13.62L4.18 9.75H9a.75.75 0 0 0 0-1.5H4.18L2.9 4.38a.75.75 0 0 1 .205-.775Z" />
                        </svg>
                    </button>
                </form>
                <p class="mt-3 text-xs text-slate-400">Jawaban chatbot diambil dari Ollama lokal melalui endpoint Laravel.</p>
            </div>
        </div>
    </div>

    <script>
        function schoolChatWidget() {
            return {
                open: false,
                messages: [
                    { from: 'bot', text: 'Assalamualaikum, saya asisten virtual RA Fadhilah.' },
                    { from: 'bot', text: 'Silakan tanyakan informasi seputar sekolah, PPDB, jadwal, atau persyaratan.' },
                ],
                input: '',
                isLoading: false,
                quickQuestions: [
                    'Apa saja syarat PPDB?',
                    'Berapa biaya formulir?',
                    'Dimana alamat sekolah?',
                ],
                async sendMessage(text = null) {
                    const content = (text ?? this.input).trim();

                    if (!content || this.isLoading) {
                        return;
                    }

                    this.messages.push({ from: 'user', text: content });
                    this.input = '';
                    this.isLoading = true;

                    try {
                        const response = await fetch('{{ route('chatbot.message') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                message: content,
                            }),
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Gagal mengambil jawaban chatbot.');
                        }

                        this.messages.push({
                            from: 'bot',
                            text: data.reply,
                        });
                    } catch (error) {
                        this.messages.push({
                            from: 'bot',
                            text: error.message || 'Terjadi kendala saat menghubungi chatbot.',
                        });
                    } finally {
                        this.isLoading = false;
                    }
                },
            };
        }
    </script>
</body>
</html>
