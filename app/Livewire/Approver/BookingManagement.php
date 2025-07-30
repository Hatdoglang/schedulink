<?php

namespace App\Livewire\Approver;

use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedBooking = null;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showBooking($bookingId)
    {
        $this->selectedBooking = Booking::with([
            'user.department',
            'user.branch',
            'assetType',
            'assetDetail',
            'approver1',
            'approver2',
            'bookedGuests',
        ])->findOrFail($bookingId);
    }

    public function render()
    {
        $bookings = Booking::with([
            'user',
            'assetType',
            'assetDetail',
            'approver1',
            'approver2',
            'bookedGuests',
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($q2) {
                        $q2->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('assetType', function ($q2) {
                            $q2->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('status', 'like', '%' . $this->search . '%')
                        ->orWhere('scheduled_date', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(7);

        return view('livewire.approver.booking-management', [
            'bookings' => $bookings,
            'selectedBooking' => $this->selectedBooking,
        ])->layout('layouts.approver');
    }
}
