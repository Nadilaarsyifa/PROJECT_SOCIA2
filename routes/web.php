
<?php

use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\KnowledgeBaseController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__ . '/auth.php';

// Dashboard dapat diakses kedua role
Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'role.check:Administrator,SOC Engineer'])
    ->name('dashboard');

// Kelola User hanya dapat diakses Administrator
Route::middleware(['auth', 'role.check:Administrator'])->group(function () {
    Route::get('/admin/users', [UserManagementController::class, 'index'])
        ->name('admin.users.index');
});

// Profile dapat diakses kedua role yang sudah login
Route::middleware(['auth', 'role.check:Administrator,SOC Engineer'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Analyze Ticket untuk Administrator dan SOC Engineer
    Route::middleware(['auth', 'role.check:Administrator,SOC Engineer'])
        ->get('/tickets/analyze', [TicketController::class, 'create'])
        ->name('tickets.create');

    Route::get('/tickets', [TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('/knowledge-base', [KnowledgeBaseController::class, 'index'])
        ->name('knowledge-base.index');
});
