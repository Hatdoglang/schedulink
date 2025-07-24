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
                    <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>
                    
                    <!-- System Health Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Total Users</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $systemHealth['total_users'] ?? 0 }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Active Users</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $systemHealth['active_users'] ?? 0 }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-800">Total Assets</h3>
                            <p class="text-2xl font-bold text-yellow-600">{{ $systemHealth['total_assets'] ?? 0 }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800">Total Bookings</h3>
                            <p class="text-2xl font-bold text-purple-600">{{ $systemHealth['total_bookings'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Booking Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800">Pending</h3>
                            <p class="text-2xl font-bold text-gray-600">{{ $bookingStatistics['pending'] ?? 0 }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Approved</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $bookingStatistics['approved'] ?? 0 }}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-red-800">Rejected</h3>
                            <p class="text-2xl font-bold text-red-600">{{ $bookingStatistics['rejected'] ?? 0 }}</p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-orange-800">Cancelled</h3>
                            <p class="text-2xl font-bold text-orange-600">{{ $bookingStatistics['cancelled'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Recent Bookings -->
                    <div class="bg-white p-6 rounded-lg shadow mb-8">
                        <h3 class="text-xl font-semibold mb-4">Recent Bookings</h3>
                        @if(count($recentBookings) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left">User</th>
                                            <th class="px-4 py-2 text-left">Asset</th>
                                            <th class="px-4 py-2 text-left">Date</th>
                                            <th class="px-4 py-2 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBookings as $booking)
                                            <tr class="border-b">
                                                <td class="px-4 py-2">{{ $booking->user->full_name ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">{{ $booking->assetDetail->name ?? $booking->assetType->name ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">{{ $booking->scheduled_date ?? 'N/A' }}</td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 rounded text-sm 
                                                        @if($booking->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No recent bookings found.</p>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="/admin/users" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition">
                            <h3 class="text-lg font-semibold">Manage Users</h3>
                            <p class="text-sm">Add, edit, or remove users</p>
                        </a>
                        <a href="/admin/asset-types" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition">
                            <h3 class="text-lg font-semibold">Manage Assets</h3>
                            <p class="text-sm">Configure asset types and details</p>
                        </a>
                        <a href="/admin/bookings" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition">
                            <h3 class="text-lg font-semibold">View All Bookings</h3>
                            <p class="text-sm">Monitor all booking activities</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
