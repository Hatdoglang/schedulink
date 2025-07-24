<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

// ✅ Redirect root URL to login
Route::redirect('/', '/login');

// ✅ Logout route (POST) using controller method that redirects to /login
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ✅ Role redirection test (requires login)
Route::get('/debug-role', function () {
    if (!auth()->check()) {
        return redirect('/login');
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

// ✅ Test admin access
Route::get('/test-admin-access', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    $user = auth()->user();
    $role = $user->role;
    
    if (!$role || $role->name !== 'Admin') {
        return [
            'error' => 'Access denied. Admin role required.',
            'user_role' => $role?->name ?? 'No role assigned',
            'user_id' => $user->id,
        ];
    }

    return [
        'success' => 'Admin access confirmed!',
        'user_role' => $role->name,
        'user_id' => $user->id,
        'admin_dashboard_url' => '/admin/dashboard',
    ];
})->middleware('auth')->name('test.admin');

// ✅ Test profile route (requires login)
Route::get('/test-profile', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    return [
        'profile_route_exists' => route('profile'),
        'profile_edit_route_exists' => route('profile.edit'),
        'user_name' => auth()->user()->full_name,
        'message' => 'Profile routes are working correctly!',
    ];
})->middleware('auth')->name('test.profile');

// ✅ Authenticated and verified routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Fallback dashboard for users without specific roles
    Route::get('/dashboard', function () {
        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
        return redirect($redirectUrl);
    })->name('dashboard');
    
    Route::view('/profile', 'profile')->name('profile');

    // Profile management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Fallback route: redirect all unmatched URLs to login
Route::fallback(function () {
    return redirect('/login');
});

// ✅ Include auth scaffolding (from Breeze/Jetstream/etc.)
require __DIR__ . '/auth.php';
