<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use App\Models\AssetType;
use App\Livewire\Requester\Bookings; // ✅ Import Livewire component
use App\Http\Controllers\BookingController;

// ✅ Redirect root URL to login
Route::redirect('/', '/login');

// ✅ Logout route (POST) using controller method that redirects to /login
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ✅ Calendar route
Route::get('/calendar', function () {
    $users = User::select('id', 'first_name', 'last_name')->get();
    $assetTypes = AssetType::select('id', 'name')->get();
    return view('bookings.calendar', compact('users', 'assetTypes'));
});

Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');


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

// ✅ Test admin dashboard access directly
Route::get('/test-admin-dashboard', function () {
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
            'redirect_to_login' => true,
        ];
    }

    return [
        'success' => 'Admin dashboard access test passed!',
        'user_role' => $role->name,
        'user_id' => $user->id,
        'admin_dashboard_url' => '/admin/dashboard',
        'can_access_admin_routes' => true,
        'next_step' => 'Try accessing /admin/dashboard directly',
    ];
})->middleware('auth')->name('test.admin.dashboard');

// ✅ Authenticated and verified routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect to role-specific dashboard
    Route::get('/dashboard', function () {
        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
        return redirect($redirectUrl);
    })->name('dashboard');

    // Profile management
    Route::view('/profile', 'profile')->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ BOOKINGS Livewire route
    Route::get('/bookings', Bookings::class)->name('bookings');
});

// ✅ Fallback route: redirect all unmatched URLs to login
Route::fallback(function () {
    return redirect('/login');
});

// ✅ Include auth scaffolding
require __DIR__ . '/auth.php';
