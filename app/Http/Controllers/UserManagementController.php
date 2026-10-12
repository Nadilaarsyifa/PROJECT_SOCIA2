<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('nama')->get();

        return view('admin.users.index', compact('users'));
    }
}
