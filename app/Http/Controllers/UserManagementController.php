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
