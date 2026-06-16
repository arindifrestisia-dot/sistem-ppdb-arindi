<x-panitia-layout title="Tambah User">
    <form method="POST" action="{{ route('panitia.users.store') }}" class="mx-auto max-w-6xl">
        @csrf

        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah User</h1>
                <p class="mt-1 text-sm text-slate-500">Buat akun baru untuk mengakses sistem PPDB.</p>
            </div>
            <a href="{{ route('panitia.users.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Kembali</a>
        </div>

        <div class="space-y-6">
                <section class="bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5 md:px-8">
                        <h2 class="font-bold text-slate-800">Informasi Akun</h2>
                    </div>

                    <div class="px-6 py-6 md:px-8">
                        <p class="mb-6 text-sm text-slate-500">Lengkapi data yang akan digunakan untuk masuk ke sistem PPDB.</p>

                        <div class="space-y-5">
                            <label class="grid gap-2 md:grid-cols-[160px_minmax(0,1fr)] md:items-center">
                                <span class="text-sm font-semibold text-slate-600">Nama <span class="text-rose-500">*</span></span>
                                <span>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap" class="w-full rounded border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                    @error('name') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                                </span>
                            </label>

                            <label class="grid gap-2 md:grid-cols-[160px_minmax(0,1fr)] md:items-center">
                                <span class="text-sm font-semibold text-slate-600">Email <span class="text-rose-500">*</span></span>
                                <span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full rounded border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                    @error('email') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                                </span>
                            </label>

                            <label class="grid gap-2 md:grid-cols-[160px_minmax(0,1fr)] md:items-center">
                                <span class="text-sm font-semibold text-slate-600">Username <span class="text-rose-500">*</span></span>
                                <span>
                                    <input type="text" name="username" value="{{ old('username') }}" required autocomplete="off" placeholder="Username login" class="w-full rounded border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                    @error('username') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                                </span>
                            </label>

                            <label class="grid gap-2 md:grid-cols-[160px_minmax(0,1fr)] md:items-center">
                                <span class="text-sm font-semibold text-slate-600">Role <span class="text-rose-500">*</span></span>
                                <span>
                                    <select id="role" name="role" required class="w-full rounded border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                        @foreach ($roleOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(old('role', \App\Models\User::ROLE_PARENT) === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('role') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                                </span>
                            </label>
                        </div>

                        <div id="registrationInfo" class="mt-6 border-l-4 border-sky-500 bg-sky-50 px-4 py-3 text-sm leading-6 text-sky-900">
                            Akun orang tua perlu menyelesaikan pembayaran formulir, data calon siswa, dan unggahan dokumen pendaftaran.
                        </div>
                    </div>
                </section>

                <section class="bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5 md:px-8">
                        <h2 class="font-bold text-slate-800">Password Akun</h2>
                    </div>

                    <div class="px-6 py-6 md:px-8">
                        <p class="mb-6 text-sm text-slate-500">Gunakan password yang kuat agar akun tetap aman.</p>

                        <div class="space-y-5">
                            <label class="grid gap-2 md:grid-cols-[160px_minmax(0,1fr)] md:items-center">
                                <span class="text-sm font-semibold text-slate-600">Password <span class="text-rose-500">*</span></span>
                                <span>
                                    <span class="relative block">
                                        <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full rounded border border-slate-200 py-3 pl-4 pr-12 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                        <button type="button" data-password-toggle="password" title="Tampilkan password" aria-label="Tampilkan password" class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center text-slate-400 hover:text-indigo-600">
                                            <svg class="password-eye-show h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <svg class="password-eye-hide hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="m3 3 18 18"></path>
                                                <path d="M10.6 10.7a2 2 0 0 0 2.7 2.7"></path>
                                                <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a16 16 0 0 1-2.1 3.2"></path>
                                                <path d="M6.6 6.6C3.5 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 4.1-.9"></path>
                                            </svg>
                                        </button>
                                    </span>
                                    @error('password') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                                </span>
                            </label>

                            <label class="grid gap-2 md:grid-cols-[160px_minmax(0,1fr)] md:items-center">
                                <span class="text-sm font-semibold text-slate-600">Konfirmasi <span class="text-rose-500">*</span></span>
                                <span class="relative block">
                                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full rounded border border-slate-200 py-3 pl-4 pr-12 text-sm outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                                    <button type="button" data-password-toggle="password_confirmation" title="Tampilkan password" aria-label="Tampilkan password" class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center text-slate-400 hover:text-indigo-600">
                                        <svg class="password-eye-show h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg class="password-eye-hide hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="m3 3 18 18"></path>
                                            <path d="M10.6 10.7a2 2 0 0 0 2.7 2.7"></path>
                                            <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a16 16 0 0 1-2.1 3.2"></path>
                                            <path d="M6.6 6.6C3.5 8.5 2 12 2 12s3.5 8 10 8a9.8 9.8 0 0 0 4.1-.9"></path>
                                        </svg>
                                    </button>
                                </span>
                            </label>
                        </div>

                        <div class="mt-7 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 rounded bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 3h12l2 2v16H5z"></path>
                                    <path d="M8 3v6h8V3"></path>
                                    <path d="M8 21v-7h8v7"></path>
                                </svg>
                                Simpan User
                            </button>
                        </div>
                    </div>
                </section>
        </div>
    </form>

    <script>
        const roleInput = document.getElementById('role');
        const registrationInfo = document.getElementById('registrationInfo');

        function updateRegistrationInfo() {
            registrationInfo.classList.toggle('hidden', roleInput.value !== @json(\App\Models\User::ROLE_PARENT));
        }

        roleInput.addEventListener('change', updateRegistrationInfo);
        updateRegistrationInfo();

        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isVisible = input.type === 'text';

                input.type = isVisible ? 'password' : 'text';
                button.querySelector('.password-eye-show').classList.toggle('hidden', !isVisible);
                button.querySelector('.password-eye-hide').classList.toggle('hidden', isVisible);
                button.title = isVisible ? 'Tampilkan password' : 'Sembunyikan password';
                button.setAttribute('aria-label', button.title);
            });
        });
    </script>
</x-panitia-layout>
