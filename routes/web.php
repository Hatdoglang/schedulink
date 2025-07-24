<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::redirect('/', '/welcome');

// Debug route for testing role redirection
Route::get('/debug-role', function () {
    if (!auth()->check()) {
        return 'User not logged in';
    }
    
    $user = auth()->user();
    $role = $user->role;
    $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
    
    return [
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_name' => $user->full_name,
        'role_id' => $role?->id,
        'role_name' => $role?->name,
        'redirect_url' => $redirectUrl,
        'expected_admin_url' => '/admin/dashboard',
    ];
})->middleware('auth')->name('debug.role');

// Protected Routes (require auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');
});

// Include role-based routes
require __DIR__ . '/role-based.php';

// Fallback redirect to login for any unmatched route
Route::fallback(function () {
    return redirect('/login');
});

require __DIR__ . '/auth.php';
