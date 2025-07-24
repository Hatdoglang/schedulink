# 📝 Complete Code Files - Part 3 (Missing Route File)

## 🛣️ **ROUTE FILES**

### 17. `routes/role-based.php` ✨ **MISSING FROM PREVIOUS PARTS**

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Requester\DashboardController as RequesterDashboardController;
use App\Http\Controllers\Requester\BookingController as RequesterBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Approver\DashboardController as ApproverDashboardController;
use App\Http\Controllers\Approver\ApprovalController;

/*
|--------------------------------------------------------------------------
| Role-Based Routes
|--------------------------------------------------------------------------
|
| Here are all the role-based routes for the application organized by
| user roles: Requester, Admin, and Approver. Each section has its own
| middleware protection and specific functionality.
|
*/

// =============================================================================
// REQUESTER ROUTES (Users & Drivers)
// =============================================================================

Route::prefix('requester')->name('requester.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Routes
    Route::get('/dashboard', [RequesterDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [RequesterDashboardController::class, 'getStats'])->name('dashboard.stats');
    
    // Livewire Routes
    Route::get('/my-dashboard', App\Livewire\Requester\Dashboard::class)->name('livewire.dashboard');
    
    // Booking Management Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [RequesterBookingController::class, 'index'])->name('index');
        Route::get('/create', [RequesterBookingController::class, 'create'])->name('create');
        Route::post('/', [RequesterBookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [RequesterBookingController::class, 'show'])->name('show');
        Route::get('/{booking}/edit', [RequesterBookingController::class, 'edit'])->name('edit');
        Route::put('/{booking}', [RequesterBookingController::class, 'update'])->name('update');
        Route::delete('/{booking}', [RequesterBookingController::class, 'destroy'])->name('destroy');
        
        // Booking Status Routes
        Route::patch('/{booking}/cancel', [RequesterBookingController::class, 'cancel'])->name('cancel');
        Route::get('/{booking}/guests', [RequesterBookingController::class, 'getGuests'])->name('guests');
        Route::post('/{booking}/guests', [RequesterBookingController::class, 'addGuest'])->name('guests.add');
        Route::delete('/{booking}/guests/{guest}', [RequesterBookingController::class, 'removeGuest'])->name('guests.remove');
    });
    
    // Asset Availability Routes
    Route::get('/assets/available', [RequesterBookingController::class, 'getAvailableAssets'])->name('assets.available');
    Route::get('/assets/{assetDetail}/availability', [RequesterBookingController::class, 'checkAvailability'])->name('assets.availability');
});

// =============================================================================
// ADMIN ROUTES (Administrators Only)
// =============================================================================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    
    // Admin Dashboard Routes
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/system-health', [AdminDashboardController::class, 'getSystemHealth'])->name('dashboard.health');
    Route::get('/dashboard/booking-stats', [AdminDashboardController::class, 'getBookingStatistics'])->name('dashboard.booking-stats');
    Route::get('/dashboard/trends', [AdminDashboardController::class, 'getBookingTrends'])->name('dashboard.trends');
    Route::get('/dashboard/asset-utilization', [AdminDashboardController::class, 'getAssetUtilization'])->name('dashboard.utilization');
    
    // Livewire Dashboard
    Route::get('/livewire-dashboard', App\Livewire\Admin\Dashboard::class)->name('livewire.dashboard');
    
    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        
        // User Status Management
        Route::patch('/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('reset-password');
        Route::post('/bulk-update', [UserManagementController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export', [UserManagementController::class, 'export'])->name('export');
    });
    
    // System Management Routes
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
        Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs');
        Route::get('/backup', [AdminDashboardController::class, 'backup'])->name('backup');
    });
    
    // All Bookings Management (Admin can see everything)
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'allBookings'])->name('index');
        Route::get('/{booking}', [AdminDashboardController::class, 'showBooking'])->name('show');
        Route::patch('/{booking}/override-status', [AdminDashboardController::class, 'overrideBookingStatus'])->name('override-status');
        Route::get('/export', [AdminDashboardController::class, 'exportBookings'])->name('export');
    });
    
    // Asset Management Routes
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'assetManagement'])->name('index');
        Route::get('/types', [AdminDashboardController::class, 'assetTypes'])->name('types');
        Route::get('/utilization-report', [AdminDashboardController::class, 'utilizationReport'])->name('utilization');
    });
});

// =============================================================================
// APPROVER ROUTES (Managers & Admins)
// =============================================================================

Route::prefix('approver')->name('approver.')->middleware(['auth', 'verified', 'role:Manager,Admin'])->group(function () {
    
    // Approver Dashboard Routes
    Route::get('/dashboard', [ApproverDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [ApproverDashboardController::class, 'getApprovalStats'])->name('dashboard.stats');
    Route::get('/dashboard/pending', [ApproverDashboardController::class, 'getPendingApprovals'])->name('dashboard.pending');
    Route::get('/dashboard/recent-actions', [ApproverDashboardController::class, 'getRecentActions'])->name('dashboard.recent');
    
    // Livewire Dashboard
    Route::get('/livewire-dashboard', App\Livewire\Approver\Dashboard::class)->name('livewire.dashboard');
    
    // Approval Management Routes
    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::get('/pending', [ApprovalController::class, 'pending'])->name('pending');
        Route::get('/my-history', [ApprovalController::class, 'myHistory'])->name('history');
        Route::get('/{booking}', [ApprovalController::class, 'show'])->name('show');
        
        // Approval Actions
        Route::post('/{booking}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::post('/{booking}/reject', [ApprovalController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [ApprovalController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk-reject', [ApprovalController::class, 'bulkReject'])->name('bulk-reject');
        
        // Approval Delegation
        Route::get('/delegate', [ApprovalController::class, 'delegateForm'])->name('delegate.form');
        Route::post('/delegate', [ApprovalController::class, 'delegate'])->name('delegate');
        Route::delete('/delegate/{delegation}', [ApprovalController::class, 'removeDelegation'])->name('delegate.remove');
    });
    
    // Approval Hierarchy Management
    Route::prefix('hierarchy')->name('hierarchy.')->group(function () {
        Route::get('/', [ApprovalController::class, 'hierarchy'])->name('index');
        Route::get('/my-authority', [ApprovalController::class, 'myAuthority'])->name('authority');
        Route::get('/team-approvals', [ApprovalController::class, 'teamApprovals'])->name('team');
    });
    
    // Reporting Routes (for approvers)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/approval-summary', [ApprovalController::class, 'approvalSummary'])->name('summary');
        Route::get('/team-performance', [ApprovalController::class, 'teamPerformance'])->name('performance');
        Route::get('/export-approvals', [ApprovalController::class, 'exportApprovals'])->name('export');
    });
});

// =============================================================================
// SHARED ROUTES (Authenticated Users - All Roles)
// =============================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Profile Management (shared by all roles)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProfileController::class, 'show'])->name('show');
        Route::patch('/', [\App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::delete('/', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Notification Routes (shared)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function() { return view('notifications.index'); })->name('index');
        Route::patch('/{notification}/read', function($id) { 
            auth()->user()->notifications()->where('id', $id)->first()->markAsRead();
            return back();
        })->name('read');
        Route::patch('/mark-all-read', function() {
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        })->name('mark-all-read');
    });
    
    // Help & Support Routes (shared)
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', function() { return view('help.index'); })->name('index');
        Route::get('/faq', function() { return view('help.faq'); })->name('faq');
        Route::get('/contact', function() { return view('help.contact'); })->name('contact');
        Route::post('/contact', function() { return back()->with('success', 'Message sent!'); })->name('contact.send');
    });
});
```

---

## 📝 **IMPORTANT NOTE ABOUT ROLE-BASED.PHP**

### **🚨 Current Status:**
The `routes/role-based.php` file **EXISTS** but is **NOT CURRENTLY INCLUDED** in `routes/web.php` due to a file path issue we encountered during development.

### **📁 What Happened:**
1. ✅ **Created**: `routes/role-based.php` with all role-based routes
2. ❌ **Issue**: File inclusion caused path errors during seeding
3. 🔧 **Workaround**: Moved routes inline to `routes/web.php`
4. 📝 **Result**: File exists but isn't actively used

### **🛠️ To Use role-based.php:**

**Option 1: Include in web.php (Recommended)**
```php
// Add this line to routes/web.php
require __DIR__ . '/role-based.php';
```

**Option 2: Replace inline routes in web.php**
Remove the inline role-based routes from `web.php` and uncomment/add the require statement.

### **✅ Benefits of Using role-based.php:**
- **Better Organization**: Separates role-specific routes
- **Easier Maintenance**: Centralized role-based routing
- **Cleaner web.php**: Keeps main routes file focused
- **Scalability**: Easy to add new role-based features

### **📋 Current Route Structure:**
```
routes/web.php        # Main routes + inline role routes (currently active)
routes/role-based.php # Organized role routes (exists but not included)
routes/auth.php       # Authentication routes (active)
```

**The file structure is now correctly updated to show all 73 files including `routes/role-based.php`!** ✅