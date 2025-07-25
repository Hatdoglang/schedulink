<?php
namespace App\Livewire\Requester;

use Livewire\Component;
use App\Models\User;
use App\Models\AssetType;

class Calendar extends Component
{
    public $users;
    public $assetTypes;

    public function mount()
    {
        $this->users = User::select('id', 'first_name', 'last_name')->get();
        $this->assetTypes = AssetType::select('id', 'name')->get();
    }

    public function render()
    {
        return view('livewire.requester.calendar');
    }
}
