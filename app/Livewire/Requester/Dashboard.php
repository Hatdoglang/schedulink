<?php

namespace App\Livewire\Requester;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $statistics = [];
    public $recentBookings = [];
    public $upcomingBookings = [];
    public $calendarData = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $userId = Auth::id();
        
        // Get booking statistics for the current user
        $totalBookings = Booking::where('user_id', $userId)->count();
        $pendingBookings = Booking::where('user_id', $userId)->where('status', 'pending')->count();
        $approvedBookings = Booking::where('user_id', $userId)->where('status', 'approved')->count();
        $rejectedBookings = Booking::where('user_id', $userId)->where('status', 'rejected')->count();
        $cancelledBookings = Booking::where('user_id', $userId)->where('status', 'cancelled')->count();

        $this->statistics = [
            'total_bookings' => $totalBookings,
            'pending_bookings' => $pendingBookings,
            'approved_bookings' => $approvedBookings,
            'rejected_bookings' => $rejectedBookings,
            'cancelled_bookings' => $cancelledBookings,
        ];

        // Get recent bookings (last 5)
        $this->recentBookings = Booking::with(['assetType', 'assetDetail'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming bookings
        $this->upcomingBookings = Booking::with(['assetType', 'assetDetail'])
            ->where('user_id', $userId)
            ->where('scheduled_date', '>=', now()->toDateString())
            ->where('status', 'approved')
            ->orderBy('scheduled_date', 'asc')
            ->orderBy('time_from', 'asc')
            ->limit(5)
            ->get();

        // Generate calendar data for current month
        $this->loadCalendarData();
    }

    public function loadCalendarData()
    {
        $userId = Auth::id();
        $currentDate = now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
        
        // Get bookings for current month
        $startOfMonth = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $bookings = Booking::with(['assetType', 'assetDetail'])
            ->where('user_id', $userId)
            ->whereBetween('scheduled_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->scheduled_date)->format('Y-m-d');
            });

        // Generate calendar weeks
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $weeks = [];
        $currentWeek = [];
        $currentDay = $startOfCalendar->copy();

        while ($currentDay <= $endOfCalendar) {
            $dayString = $currentDay->format('Y-m-d');
            $dayBookings = $bookings[$dayString] ?? collect();
            
            $currentWeek[] = [
                'date' => $dayString,
                'dayNumber' => $currentDay->format('j'),
                'isCurrentMonth' => $currentDay->month == $currentMonth,
                'isToday' => $dayString === now()->format('Y-m-d'),
                'bookings' => $dayBookings
            ];

            if ($currentDay->dayOfWeek === 6) { // Saturday (end of week)
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }

            $currentDay->addDay();
        }

        if (!empty($currentWeek)) {
            $weeks[] = $currentWeek;
        }

        $this->calendarData = [
            'weeks' => $weeks,
            'monthName' => $startOfMonth->format('F Y')
        ];
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('dashboard-refreshed');
    }

    public function render()
    {
        return view('livewire.requester.dashboard');
    }
}