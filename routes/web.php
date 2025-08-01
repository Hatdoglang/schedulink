<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Requester\ConferenceRoomController;
use App\Http\Controllers\ApprovalController;

use App\Models\User;
use App\Models\AssetType;
use App\Models\Booking;

// Livewire Components
use App\Livewire\AdminStaff\Dashboard as AdminStaffDashboard;
use App\Livewire\AdminStaff\BookingManagement as AdminStaffBookingManagement;

use App\Livewire\Approver\Dashboard as ApproverDashboard;
use App\Livewire\Approver\BookingManagement;
use App\Livewire\Approver\VehicleBookingManagement;

// ✅ Redirect root URL to login
Route::redirect('/', '/login');

// ✅ Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ✅ Calendar view route
Route::get('/calendar', function () {
    $users = User::select('id', 'first_name', 'last_name')->get();
    $assetTypes = AssetType::select('id', 'name')->get();
    return view('bookings.calendar', compact('users', 'assetTypes'));
})->name('calendar');

// ✅ Booking submission
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

// ✅ Booking approval actions
Route::post('/bookings/approve', [ApprovalController::class, 'approve'])->name('bookings.approve');
Route::post('/bookings/reject', [ApprovalController::class, 'reject'])->name('bookings.reject');

// ✅ Conference room view (Requester)
Route::get('/conference-room', [ConferenceRoomController::class, 'index'])->name('requester.conference-room');

// ✅ Authenticated and verified routes
Route::middleware(['auth', 'verified'])->group(function () {

    // ✅ Dashboard role-based redirect
    Route::get('/dashboard', function () {
        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
        return redirect($redirectUrl);
    })->name('dashboard');

    // ✅ Profile Management
    Route::view('/profile', 'profile')->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Approver Routes
    Route::prefix('approver')->name('approver.')->group(function () {
        Route::get('/dashboard', ApproverDashboard::class)->name('dashboard');
        Route::get('/bookings', BookingManagement::class)->name('booking-management');
        Route::get('/vehicle-booking-management', VehicleBookingManagement::class)->name('vehicle-booking-management');
    });

    // ✅ Admin Staff Routes
    Route::prefix('admin-staff')->name('admin-staff.')->group(function () {
        Route::get('/dashboard', AdminStaffDashboard::class)->name('dashboard');

        // Ready for future features:
        Route::get('/bookings', AdminStaffBookingManagement::class)->name('booking-management');
        // ✅ This is the important route:
        Route::get('/bookings/{booking}/print', [BookingController::class, 'print'])
            ->name('bookings.print');


    });
});

// ✅ API route for FullCalendar background events
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
    return [
        'user_id' => $user->id,
        'email' => $user->email,
        'name' => $user->full_name,
        'role_id' => $user->role?->id,
        'role_name' => $user->role?->name,
        'redirect_url' => \App\Services\RoleRedirectService::getRedirectUrl(),
    ];
})->middleware('auth')->name('debug.role');

Route::get('/test-admin-access', function () {
    if (!auth()->check())
        return redirect('/login');
    $role = auth()->user()->role?->name;
    return $role === 'Admin'
        ? ['success' => 'Admin access confirmed!', 'user_role' => $role]
        : ['error' => 'Access denied.', 'user_role' => $role ?? 'none'];
})->middleware('auth')->name('test.admin');

Route::get('/test-profile', function () {
    if (!auth()->check())
        return redirect('/login');
    return [
        'profile_route' => route('profile'),
        'edit_route' => route('profile.edit'),
        'user' => auth()->user()->full_name,
        'status' => 'Profile routes working.',
    ];
})->middleware('auth')->name('test.profile');

// ✅ Fallback to login if route doesn't exist
Route::fallback(function () {
    return redirect('/login');
});

// ✅ Include Breeze/Jetstream auth routes
require __DIR__ . '/auth.php';
