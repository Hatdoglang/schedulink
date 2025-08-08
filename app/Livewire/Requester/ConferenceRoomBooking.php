<?php

namespace App\Livewire\Requester;

use Livewire\Component;
use App\Models\AssetType;
use App\Models\AssetDetail;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class ConferenceRoomBooking extends Component
{
    public $asset_type_id;
    public $asset_detail_id;
    public $venue;
    public $purpose;
    public $no_of_seats;
    public $scheduled_date;
    public $time_from;
    public $time_to;

    public $assetTypes = [];
    public $assetDetails = [];

    protected $listeners = ['setScheduledDate'];

    public function mount($scheduled_date = null)
    {
        $this->assetTypes = AssetType::all();
        $this->scheduled_date = $scheduled_date ?? now()->format('Y-m-d');
    }

    public function updatedAssetTypeId($value)
    {
        $this->assetDetails = AssetDetail::where('asset_type_id', $value)->get();
        $this->asset_detail_id = null;
        $this->venue = '';
    }

    public function updatedAssetDetailId($value)
    {
        $asset = AssetDetail::find($value);
        $this->venue = $asset?->location ?? '';
    }

    public function setScheduledDate($date)
    {
        $this->scheduled_date = $date;
    }

    public function submitBooking()
    {
        $this->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'asset_detail_id' => 'required|exists:asset_details,id',
            'venue' => 'required|string',
            'purpose' => 'required|string',
            'no_of_seats' => 'required|integer|min:1',
            'scheduled_date' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'required|after:time_from',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'asset_type_id' => $this->asset_type_id,
            'asset_detail_id' => $this->asset_detail_id,
            'asset_name' => AssetDetail::find($this->asset_detail_id)?->asset_name,
            'purpose' => $this->purpose,
            'no_of_seats' => $this->no_of_seats,
            'destination' => $this->venue,
            'scheduled_date' => $this->scheduled_date,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Booking submitted successfully!');
        $this->resetExcept(['assetTypes', 'scheduled_date']);
        $this->dispatch('hide-modal');
    }

    public function render()
    {
        return view('livewire.requester.conference-room-booking');
    }
}
