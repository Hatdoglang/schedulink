<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/login', navigate: true);
    }
};
?>


<div>
    {{-- Include the admin navigation --}}
    @include('livewire.admin.layout.navigation')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">
                        Welcome, {{ Auth::user()->full_name ?? Auth::user()->email }}!
                    </h3>

                    @php
                        $userRole = Auth::user()->role->name ?? 'User';
                        $redirectUrl = \App\Services\RoleRedirectService::getRedirectUrl();
                    @endphp

                    <div class="mb-4">
                        <p class="text-gray-600">
                            Your role:
                            <span class="font-semibold text-blue-600">{{ $userRole }}</span>
                        </p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            You should be automatically redirected to your role-specific dashboard.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ $redirectUrl }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Go to My Dashboard
                        </a>

                        @if($userRole === 'Admin')
                            <div class="mt-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Admin Quick Links:</h4>
                                <div class="space-x-2">
                                    <a href="/admin/users" class="text-blue-600 hover:text-blue-800">Manage Users</a>
                                    <a href="/admin/bookings" class="text-blue-600 hover:text-blue-800">All Bookings</a>
                                    <a href="/admin/asset-types" class="text-blue-600 hover:text-blue-800">Manage Assets</a>
                                </div>
                            </div>
                        @elseif($userRole === 'Manager')
                            <div class="mt-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Approver Quick Links:</h4>
                                <div class="space-x-2">
                                    <a href="/approver/approvals/pending" class="text-blue-600 hover:text-blue-800">Pending Approvals</a>
                                    <a href="/approver/approvals/my-history" class="text-blue-600 hover:text-blue-800">My History</a>
                                </div>
                            </div>
                        @else
                            <div class="mt-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Quick Links:</h4>
                                <div class="space-x-2">
                                    <a href="/requester/bookings" class="text-blue-600 hover:text-blue-800">My Bookings</a>
                                    <a href="/requester/bookings/create" class="text-blue-600 hover:text-blue-800">New Booking</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
