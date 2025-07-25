<?php

namespace App\Livewire\Requester;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Calendar extends Component
{
    public function render()
    {
        return view('livewire.requester.calendar');
    }
}
