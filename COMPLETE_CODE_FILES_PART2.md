# 📝 Complete Code Files - Part 2

## 🛡️ **MIDDLEWARE & SERVICES**

### 10. `app/Http/Middleware/RoleMiddleware.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has an assigned role
        if (!$user->role) {
            abort(403, 'Access denied. No role assigned.');
        }

        $userRole = $user->role->name;

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $roles)) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
```

### 11. `app/Services/RoleRedirectService.php`
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class RoleRedirectService
{
    /**
     * Get the appropriate redirect URL based on user role
     */
    public static function getRedirectUrl(): string
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return '/dashboard'; // Default fallback
        }

        $roleName = $user->role->name;

        return match($roleName) {
            'Admin' => '/admin/livewire-dashboard',
            'Manager' => '/approver/livewire-dashboard', 
            'Driver' => '/requester/my-dashboard',
            'User' => '/requester/my-dashboard',
            default => '/dashboard'
        };
    }

    /**
     * Get the appropriate Livewire component based on user role
     */
    public static function getDashboardComponent(): string
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return 'dashboard'; // Default fallback
        }

        $roleName = $user->role->name;

        return match($roleName) {
            'Admin' => 'admin.dashboard',
            'Manager' => 'approver.dashboard', 
            'Driver' => 'requester.dashboard',
            'User' => 'requester.dashboard',
            default => 'dashboard'
        };
    }

    /**
     * Check if user has access to a specific role section
     */
    public static function hasRoleAccess(string $role): bool
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return false;
        }

        $userRole = $user->role->name;
        $allowedRoles = explode(',', $role);

        return in_array($userRole, $allowedRoles);
    }

    /**
     * Get role-specific menu items
     */
    public static function getMenuItems(): array
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return [];
        }

        $roleName = $user->role->name;

        return match($roleName) {
            'Admin' => [
                ['name' => 'Admin Dashboard', 'url' => '/admin/dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Manage Users', 'url' => '/admin/users', 'icon' => 'fas fa-users'],
                ['name' => 'All Bookings', 'url' => '/admin/bookings', 'icon' => 'fas fa-calendar-alt'],
                ['name' => 'Manage Assets', 'url' => '/admin/asset-types', 'icon' => 'fas fa-car'],
                ['name' => 'Analytics', 'url' => '/admin/analytics', 'icon' => 'fas fa-chart-line'],
                ['name' => 'Approvers', 'url' => '/admin/approvers', 'icon' => 'fas fa-user-check'],
            ],
            'Manager' => [
                ['name' => 'Approver Dashboard', 'url' => '/approver/dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Pending Approvals', 'url' => '/approver/approvals/pending', 'icon' => 'fas fa-clock'],
                ['name' => 'Approval History', 'url' => '/approver/approvals/my-history', 'icon' => 'fas fa-history'],
                ['name' => 'My Bookings', 'url' => '/requester/my-dashboard', 'icon' => 'fas fa-calendar'],
            ],
            'Driver' => [
                ['name' => 'My Dashboard', 'url' => '/requester/my-dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'My Bookings', 'url' => '/requester/bookings', 'icon' => 'fas fa-calendar'],
                ['name' => 'New Booking', 'url' => '/requester/bookings/create', 'icon' => 'fas fa-plus'],
            ],
            'User' => [
                ['name' => 'My Dashboard', 'url' => '/requester/my-dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'My Bookings', 'url' => '/requester/bookings', 'icon' => 'fas fa-calendar'],
                ['name' => 'New Booking', 'url' => '/requester/bookings/create', 'icon' => 'fas fa-plus'],
            ],
            default => []
        };
    }

    /**
     * Get the user's role display name
     */
    public static function getRoleDisplayName(): string
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return 'User';
        }

        return $user->role->name;
    }
}
```

---

## 🖥️ **LIVEWIRE DASHBOARD COMPONENTS**

### 12. `app/Livewire/Requester/Dashboard.php`
```php
<?php

namespace App\Livewire\Requester;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $bookingStats = [];
    public $recentBookings = [];
    public $upcomingBookings = [];

    public function mount()
    {
        $this->loadBookingStats();
        $this->loadRecentBookings();
        $this->loadUpcomingBookings();
    }

    public function loadBookingStats()
    {
        $userId = Auth::id();
        
        $this->bookingStats = [
            'total' => Booking::where('user_id', $userId)->count(),
            'pending' => Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            'approved' => Booking::where('user_id', $userId)->where('status', 'approved')->count(),
            'rejected' => Booking::where('user_id', $userId)->where('status', 'rejected')->count(),
            'cancelled' => Booking::where('user_id', $userId)->where('status', 'cancelled')->count(),
        ];
    }

    public function loadRecentBookings()
    {
        $this->recentBookings = Booking::with(['assetDetail.assetType'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function loadUpcomingBookings()
    {
        $this->upcomingBookings = Booking::with(['assetDetail.assetType'])
            ->where('user_id', Auth::id())
            ->where('booking_date', '>=', now())
            ->where('status', 'approved')
            ->orderBy('booking_date', 'asc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.requester.dashboard', [
            'bookingStats' => $this->bookingStats,
            'recentBookings' => $this->recentBookings,
            'upcomingBookings' => $this->upcomingBookings,
        ]);
    }
}
```

### 13. `app/Livewire/Admin/Dashboard.php`
```php
<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\User;
use App\Models\AssetDetail;
use App\Models\AssetType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $systemHealth = [];
    public $bookingStatistics = [];
    public $recentBookings = [];
    public $bookingTrends = [];
    public $mostActiveUsers = [];
    public $assetUtilization = [];

    public function mount()
    {
        $this->loadSystemHealth();
        $this->loadBookingStatistics();
        $this->loadRecentBookings();
        $this->loadBookingTrends();
        $this->loadMostActiveUsers();
        $this->loadAssetUtilization();
    }

    public function loadSystemHealth()
    {
        $this->systemHealth = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_assets' => AssetDetail::count(),
            'available_assets' => AssetDetail::where('is_available', true)->count(),
            'total_bookings' => Booking::count(),
            'pending_approvals' => Booking::where('status', 'pending')->count(),
        ];
    }

    public function loadBookingStatistics()
    {
        $this->bookingStatistics = [
            'today' => Booking::whereDate('created_at', today())->count(),
            'this_week' => Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Booking::whereMonth('created_at', now()->month)->count(),
            'approved_today' => Booking::where('status', 'approved')->whereDate('updated_at', today())->count(),
            'rejected_today' => Booking::where('status', 'rejected')->whereDate('updated_at', today())->count(),
        ];
    }

    public function loadRecentBookings()
    {
        $this->recentBookings = Booking::with(['user', 'assetDetail.assetType'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    public function loadBookingTrends()
    {
        $this->bookingTrends = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();
    }

    public function loadMostActiveUsers()
    {
        $this->mostActiveUsers = User::select('users.*', DB::raw('COUNT(bookings.id) as booking_count'))
            ->leftJoin('bookings', 'users.id', '=', 'bookings.user_id')
            ->groupBy('users.id')
            ->orderBy('booking_count', 'desc')
            ->take(5)
            ->get();
    }

    public function loadAssetUtilization()
    {
        $this->assetUtilization = AssetType::select('asset_types.*', 
                DB::raw('COUNT(bookings.id) as booking_count'),
                DB::raw('COUNT(asset_details.id) as total_assets')
            )
            ->leftJoin('asset_details', 'asset_types.id', '=', 'asset_details.asset_type_id')
            ->leftJoin('bookings', 'asset_details.id', '=', 'bookings.asset_detail_id')
            ->groupBy('asset_types.id')
            ->orderBy('booking_count', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'systemHealth' => $this->systemHealth,
            'bookingStatistics' => $this->bookingStatistics,
            'recentBookings' => $this->recentBookings,
            'bookingTrends' => $this->bookingTrends,
            'mostActiveUsers' => $this->mostActiveUsers,
            'assetUtilization' => $this->assetUtilization,
        ]);
    }
}
```

### 14. `app/Livewire/Approver/Dashboard.php`
```php
<?php

namespace App\Livewire\Approver;

use App\Models\Booking;
use App\Models\ApprovalLog;
use App\Models\Approver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $approvalStats = [];
    public $pendingApprovals = [];
    public $recentActions = [];
    public $approvalHierarchy = [];

    public function mount()
    {
        $this->loadApprovalStats();
        $this->loadPendingApprovals();
        $this->loadRecentActions();
        $this->loadApprovalHierarchy();
    }

    public function loadApprovalStats()
    {
        $userId = Auth::id();
        
        $this->approvalStats = [
            'pending_approvals' => Booking::whereHas('approvers', function($query) use ($userId) {
                $query->where('user_id', $userId)->where('is_approved', false);
            })->where('status', 'pending')->count(),
            
            'approved_today' => ApprovalLog::where('approver_id', $userId)
                ->where('status', 'approved')
                ->whereDate('created_at', today())
                ->count(),
                
            'rejected_today' => ApprovalLog::where('approver_id', $userId)
                ->where('status', 'rejected')
                ->whereDate('created_at', today())
                ->count(),
                
            'total_processed' => ApprovalLog::where('approver_id', $userId)->count(),
        ];
    }

    public function loadPendingApprovals()
    {
        $userId = Auth::id();
        
        $this->pendingApprovals = Booking::with(['user', 'assetDetail.assetType'])
            ->whereHas('approvers', function($query) use ($userId) {
                $query->where('user_id', $userId)->where('is_approved', false);
            })
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->take(10)
            ->get();
    }

    public function loadRecentActions()
    {
        $userId = Auth::id();
        
        $this->recentActions = ApprovalLog::with(['booking.user', 'booking.assetDetail.assetType'])
            ->where('approver_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    public function loadApprovalHierarchy()
    {
        $userId = Auth::id();
        
        $this->approvalHierarchy = Approver::with(['user', 'assetType'])
            ->where('user_id', $userId)
            ->get();
    }

    public function approveBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        // Update approver status
        $approver = $booking->approvers()->where('user_id', Auth::id())->first();
        if ($approver) {
            $approver->update(['is_approved' => true]);
            
            // Log the approval
            ApprovalLog::create([
                'booking_id' => $booking->id,
                'approver_id' => Auth::id(),
                'status' => 'approved',
                'comments' => 'Approved via dashboard',
            ]);
            
            // Check if all approvers have approved
            $allApproved = $booking->approvers()->where('is_approved', false)->count() === 0;
            if ($allApproved) {
                $booking->update(['status' => 'approved']);
            }
            
            $this->loadApprovalStats();
            $this->loadPendingApprovals();
            $this->loadRecentActions();
            
            session()->flash('message', 'Booking approved successfully.');
        }
    }

    public function rejectBooking($bookingId, $reason = 'Rejected via dashboard')
    {
        $booking = Booking::findOrFail($bookingId);
        
        // Update booking status to rejected
        $booking->update(['status' => 'rejected']);
        
        // Log the rejection
        ApprovalLog::create([
            'booking_id' => $booking->id,
            'approver_id' => Auth::id(),
            'status' => 'rejected',
            'comments' => $reason,
        ]);
        
        $this->loadApprovalStats();
        $this->loadPendingApprovals();
        $this->loadRecentActions();
        
        session()->flash('message', 'Booking rejected successfully.');
    }

    public function render()
    {
        return view('livewire.approver.dashboard', [
            'approvalStats' => $this->approvalStats,
            'pendingApprovals' => $this->pendingApprovals,
            'recentActions' => $this->recentActions,
            'approvalHierarchy' => $this->approvalHierarchy,
        ]);
    }
}
```

---

## 🎨 **LIVEWIRE DASHBOARD VIEWS**

### 15. `resources/views/livewire/requester/dashboard.blade.php`
```php
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Total Bookings</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $bookingStats['total'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Pending</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $bookingStats['pending'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Approved</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $bookingStats['approved'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Rejected</h3>
                        <p class="text-3xl font-bold text-red-600">{{ $bookingStats['rejected'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Cancelled</h3>
                        <p class="text-3xl font-bold text-gray-600">{{ $bookingStats['cancelled'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Recent Bookings</h3>
                        
                        @if(count($recentBookings) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left">Asset</th>
                                            <th class="px-4 py-2 text-left">Date</th>
                                            <th class="px-4 py-2 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBookings as $booking)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">
                                                    {{ $booking->assetDetail->assetType->name ?? 'N/A' }} - 
                                                    {{ $booking->assetDetail->asset_name ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-2">{{ $booking->booking_date->format('M d, Y') }}</td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 text-xs rounded-full 
                                                        @if($booking->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No recent bookings found.</p>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Upcoming Bookings</h3>
                        
                        @if(count($upcomingBookings) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left">Asset</th>
                                            <th class="px-4 py-2 text-left">Date</th>
                                            <th class="px-4 py-2 text-left">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingBookings as $booking)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">
                                                    {{ $booking->assetDetail->assetType->name ?? 'N/A' }} - 
                                                    {{ $booking->assetDetail->asset_name ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-2">{{ $booking->booking_date->format('M d, Y') }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $booking->start_time }} - {{ $booking->end_time }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No upcoming bookings found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                New Booking
                            </button>
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                View All Bookings
                            </button>
                            <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Booking History
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 16. `resources/views/livewire/admin/dashboard.blade.php`
```php
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- System Health Cards -->
            <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Total Users</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $systemHealth['total_users'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Active Users</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $systemHealth['active_users'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Total Assets</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $systemHealth['total_assets'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Available</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $systemHealth['available_assets'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Total Bookings</h3>
                        <p class="text-2xl font-bold text-orange-600">{{ $systemHealth['total_bookings'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Pending</h3>
                        <p class="text-2xl font-bold text-yellow-600">{{ $systemHealth['pending_approvals'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Today</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $bookingStatistics['today'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">This Week</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $bookingStatistics['this_week'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">This Month</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $bookingStatistics['this_month'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Approved Today</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $bookingStatistics['approved_today'] ?? 0 }}</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900">
                        <h3 class="text-sm font-semibold mb-2">Rejected Today</h3>
                        <p class="text-2xl font-bold text-red-600">{{ $bookingStatistics['rejected_today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Recent Bookings</h3>
                        
                        @if(count($recentBookings) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left">User</th>
                                            <th class="px-4 py-2 text-left">Asset</th>
                                            <th class="px-4 py-2 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBookings as $booking)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">{{ $booking->user->full_name ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $booking->assetDetail->assetType->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 text-xs rounded-full 
                                                        @if($booking->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No recent bookings found.</p>
                        @endif
                    </div>
                </div>

                <!-- Most Active Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Most Active Users</h3>
                        
                        @if(count($mostActiveUsers) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left">User</th>
                                            <th class="px-4 py-2 text-left">Role</th>
                                            <th class="px-4 py-2 text-left">Bookings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mostActiveUsers as $user)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">{{ $user->full_name }}</td>
                                                <td class="px-4 py-2">{{ $user->role->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">
                                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 text-xs rounded-full">
                                                        {{ $user->booking_count }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No user data found.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Manage Users
                            </button>
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                View All Bookings
                            </button>
                            <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Asset Management
                            </button>
                            <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                Analytics
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

Would you like me to continue with Part 3 which will include the Approver dashboard view, key models, and seeders? The complete file set is quite extensive with 72 files total.