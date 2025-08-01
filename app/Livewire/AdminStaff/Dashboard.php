<?php

namespace App\Livewire\AdminStaff;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin-staff.dashboard')
            ->layout('layouts.adminstaff'); // ✅ Use correct layout
    }
}

