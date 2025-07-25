<?php

namespace App\Livewire\Requester;

use Livewire\Component;
use App\Models\Booking;
use App\Models\AssetType;

class Bookings extends Component
{
    public $statusFilter = '';
    public $search = '';
    public $bookings;
    public $assetTypes;

    public function mount()
    {
        $this->assetTypes = AssetType::all();
        $this->loadBookings();
    }

    // Reload bookings based on current filters
    public function loadBookings()
    {
        $query = Booking::with(['user', 'assetType'])->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('assetType', fn($q) => $q->where('name', 'like', $searchTerm))
                    ->orWhereHas('user', fn($q) => $q->where('first_name', 'like', $searchTerm)
                        ->orWhere('last_name', 'like', $searchTerm))
                    ->orWhere('destination', 'like', $searchTerm)
                    ->orWhere('status', 'like', $searchTerm);
            });
        }

        $this->bookings = $query->get();
    }

    // React to status filter change
    public function updatedStatusFilter()
    {
        $this->loadBookings();
    }

    // React to search input change with debounce
    public function updatedSearch()
    {
        $this->loadBookings();
    }

    public function render()
    {
        return view('livewire.requester.bookings', [
            'bookings' => $this->bookings,
            'assetTypes' => $this->assetTypes,
        ])->layout('layouts.app');
    }
}
