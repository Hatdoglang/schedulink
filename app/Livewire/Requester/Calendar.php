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
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = Booking::with(['user', 'assetType'])
            ->where('status', 'approved') // ✅ only approved
            ->orderBy('scheduled_date')
            ->get()
            ->filter(function ($booking) {
                return !empty($booking->scheduled_date) && !empty($booking->time_from) && !empty($booking->time_to);
            })
            ->map(function ($booking) {
                $start = Carbon::parse($booking->scheduled_date)->setTimeFrom($booking->time_from);
                $end = Carbon::parse($booking->scheduled_date)->setTimeFrom($booking->time_to);

                $now = Carbon::now('Asia/Manila');
                $timelineStatus = $now->between($start, $end)
                    ? 'Ongoing'
                    : ($now->gt($end) ? 'Ended' : 'Incoming');

                $timeRange = $start->format('g:i A') . ' > ' . $end->format('g:i A');

                $user = optional($booking->user);
                $requestedBy = trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? ''));

                return [
                    'title' => $booking->purpose ?? 'No Title',
                    'start' => $start->format('Y-m-d\TH:i:s'),
                    'end' => $end->format('Y-m-d\TH:i:s'),
                    'allDay' => false,
                    'time_range' => $timeRange,
                    'requested_by' => $requestedBy ?: 'Unknown User',
                    'purpose' => $booking->purpose ?? 'No Title',
                    'asset_type' => optional($booking->assetType)->name ?? 'N/A',
                    'venue' => $booking->destination ?? 'N/A',
                    'status' => $booking->status,
                    'timeline_status' => $timelineStatus,
                ];
            })
            ->values()
            ->toArray();
    }


    public function render()
    {
        return view('livewire.requester.calendar', [
            'events' => $this->events,
        ])->layout('layouts.app');
    }
}
