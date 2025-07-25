<div class="flex min-h-screen bg-gray-100 pt-16">

    <!-- Sidebar (Fixed Width) -->
    <aside class="w-[15vw] bg-white border-r border-gray-200 fixed top-0 bottom-0 left-0 z-40">
        @include('livewire.requester.sidebar')
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col ml-[15vw]">

       

        <!-- Page Content -->
        <main class="flex-1 p-6 overflow-auto">
            
            <div class="container mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

                <!-- Calendar Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Your Booking Calendar</h2>
                        <a href="{{ route('requester.calendar') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Full Calendar →
                        </a>
                    </div>
                    <div class="calendar-container">
                        @livewire('requester.calendar', ['compactMode' => true])
                    </div>
                </div>

                <!-- Recent Bookings Section -->
                @if($recentBookings && count($recentBookings) > 0)
                <div class="mt-8 bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Bookings</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $booking->assetDetail->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $booking->time_from }} - {{ $booking->time_to }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                </div>
                @endif
            </div>
        </main>
    </div>
</div>
