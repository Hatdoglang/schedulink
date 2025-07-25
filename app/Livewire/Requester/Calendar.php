<?php

namespace App\Livewire\Requester;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.requester')]
class Calendar extends Component
{
    public $currentDate;
    public $currentMonth;
    public $currentYear;
    public $bookings = [];
    public $selectedBooking = null;
    public $compactMode = false; // New property for compact dashboard view

    public function mount($compactMode = false)
    {
        $this->compactMode = $compactMode;
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

    public function render()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get the first day of the week for the calendar
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $weeks = [];
        $currentWeek = [];
        $currentDay = $startOfCalendar->copy();

        while ($currentDay <= $endOfCalendar) {
            $currentWeek[] = $currentDay->copy();

            if ($currentDay->dayOfWeek === 6) { // Saturday (end of week)
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
            'today' => now()->format('Y-m-d')
        ]);
    }
}