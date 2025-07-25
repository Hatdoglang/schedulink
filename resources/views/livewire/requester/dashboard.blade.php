<div class="flex min-h-screen bg-gray-100">

    <!-- Sidebar (Fixed Width) -->
    <aside class="w-[15vw] bg-white border-r border-gray-200 fixed top-0 bottom-0 left-0 z-40">
        @include('livewire.requester.sidebar')
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col ml-[15vw]">

        <!-- Header -->
        <header class="bg-white border-b shadow-sm py-2 px-4 sticky top-0 z-30">
            @include('livewire.requester.header')
            @include('livewire.requester.navigation')

        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 overflow-auto">
            <div class="container mx-auto">
                <h1 class="text-xl font-semibold text-gray-800">Welcome to your Dashboard</h1>
                <p class="text-gray-500">Here's a quick overview of your requests, schedules, or alerts.</p>

                <!-- Example Card -->
                <div class="mt-6 bg-white shadow rounded-lg p-6">
                    <p class="text-gray-700">This is where your main content will appear.</p>
                </div>
            </div>
        </main>

    </div>
</div>
