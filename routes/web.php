<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/welcome');

// Protected Routes (require auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');
});

// Fallback redirect to login for any unmatched route
Route::fallback(function () {
    return redirect('/login');
});

require __DIR__ . '/auth.php';
