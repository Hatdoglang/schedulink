<?php

namespace App\Livewire\Requester;

use Livewire\Component;
use App\Models\Booking;

class Bookings extends Component
{
    public $bookings;

    public function mount()
    {
        $this->bookings = Booking::with(['user', 'assetType'])->latest()->get();
    }

    public function render()
    {
        return view('livewire.requester.bookings') // or whatever your Blade file is named
            ->layout('layouts.app'); // ✅ correct path to your layout
    }

}

