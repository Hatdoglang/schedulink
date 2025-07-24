<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/welcome');

// Protected Routes (require auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');
});

// Role-based routes (inline to avoid path issues)

// Requester Routes
Route::prefix('requester')->name('requester.')->middleware(['auth', 'verified'])->group(function () {
    // Livewire Routes
    Route::get('/my-dashboard', App\Livewire\Requester\Dashboard::class)->name('livewire.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    // Livewire Dashboard
    Route::get('/livewire-dashboard', App\Livewire\Admin\Dashboard::class)->name('livewire.dashboard');
});

// Approver Routes
Route::prefix('approver')->name('approver.')->middleware(['auth', 'verified', 'role:Manager,Admin'])->group(function () {
    // Livewire Dashboard
    Route::get('/livewire-dashboard', App\Livewire\Approver\Dashboard::class)->name('livewire.dashboard');
});

// Fallback redirect to login for any unmatched route
Route::fallback(function () {
    return redirect('/login');
});

require __DIR__ . '/auth.php';