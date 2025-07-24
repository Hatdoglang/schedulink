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

// Test route for profile
Route::get('/test-profile', function () {
    if (!auth()->check()) {
        return 'User not logged in - please login first';
    }
    
    return [
        'profile_route_exists' => route('profile'),
        'profile_edit_route_exists' => route('profile.edit'),
        'user_name' => auth()->user()->full_name,
        'message' => 'Profile routes are working correctly!'
    ];
})->middleware('auth')->name('test.profile');

// Test route for admin navigation
Route::get('/test-admin-nav', function () {
    if (!auth()->check()) {
        return 'User not logged in - please login first';
    }
    
    $user = auth()->user();
    $isAdmin = $user->role && $user->role->name === 'Admin';
    
    return [
        'user_name' => $user->full_name,
        'user_role' => $user->role?->name ?? 'No Role',
        'is_admin' => $isAdmin,
        'admin_dashboard_url' => route('admin.dashboard'),
        'can_access_admin' => $isAdmin ? 'Yes' : 'No',
        'message' => $isAdmin ? 'Admin navigation should work!' : 'Access denied - not an admin'
    ];
})->middleware('auth')->name('test.admin.nav');

// Protected Routes (require auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');
    
    // Profile management routes (traditional forms)
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include role-based routes
require __DIR__ . '/role-based.php';

// Fallback redirect to login for any unmatched route
Route::fallback(function () {
    return redirect('/login');
});

require __DIR__ . '/auth.php';
