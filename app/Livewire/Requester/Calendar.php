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
    public $viewFormat = 'month'; // New property for view format: day, week, month, year

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
        switch ($this->viewFormat) {
            case 'day':
                $startDate = $this->currentDate->copy()->startOfDay();
                $endDate = $this->currentDate->copy()->endOfDay();
                break;
            case 'week':
                $startDate = $this->currentDate->copy()->startOfWeek();
                $endDate = $this->currentDate->copy()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::create($this->currentYear, 1, 1)->startOfYear();
                $endDate = $startDate->copy()->endOfYear();
                break;
            default:
                $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
        }

        $this->bookings = Booking::with(['assetType', 'assetDetail'])
            ->where('user_id', Auth::id())
            ->whereBetween('scheduled_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->scheduled_date)->format('Y-m-d');
            });
    }

    public function setViewFormat($format)
    {
        $this->viewFormat = $format;
        $this->loadBookings();
    }

    public function previousPeriod()
    {
        switch ($this->viewFormat) {
            case 'day':
                $this->currentDate = $this->currentDate->copy()->subDay();
                break;
            case 'week':
                $this->currentDate = $this->currentDate->copy()->subWeek();
                break;
            case 'month':
                $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
                $this->currentMonth = $date->month;
                $this->currentYear = $date->year;
                $this->currentDate = $date;
                break;
            case 'year':
                $this->currentYear--;
                $this->currentDate = Carbon::create($this->currentYear, $this->currentMonth, 1);
                break;
        }
        $this->loadBookings();
    }

    public function nextPeriod()
    {
        switch ($this->viewFormat) {
            case 'day':
                $this->currentDate = $this->currentDate->copy()->addDay();
                break;
            case 'week':
                $this->currentDate = $this->currentDate->copy()->addWeek();
                break;
            case 'month':
                $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
                $this->currentMonth = $date->month;
                $this->currentYear = $date->year;
                $this->currentDate = $date;
                break;
            case 'year':
                $this->currentYear++;
                $this->currentDate = Carbon::create($this->currentYear, $this->currentMonth, 1);
                break;
        }
        $this->loadBookings();
    }

    // Keep backward compatibility
    public function previousMonth()
    {
        $this->previousPeriod();
    }

    public function nextMonth()
    {
        $this->nextPeriod();
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

    private function getWeekDays()
    {
        $startOfWeek = $this->currentDate->copy()->startOfWeek();
        $days = [];
        
        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->copy()->addDays($i);
        }
        
        return $days;
    }

    private function getYearMonths()
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($this->currentYear, $i, 1);
        }
        return $months;
    }

    private function getTimeSlots()
    {
        $slots = [];
        for ($hour = 6; $hour < 22; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
        }
        return $slots;
    }

    public function render()
    {
        $data = [
            'today' => now()->format('Y-m-d'),
            'viewFormat' => $this->viewFormat
        ];

        switch ($this->viewFormat) {
            case 'day':
                $data['currentDay'] = $this->currentDate;
                $data['dayName'] = $this->currentDate->format('l, F j, Y');
                $data['timeSlots'] = $this->getTimeSlots();
                break;

            case 'week':
                $data['weekDays'] = $this->getWeekDays();
                $data['weekRange'] = $this->currentDate->copy()->startOfWeek()->format('M j') . ' - ' . 
                                   $this->currentDate->copy()->endOfWeek()->format('M j, Y');
                $data['timeSlots'] = $this->getTimeSlots();
                break;

            case 'month':
                $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
                $endOfMonth = $startOfMonth->copy()->endOfMonth();
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

                $data['weeks'] = $weeks;
                $data['monthName'] = $startOfMonth->format('F Y');
                break;

            case 'year':
                $data['yearMonths'] = $this->getYearMonths();
                $data['yearName'] = $this->currentYear;
                break;
        }

        return view('livewire.requester.calendar', $data);
    }
}