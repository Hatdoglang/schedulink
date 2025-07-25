<?php

namespace App\Livewire\Requester;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Calendar extends Component
{
    public $currentDate;
    public $currentMonth;
    public $currentYear;
    public $bookings = [];
    public $selectedBooking = null;
    public $compactMode = false;
    public $viewMode = 'month'; // View mode: day, week, month, year

    public function mount($compactMode = false, $viewMode = 'month')
    {
        $this->compactMode = $compactMode;
        $this->viewMode = $viewMode;
        $this->currentDate = now();
        $this->currentMonth = $this->currentDate->month;
        $this->currentYear = $this->currentDate->year;
        $this->loadBookings();
    }

    public function loadBookings()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $this->bookings = Booking::with(['assetType', 'assetDetail'])
            ->where('user_id', Auth::id())
            ->whereBetween('scheduled_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->scheduled_date)->format('Y-m-d');
            });
    }

    public function getBookingsForCalendarProperty()
    {
        $bookingsForCalendar = [];
        
        foreach ($this->bookings as $date => $dayBookings) {
            foreach ($dayBookings as $booking) {
                $startDateTime = $booking->scheduled_date . ' ' . $booking->time_from;
                $endDateTime = $booking->scheduled_date . ' ' . $booking->time_to;
                
                $bookingsForCalendar[] = [
                    'id' => 'booking-' . $booking->id,
                    'title' => $this->getEventTitle($booking),
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'backgroundColor' => $this->getEventColor($booking->status),
                    'borderColor' => $this->getEventColor($booking->status),
                    'textColor' => $this->getEventTextColor($booking->status),
                    'extendedProps' => [
                        'booking_id' => $booking->id,
                        'status' => $booking->status,
                        'asset_name' => $booking->assetDetail->name ?? 'Unknown Asset',
                        'purpose' => $booking->purpose,
                        'destination' => $booking->destination,
                        'notes' => $booking->notes,
                    ]
                ];
            }
        }
        
        return $bookingsForCalendar;
    }

    private function getEventTitle($booking)
    {
        $assetName = $booking->assetDetail->name ?? 'Booking';
        $time = Carbon::parse($booking->time_from)->format('H:i');
        
        if ($this->compactMode) {
            return $time . ' - ' . $assetName;
        }
        
        return $time . ' - ' . $assetName . ($booking->purpose ? ' (' . $booking->purpose . ')' : '');
    }

    private function getEventColor($status)
    {
        return match($status) {
            'approved' => '#198754',
            'pending' => '#ffc107',
            'rejected' => '#dc3545',
            default => '#6c757d'
        };
    }

    private function getEventTextColor($status)
    {
        return match($status) {
            'pending' => '#000',
            default => '#fff'
        };
    }

    public function updateCurrentDate($date)
    {
        $this->currentDate = Carbon::parse($date);
        $this->currentMonth = $this->currentDate->month;
        $this->currentYear = $this->currentDate->year;
        $this->loadBookings();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->currentDate = $date;
        $this->loadBookings();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->currentDate = $date;
        $this->loadBookings();
    }

    public function today()
    {
        $this->currentDate = now();
        $this->currentMonth = $this->currentDate->month;
        $this->currentYear = $this->currentDate->year;
        $this->loadBookings();
    }

    public function viewBooking($bookingId)
    {
        $this->selectedBooking = Booking::with(['assetType', 'assetDetail'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->first();
    }

    public function closeModal()
    {
        $this->selectedBooking = null;
    }

    public function refreshCalendar()
    {
        $this->loadBookings();
        $this->dispatch('refreshCalendar');
    }

    public function render()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Calendar range (can be reused later for week/day view support)
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $weeks = [];
        $currentWeek = [];
        $currentDay = $startOfCalendar->copy();

        while ($currentDay <= $endOfCalendar) {
            $currentWeek[] = $currentDay->copy();

            if ($currentDay->dayOfWeek === 6) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }

            $currentDay->addDay();
        }

        if (!empty($currentWeek)) {
            $weeks[] = $currentWeek;
        }

        return view('livewire.requester.calendar', [
            'weeks' => $weeks,
            'monthName' => $startOfMonth->format('F Y'),
            'today' => now()->format('Y-m-d'),
            'viewMode' => $this->viewMode,
            'currentMonth' => $this->currentMonth,
            'bookings' => $this->bookings,
            'bookingsForCalendar' => $this->bookingsForCalendar,
        ]);
    }
}
