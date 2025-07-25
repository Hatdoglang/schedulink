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
    public $selectedBooking = null;
    public $compactMode = false;

    public function mount($compactMode = false)
    {
        $this->compactMode = $compactMode;
    }

    public function getEventsProperty()
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $bookings = Booking::with(['assetType', 'assetDetail'])
            ->where('user_id', Auth::id())
            ->whereBetween('scheduled_date', [$startOfYear, $endOfYear])
            ->get();

        return $bookings->map(function ($booking) {
            $startDateTime = Carbon::parse($booking->scheduled_date . ' ' . $booking->time_from);
            $endDateTime = Carbon::parse($booking->scheduled_date . ' ' . $booking->time_to);

            return [
                'id' => $booking->id,
                'title' => $booking->assetDetail->name ?? 'Booking',
                'start' => $startDateTime->toISOString(),
                'end' => $endDateTime->toISOString(),
                'backgroundColor' => $this->getEventColor($booking->status),
                'borderColor' => $this->getEventBorderColor($booking->status),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'status' => $booking->status,
                    'asset_type' => $booking->assetType->name ?? 'N/A',
                    'asset_detail' => $booking->assetDetail->name ?? 'N/A',
                    'description' => $booking->purpose ?? '',
                    'booking_id' => $booking->id
                ]
            ];
        })->toArray();
    }

    private function getEventColor($status)
    {
        switch ($status) {
            case 'approved':
                return '#22c55e';
            case 'pending':
                return '#f59e0b';
            case 'rejected':
                return '#ef4444';
            default:
                return '#6b7280';
        }
    }

    private function getEventBorderColor($status)
    {
        switch ($status) {
            case 'approved':
                return '#16a34a';
            case 'pending':
                return '#d97706';
            case 'rejected':
                return '#dc2626';
            default:
                return '#4b5563';
        }
    }

    public function viewBooking($bookingId)
    {
        $this->selectedBooking = Booking::with(['assetType', 'assetDetail'])
            ->where('id', $bookingId)
            ->where('user_id', Auth::id())
            ->first();

        $this->dispatch('booking-selected', $this->selectedBooking);
    }

    public function closeModal()
    {
        $this->selectedBooking = null;
    }

    public function refreshEvents()
    {
        $this->dispatch('refresh-calendar-events', $this->events);
    }

    public function render()
    {
        return view('livewire.requester.calendar', [
            'events' => $this->events
        ]);
    }
}