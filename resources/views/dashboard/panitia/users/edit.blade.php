<x-panitia-layout title="Edit User">
    <div class="mx-auto max-w-4xl">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit User</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui informasi akun dan hak akses user.</p>
            </div>
            <a href="{{ route('panitia.users.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Kembali</a>
        </div>

        <form method="POST" action="{{ route('panitia.users.update', $managedUser) }}" class="mt-6 bg-white p-6 shadow-sm md:p-8">
            @csrf
            @method('PUT')

            <div class="grid gap-5 md:grid-cols-2">
                <label class="block md:col-span-2">
                    <span class="text-sm font-semibold text-slate-700">Nama lengkap</span>
                    <input type="text" name="name" value="{{ old('name', $managedUser->name) }}" required autofocus class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                    @error('name') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Username</span>
                    <input type="text" name="username" value="{{ old('username', $managedUser->username) }}" required autocomplete="off" class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                    @error('username') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Email</span>
                    <input type="email" name="email" value="{{ old('email', $managedUser->email) }}" required class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                    @error('email') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </label>

                <label class="block md:col-span-2">
                    <span class="text-sm font-semibold text-slate-700">Role</span>
                    <select name="role" required @disabled(auth()->user()->is($managedUser)) class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        @foreach ($roleOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('role', $managedUser->role) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if (auth()->user()->is($managedUser))
                        <input type="hidden" name="role" value="{{ $managedUser->role }}">
                        <span class="mt-1 block text-xs text-slate-500">Role akun yang sedang digunakan tidak dapat diubah.</span>
                    @endif
                    @error('role') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Password baru</span>
                    <span class="relative mt-2 block">
                        <input id="password" type="password" name="password" autocomplete="new-password" class="w-full rounded-lg border border-slate-200 py-3 pl-4 pr-12 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        <button type="button" data-password-toggle="password" title="Tampilkan password" aria-label="Tampilkan password" class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center text-slate-400 hover:text-sky-700">
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
                    <span class="mt-1 block text-xs text-slate-500">Kosongkan jika password tidak diubah.</span>
                    @error('password') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Konfirmasi password baru</span>
                    <span class="relative mt-2 block">
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="w-full rounded-lg border border-slate-200 py-3 pl-4 pr-12 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        <button type="button" data-password-toggle="password_confirmation" title="Tampilkan password" aria-label="Tampilkan password" class="absolute inset-y-0 right-0 inline-flex w-11 items-center justify-center text-slate-400 hover:text-sky-700">
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
                <button type="submit" class="rounded-lg bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <script>
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
