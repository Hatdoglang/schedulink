<?php 
namespace App\Http\Livewire\Requester;

use Livewire\Component;

class BookingManagement extends Component
{
    public function render()
    {
        $bookings = [
            [
                'id' => 'BK-001',
                'user' => ['name' => 'John Doe'],
                'date' => '2025-07-25',
                'status' => 'approved',
            ],
            [
                'id' => 'BK-002',
                'user' => ['name' => 'Jane Smith'],
                'date' => '2025-07-26',
                'status' => 'pending',
            ],
            [
                'id' => 'BK-003',
                'user' => ['name' => 'Alice Johnson'],
                'date' => '2025-07-27',
                'status' => 'rejected',
            ],
        ];

        // NOTE: Make sure this view name matches exactly
        return view('livewire.requester.booking', compact('bookings'));
    }
}
