<x-panitia-layout title="Manajemen User">
    @php
        $roleTone = [
            \App\Models\User::ROLE_PARENT => 'bg-sky-50 text-sky-700',
            \App\Models\User::ROLE_COMMITTEE => 'bg-amber-50 text-amber-700',
            \App\Models\User::ROLE_PRINCIPAL => 'bg-emerald-50 text-emerald-700',
        ];
    @endphp

    @if ($errors->has('user'))
        <div class="mb-5 border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
            {{ $errors->first('user') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar User</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola akun dan pantau kebutuhan registrasi akun orang tua.</p>
        </div>
        <a href="{{ route('panitia.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
            Tambah User
        </a>
    </div>

    <form method="GET" action="{{ route('panitia.users.index') }}" class="mt-6 grid gap-3 bg-white p-4 shadow-sm sm:grid-cols-[minmax(0,1fr)_220px_auto]">
        <input type="search" name="search" value="{{ $filters['search'] }}" placeholder="Cari nama, username, email, atau nomor registrasi" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        <select name="role" class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            <option value="">Semua role</option>
            @foreach ($roleOptions as $value => $label)
                <option value="{{ $value }}" @selected($filters['role'] === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-sky-600 px-5 py-3 text-sm font-semibold text-white hover:bg-sky-700">Terapkan</button>
    </form>

    <div class="mt-5 overflow-x-auto bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-5 py-4 text-left font-semibold text-slate-600">User</th>
                    <th class="px-5 py-4 text-left font-semibold text-slate-600">Role</th>
                    <th class="px-5 py-4 text-left font-semibold text-slate-600">Informasi Registrasi</th>
                    <th class="px-5 py-4 text-left font-semibold text-slate-600">Dibuat</th>
                    <th class="px-5 py-4 text-right font-semibold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    @php
                        $registration = $user->studentRegistration;
                        $paymentPaid = $user->ppdbFormPayment
                            && in_array($user->ppdbFormPayment->status, ['settlement', 'capture'], true)
                            && $user->ppdbFormPayment->paid_at;
                    @endphp
                    <tr class="align-top">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="mt-1 text-slate-500">{{ '@' . $user->username }}</p>
                            <p class="text-xs text-slate-400">{{ $user->email }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $roleTone[$user->role] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $roleOptions[$user->role] ?? \Illuminate\Support\Str::headline($user->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if ($user->isStaff())
                                <span class="text-slate-400">-</span>
                            @elseif (! $paymentPaid)
                                <p class="font-semibold text-amber-700">Perlu pembayaran formulir</p>
                                <p class="mt-1 text-xs text-slate-500">Akun belum dapat mengisi data calon siswa.</p>
                            @elseif (! $registration)
                                <p class="font-semibold text-sky-700">Perlu melengkapi data siswa</p>
                                <p class="mt-1 text-xs text-slate-500">Pembayaran formulir sudah selesai.</p>
                            @elseif (! $registration->submitted_at)
                                <p class="font-semibold text-sky-700">Formulir belum dikirim</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $registration->full_name }}</p>
                            @else
                                <p class="font-semibold text-emerald-700">{{ $registration->registration_number ?? 'Registrasi terkirim' }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $registration->full_name }} <span aria-hidden="true">&middot;</span> {{ \Illuminate\Support\Str::headline($registration->verification_status ?? 'menunggu') }}
                                </p>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $user->created_at?->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    href="{{ route('panitia.users.edit', $user) }}"
                                    title="Edit user"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700"
                                >
                                    <span class="sr-only">Edit {{ $user->name }}</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"></path>
                                    </svg>
                                </a>

                                @if (! auth()->user()->is($user))
                                    <form method="POST" action="{{ route('panitia.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini? Data registrasi dan berkas terkait juga akan dihapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            title="Hapus user"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50"
                                        >
                                            <span class="sr-only">Hapus {{ $user->name }}</span>
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M3 6h18"></path>
                                                <path d="M8 6V4h8v2"></path>
                                                <path d="M19 6l-1 14H6L5 6"></path>
                                                <path d="M10 11v5M14 11v5"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">User tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $users->links() }}</div>
</x-panitia-layout>

