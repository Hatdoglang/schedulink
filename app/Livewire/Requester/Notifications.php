<?php

namespace App\Livewire\Requester;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.requester')]
class Notifications extends Component
{
    use WithPagination;
    
    public $filter = 'all'; // all, unread, read
    
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        session()->flash('success', 'All notifications marked as read.');
    }
    
    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
            session()->flash('success', 'Notification deleted.');
        }
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }
    
    public function render()
    {
        $query = Auth::user()->notifications();
        
        switch ($this->filter) {
            case 'unread':
                $query = Auth::user()->unreadNotifications();
                break;
            case 'read':
                $query = Auth::user()->readNotifications();
                break;
        }
        
        $notifications = $query->paginate(10);
        
        return view('livewire.requester.notifications', [
            'notifications' => $notifications,
            'unreadCount' => Auth::user()->unreadNotifications->count()
        ]);
    }
}