<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('nama')->get();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse

    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9._-]+$/',
                'unique:users,username',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, tanda strip, dan garis bawah.',
            'username.unique' => 'Username sudah digunakan oleh pengguna lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
        $user = User::create([
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'SOC Engineer',
        ]);

        $user->assignRole('SOC Engineer');

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna SOC Engineer berhasil ditambahkan.');
    }

    public function resetPassword(User $user): RedirectResponse
    {
        $temporaryPassword = Str::random(12);

        $user->password = Hash::make($temporaryPassword);
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Password pengguna berhasil direset.')
            ->with('temporary_password', $temporaryPassword)
            ->with('temporary_username', $user->username);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Administrator tidak boleh menghapus akunnya sendiri.
        if ($request->user()->is($user)) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Kamu tidak dapat menghapus akun sendiri.');
        }

        // Lindungi Administrator terakhir.
        if (
            $user->hasRole('Administrator')
            && User::role('Administrator')->count() <= 1
        ) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Administrator terakhir tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
