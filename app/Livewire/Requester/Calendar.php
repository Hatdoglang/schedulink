<?php

namespace App\Livewire\Requester;

use Livewire\Component;
use App\Models\Booking;
use Carbon\Carbon;

class Calendar extends Component
{
    public $events = [];

    public function mount()
    {
        $this->events = Booking::with(['user', 'assetType'])->get()->map(function ($booking) {
            $date = Carbon::parse($booking->scheduled_date)->toDateString(); // Only the date part

            return [
                'title' => $booking->purpose ?? 'No Title',
                'start' => $date, // Use only the date for all-day event
                'allDay' => true, // Tell FullCalendar it's an all-day event
                'requested_by' => optional($booking->user)->first_name . ' ' . optional($booking->user)->last_name,
                'asset_type' => optional($booking->assetType)->name ?? 'N/A',
                'venue' => $booking->destination ?? 'N/A',
                'status' => $booking->status ?? 'Pending',
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.requester.calendar', [
            'events' => $this->events,
        ]);
    }
}
