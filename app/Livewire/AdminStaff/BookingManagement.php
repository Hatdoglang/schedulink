<?php

namespace App\Livewire\AdminStaff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;

class BookingManagement extends Component
{
    use WithPagination;

    public $selectedBooking = null;
    public $disapproveReason = '';

    protected $listeners = ['viewBookingDetails'];

    public function viewBookingDetails($bookingId)
    {
        $this->selectedBooking = Booking::with([
            'user.department',
            'user.branch',
            'assetType',
            'assetDetail',
            'vehicleAssignment.driver',
            'bookedGuests',
        ])->findOrFail($bookingId);

        $this->dispatch('open-details-modal');
    }

    public function approveBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->status = 'approved';
        $booking->save();

        $this->dispatch('close-details-modal');
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Booking approved successfully.',
        ]);
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
        }
    }

    public function render()
    {
        $query = Booking::with(['user', 'assetType'])->latest();

        $query->where('asset_type_id', 2); // Only conference room

        if (request()->has('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $query->where('status', request('status'));
        }

        return view('livewire.admin-staff.booking-management', [
            'bookings' => $query->paginate(10),
        ])->layout('layouts.adminstaff');
    }
}
