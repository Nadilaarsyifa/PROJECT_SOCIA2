
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



// Kelola User dan Delete Ticket hanya dapat diakses Administrator

Route::middleware(['auth', 'role.check:Administrator'])->group(function () {
    // Daftar user
    Route::get('/admin/users', [UserManagementController::class, 'index'])
        ->name('admin.users.index');

    // Reset password
    Route::post('/admin/users/{user}/reset-password', [
        UserManagementController::class,
        'resetPassword',
    ])->name('users.reset-password');

    // Hapus user
    Route::delete('/admin/users/{user}', [
        UserManagementController::class,
        'destroy',
    ])->name('users.destroy');

    // Hapus ticket
    Route::delete('/tickets/{ticketNo}', [TicketController::class, 'destroy'])
        ->name('tickets.destroy');
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
    Route::get('/tickets/analyze', [TicketController::class, 'create'])
        ->name('tickets.create');

    Route::post('/tickets/analyze', [TicketController::class, 'store'])
        ->name('tickets.store');

    // Daftar semua ticket
    Route::get('/tickets', [TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('/knowledge-base', [KnowledgeBaseController::class, 'index'])
        ->name('knowledge-base.index');

    Route::patch('/tickets/{ticketNo}/checklist', [
        \App\Http\Controllers\ActionChecklistController::class,
        'update',
    ])->name('action-checklists.update');

    Route::post('/tickets/analyze/reset', [
        \App\Http\Controllers\TicketController::class,
        'reset',
    ])->name('tickets.reset');
});
