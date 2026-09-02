<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\Tech\TicketHandlingController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route yang membutuhkan Autentikasi (Sudah Login)
Route::middleware(['auth'])->group(function () {

    // Fitur User / Karyawan
    Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    });

    // Fitur Teknisi & Admin
    Route::middleware(['role:technician,admin'])->prefix('tech')->name('tech.')->group(function () {
        Route::get('/tickets', [TicketHandlingController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{id}', [TicketHandlingController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{id}/status', [TicketHandlingController::class, 'updateStatus'])->name('tickets.update-status');
    });

    // Fitur Khusus Admin (Master Data & Pengaturan)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/categories', function () {
            return 'Halaman Kelola Kategori Kendala';
        })->name('categories.index');

        Route::get('/users', function () {
            return 'Halaman Kelola Pengguna (User/Teknisi)';
        })->name('users.index');

        Route::patch('/tickets/{id}/assign', [TicketHandlingController::class, 'assignTechnician'])->name('tickets.assign');
    });

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';