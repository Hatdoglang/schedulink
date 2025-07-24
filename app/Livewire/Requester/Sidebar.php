<?php

namespace App\Livewire\Requester;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public $currentRoute;
    
    public function mount()
    {
        $this->currentRoute = request()->route()->getName();
    }
    
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
    
    public function render()
    {
        return view('livewire.requester.sidebar');
    }
}