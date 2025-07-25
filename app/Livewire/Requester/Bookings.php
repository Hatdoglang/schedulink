<?php

namespace App\Livewire\Requester;

use Livewire\Component;
use App\Models\AssetType;
use App\Models\AssetDetail;

class Bookings extends Component
{
    public $assetTypes = [];
    public $assetDetails = [];

    public $selectedAssetType;
    public $selectedAssetDetail;
    public $scheduledDate;
    public $timeFrom;
    public $timeTo;
    public $purpose;
    public $notes;
    public $newGuest;
    public $guests = [];

    public function mount()
    {
        $this->assetTypes = AssetType::all();
    }

    public function updatedSelectedAssetType($value)
    {
        $this->assetDetails = AssetDetail::where('asset_type_id', $value)->get();
        $this->selectedAssetDetail = null;
    }

    public function addGuest()
    {
        $guest = trim($this->newGuest);
        if (!empty($guest)) {
            $this->guests[] = $guest;
            $this->newGuest = '';
        }
    }

    public function removeGuest($index)
    {
        unset($this->guests[$index]);
        $this->guests = array_values($this->guests); // Re-index array
    }

    public function save()
    {
        $this->validate([
            'selectedAssetType' => 'required|exists:asset_types,id',
            'selectedAssetDetail' => 'required|exists:asset_details,id',
            'scheduledDate' => 'required|date|after_or_equal:today',
            'timeFrom' => 'required|date_format:H:i',
            'timeTo' => 'required|date_format:H:i|after:timeFrom',
            'purpose' => 'required|string|min:5',
            'notes' => 'nullable|string',
        ]);

        // Booking saving logic goes here...

        session()->flash('success', 'Booking created successfully!');
        $this->resetExcept('assetTypes');
    }

    public function render()
    {
        return view('livewire.requester.bookings')
            ->layout('layouts.app'); // uses resources/views/layouts/app.blade.php
    }
}
