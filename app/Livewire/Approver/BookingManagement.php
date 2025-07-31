<?php

namespace App\Livewire\Approver;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingManagement extends Component
{
    use WithPagination;

    public $selectedBooking;
    public $disapproveReason = '';

    protected $listeners = ['viewBookingDetails'];

    public function viewBookingDetails($id)
    {
        $this->selectedBooking = Booking::with([
            'user.department',
            'user.branch',
            'assetType',
            'assetDetail',
            'bookedGuests'
        ])->find($id);

        $this->dispatch('open-details-modal');
    }

    public function approveBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $user = Auth::user();
        $fullName = $user->first_name . ' ' . $user->last_name;

        // Check if current user already approved
        if (
            $booking->first_approver_name === $fullName ||
            $booking->second_approver_name === $fullName
        ) {
            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'You have already approved this booking.',
            ]);
            return;
        }

        if (!$booking->first_approver_name) {
            $booking->first_approver_name = $fullName;
            $booking->first_approved_at = now();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'First approval recorded. Waiting for second approval.',
            ]);
        } elseif (!$booking->second_approver_name) {
            $booking->second_approver_name = $fullName;
            $booking->second_approved_at = now();

            // Only now mark booking as approved
            $booking->status = 'approved';

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Booking fully approved.',
            ]);
        } else {
            $this->dispatch('notify', [
                'type' => 'info',
                'message' => 'This booking is already fully approved.',
            ]);
            return;
        }

        $booking->save();

        // Refresh selected booking details if modal is open
        $this->selectedBooking = $booking->fresh();

        $this->dispatch('close-details-modal');
    }

    public function openDisapproveModal($bookingId)
    {
        $this->selectedBooking = Booking::findOrFail($bookingId);
        $this->disapproveReason = '';

        $this->dispatch('open-disapprove-modal');
    }

    public function submitDisapproval()
    {
        $this->validate([
            'disapproveReason' => 'required|string|min:5',
        ]);

        if ($this->selectedBooking) {
            $this->selectedBooking->status = 'rejected';
            $this->selectedBooking->disapprove_reason = $this->disapproveReason;
            $this->selectedBooking->save();

            $this->dispatch('close-disapprove-modal');
            $this->dispatch('close-details-modal');

            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'Booking disapproved with reason.',
            ]);

            $this->disapproveReason = '';
        }
    }

    public function render()
    {
        return view('livewire.approver.booking-management', [
            'bookings' => Booking::with([
                'user.department',
                'user.branch',
                'assetType',
                'assetDetail',
                'bookedGuests'
            ])->latest()->paginate(10),
        ])->layout('layouts.approver');
    }
}
