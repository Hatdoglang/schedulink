<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\User;
use App\Models\AssetDetail;
use App\Models\AssetType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public $systemHealth = [];
    public $bookingStatistics = [];
    public $recentBookings = [];
    public $bookingTrends = [];
    public $mostActiveUsers = [];
    public $assetUtilization = [];
    public $upcomingBookings = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Overall system statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalAssets = AssetDetail::count();
        $totalBookings = Booking::count();

        // Booking statistics
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $rejectedBookings = Booking::where('status', 'rejected')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        $this->systemHealth = [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'total_assets' => $totalAssets,
            'total_bookings' => $totalBookings,
            'utilization_rate' => $totalAssets > 0 ? round(($approvedBookings / $totalAssets) * 100, 2) : 0,
        ];

        $this->bookingStatistics = [
            'total' => $totalBookings,
            'pending' => $pendingBookings,
            'approved' => $approvedBookings,
            'rejected' => $rejectedBookings,
            'cancelled' => $cancelledBookings,
        ];

        // Recent bookings
        $this->recentBookings = Booking::with(['user', 'assetType', 'assetDetail'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Booking trends (last 30 days)
        $this->bookingTrends = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Most active users (top 5)
        $this->mostActiveUsers = User::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Asset utilization by type
        $this->assetUtilization = AssetType::select('asset_types.name')
            ->withCount(['bookings as total_bookings'])
            ->withCount(['bookings as approved_bookings' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        // Upcoming bookings requiring attention
        $this->upcomingBookings = Booking::with(['user', 'assetType', 'assetDetail'])
            ->where('scheduled_date', '>=', now()->toDateString())
            ->where('status', 'approved')
            ->orderBy('scheduled_date', 'asc')
            ->orderBy('time_from', 'asc')
            ->limit(10)
            ->get();
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('dashboard-refreshed');
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}