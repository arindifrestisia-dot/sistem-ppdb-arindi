<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PanitiaUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $role = (string) $request->query('role');

        $users = User::query()
            ->with(['studentRegistration', 'ppdbFormPayment'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('studentRegistration', function ($query) use ($search) {
                            $query
                                ->where('full_name', 'like', "%{$search}%")
                                ->orWhere('registration_number', 'like', "%{$search}%");
                        });
                });
            })
            ->when(array_key_exists($role, $this->roleOptions()), fn ($query) => $query->where('role', $role))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.panitia.users.index', [
            'users' => $users,
            'roleOptions' => $this->roleOptions(),
            'filters' => compact('search', 'role'),
        ]);
    }

    public function create(): View
    {
        return view('dashboard.panitia.users.create', [
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower((string) $request->input('email')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash:ascii', 'min:4', 'max:50', Rule::unique(User::class)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'role' => ['required', Rule::in(array_keys($this->roleOptions()))],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('panitia.users.index')
            ->with('status', 'User baru berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('dashboard.panitia.users.edit', [
            'managedUser' => $user,
            'roleOptions' => $this->roleOptions(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower((string) $request->input('email')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash:ascii', 'min:4', 'max:50', Rule::unique(User::class)->ignore($user)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user)],
            'role' => ['required', Rule::in(array_keys($this->roleOptions()))],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->user()->is($user) && $validated['role'] !== $user->role) {
            throw ValidationException::withMessages([
                'role' => 'Role akun yang sedang digunakan tidak dapat diubah.',
            ]);
        }

        $user->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (filled($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('panitia.users.index')
            ->with('status', 'Data user berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return redirect()
                ->route('panitia.users.index')
                ->withErrors(['user' => 'Akun yang sedang digunakan tidak dapat dihapus.']);
        }

        $registrationFiles = $user->studentRegistration
            ? collect([
                $user->studentRegistration->child_photo_path,
                $user->studentRegistration->parents_id_card_path,
                $user->studentRegistration->birth_certificate_path,
                $user->studentRegistration->family_card_path,
            ])->filter()->values()->all()
            : [];

        $user->delete();

        if ($registrationFiles !== []) {
            Storage::disk('public')->delete($registrationFiles);
        }

        return redirect()
            ->route('panitia.users.index')
            ->with('status', 'User berhasil dihapus.');
    }

    private function roleOptions(): array
    {
        return [
            User::ROLE_PARENT => 'Orang Tua',
            User::ROLE_COMMITTEE => 'Panitia PPDB',
            User::ROLE_PRINCIPAL => 'Kepala Sekolah',
        ];
    }
}
