<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Requester\ConferenceRoomController;
use App\Models\User;
use App\Models\AssetType;
use App\Models\Booking;
use App\Livewire\Approver\BookingManagement;
use App\Http\Controllers\ApprovalController;

// ✅ Redirect root URL to login
Route::redirect('/', '/login');

// ✅ Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ✅ Calendar view route (used by Blade + FullCalendar)
Route::get('/calendar', function () {
    $users = User::select('id', 'first_name', 'last_name')->get();
    $assetTypes = AssetType::select('id', 'name')->get();
    return view('bookings.calendar', compact('users', 'assetTypes'));
})->name('calendar');


Route::post('/bookings/approve', [ApprovalController::class, 'approve'])->name('bookings.approve');
Route::post('/bookings/reject', [ApprovalController::class, 'reject'])->name('bookings.reject');

// ✅ Booking submission route
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

// ✅ Conference room booking view
Route::get('/conference-room', [ConferenceRoomController::class, 'index'])->name('requester.conference-room');

// ✅ Authenticated and verified routes
Route::middleware(['auth', 'verified'])->group(function () {
    // ✅ Redirect to dashboard based on role (Admin, Approver, Driver, User)
    Route::get('/dashboard', function () {
        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
        return redirect($redirectUrl);
    })->name('dashboard');

    // ✅ Profile management
    Route::view('/profile', 'profile')->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Approver-specific routes
    Route::prefix('approver')->group(function () {
        Route::get('/dashboard', \App\Livewire\Approver\Dashboard::class)->name('approver.dashboard');

        // ✅ Booking management view
        Route::get('/booking-management', BookingManagement::class)->name('approver.booking-management');

    });
});


// ✅ Return full-day bookings as background events for calendar
Route::get('/api/bookings/dates', function () {
    return response()->json(
        Booking::where('time_from', '00:00:00')
            ->where('time_to', '23:59:00')
            ->select('scheduled_date', 'time_from', 'time_to')
            ->get()
            ->map(fn($b) => [
                'title' => 'Booked',
                'start' => $b->scheduled_date->toDateString(),
                'allDay' => true,
                'display' => 'background',
                'color' => '#cc0000',
            ])
            ->values()
    );
});

// ✅ Debug/testing routes (optional)
Route::get('/debug-role', function () {
    if (!auth()->check())
        return redirect('/login');

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
    ];
})->middleware('auth')->name('debug.role');

Route::get('/test-admin-access', function () {
    if (!auth()->check())
        return redirect('/login');

    $user = auth()->user();
    $role = $user->role;

    if (!$role || $role->name !== 'Admin') {
        return [
            'error' => 'Access denied. Admin role required.',
            'user_role' => $role?->name ?? 'No role assigned',
        ];
    }

    return [
        'success' => 'Admin access confirmed!',
        'user_role' => $role->name,
        'admin_dashboard_url' => '/admin/dashboard',
    ];
})->middleware('auth')->name('test.admin');

Route::get('/test-profile', function () {
    if (!auth()->check())
        return redirect('/login');

    return [
        'profile_route_exists' => route('profile'),
        'profile_edit_route_exists' => route('profile.edit'),
        'user_name' => auth()->user()->full_name,
        'message' => 'Profile routes are working correctly!',
    ];
})->middleware('auth')->name('test.profile');

Route::get('/test-admin-dashboard', function () {
    if (!auth()->check())
        return redirect('/login');

    $user = auth()->user();
    $role = $user->role;

    if (!$role || $role->name !== 'Admin') {
        return [
            'error' => 'Access denied. Admin role required.',
            'user_role' => $role?->name ?? 'No role assigned',
            'redirect_to_login' => true,
        ];
    }

    return [
        'success' => 'Admin dashboard access test passed!',
        'user_role' => $role->name,
        'admin_dashboard_url' => '/admin/dashboard',
    ];
})->middleware('auth')->name('test.admin.dashboard');

// ✅ Fallback: redirect unmatched routes to login
Route::fallback(function () {
    return redirect('/login');
});

// ✅ Include Laravel Breeze or Jetstream auth routes
require __DIR__ . '/auth.php';

