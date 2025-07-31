<?php

namespace App\Livewire\Requester;

use Livewire\Component;
use App\Models\Booking;
use App\Models\AssetType;
use App\Models\AssetDetail;
use Livewire\WithPagination;

class Bookings extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';
    public $assetTypes;
    public $assetDetails;

    // Use bootstrap pagination theme (optional, based on your frontend)
    protected string $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->assetTypes = AssetType::all();
        $this->assetDetails = AssetDetail::all();
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset to page 1 when searching
    }

    public function updatingStatusFilter()
    {
        $this->resetPage(); // Reset to page 1 when filtering
    }

    public function render()
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

        return view('livewire.requester.bookings', [
            'bookings' => $query->paginate(7),
            'assetTypes' => $this->assetTypes,
            'assetDetails' => $this->assetDetails,
        ])->layout('layouts.app');
    }
}
