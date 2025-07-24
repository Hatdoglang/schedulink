<?php

namespace App\Livewire\Approver;

use App\Models\Booking;
use App\Models\Approver;
use App\Models\ApprovalLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $statistics = [];
    public $pendingBookings = [];
    public $recentApprovals = [];
    public $approvalHierarchy = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $userId = Auth::id();

        // Get approver records for current user
        $approverRecords = Approver::where('user_id', $userId)->pluck('id');

        if ($approverRecords->isEmpty()) {
            $this->statistics = [
                'pending_approvals' => 0,
                'approved_today' => 0,
                'rejected_today' => 0,
                'total_processed' => 0,
            ];
            $this->pendingBookings = collect([]);
            $this->recentApprovals = collect([]);
            $this->approvalHierarchy = collect([]);
            return;
        }

        // Get pending bookings that need approval from this user
        $this->pendingBookings = Booking::with(['user', 'assetType', 'assetDetail'])
            ->where('status', 'pending')
            ->whereHas('assetType.approvers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        // Statistics
        $pendingApprovalsCount = $this->pendingBookings->count();
        
        $approvedToday = ApprovalLog::where('approver_user_id', $userId)
            ->where('status', 'approved')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $rejectedToday = ApprovalLog::where('approver_user_id', $userId)
            ->where('status', 'rejected')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $totalProcessed = ApprovalLog::where('approver_user_id', $userId)
            ->whereIn('status', ['approved', 'rejected'])
            ->count();

        $this->statistics = [
            'pending_approvals' => $pendingApprovalsCount,
            'approved_today' => $approvedToday,
            'rejected_today' => $rejectedToday,
            'total_processed' => $totalProcessed,
        ];

        // Recent approval actions by this approver
        $this->recentApprovals = ApprovalLog::with(['booking.user', 'booking.assetType', 'booking.assetDetail'])
            ->where('approver_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get approval hierarchy for assets this user can approve
        $this->approvalHierarchy = Approver::with(['assetType', 'user'])
            ->where('user_id', $userId)
            ->get()
            ->groupBy('asset_type_id');
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('dashboard-refreshed');
    }

    public function render()
    {
        return view('livewire.approver.dashboard');
    }
}