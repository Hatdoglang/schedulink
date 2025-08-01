<div>
    <div class="container py-5">

        {{-- Header with Status Filter, Search, and + Reservation Button --}}
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-auto d-flex align-items-center">
                {{-- Status Filter Dropdown --}}
                <div class="dropdown me-3">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Filter by Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                        <li>
                            <a class="dropdown-item {{ request('status') === null ? 'active' : '' }}" href="{{ url()->current() }}">
                                All
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('status') === 'pending' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}">
                                Pending
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('status') === 'approved' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}">
                                Approved
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('status') === 'rejected' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}">
                                Rejected
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-auto flex-grow-1 d-flex justify-content-end">
                {{-- Live Search Input --}}
                <input type="search" id="searchInput" class="form-control form-control-sm w-auto" style="min-width: 180px; max-width: 300px;"
                    placeholder="Search bookings..." aria-label="Search bookings" />
            </div>

            <div class="col-md-auto ms-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bookingModal">
                    + Asset Reservation
                </button>
            </div>
        </div>

        {{-- Booking Table --}}
        <div class="row justify-content-center mt-3">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle" style="border-collapse: separate; border-spacing: 0 0.6rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No.</th>
                                    <th>Asset Type</th>
                                    <th>Requested</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                    <th>Last Modified</th>
                                    <th class="pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $index => $booking)
                                    <tr style="background: #fff; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); border-radius: 0.375rem;">
                                        <td class="ps-4">{{ $bookings->firstItem() + $index }}</td>
                                        <td>{{ $booking->assetType->name ?? 'N/A' }}</td>
                                        <td>{{ optional($booking->user)->first_name }} {{ optional($booking->user)->last_name }}</td>
                                        <td>{{ $booking->destination ?? '—' }}</td>
                                        <td>
                                            @switch($booking->status)
                                                @case('approved')
                                                    <span class="px-3 py-1 rounded-pill fw-bold text-success"
                                                        style="background-color: #d1e7dd; border: 1px solid #badbcc;">
                                                        Approved
                                                    </span>
                                                @break

                                                @case('pending')
                                                    <span class="px-3 py-1 rounded-pill fw-bold text-primary"
                                                        style="background-color: #cfe2ff; border: 1px solid #9ec5fe;">
                                                        Pending
                                                    </span>
                                                @break

                                                @case('rejected')
                                                    <span class="px-3 py-1 rounded-pill fw-bold text-danger"
                                                        style="background-color: #f8d7da; border: 1px solid #f5c2c7;">
                                                        Rejected
                                                    </span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary">Unknown</span>
                                            @endswitch
                                        </td>

                                        <td>{{ $booking->updated_at->format('M d, Y h:i A') }}</td>
                                        <td class="pe-4 align-middle text-center">
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">View Details</a></li>
                                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#">Cancel Booking</a></li>
                                                </ul>
                                            </div>
                                        </td>

                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No bookings found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            <div class="mt-3 px-4">
                                {{ $bookings->links() }}
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Modal --}}
                @include('livewire.requester.modal.bookingModal')
            </div>


            {{-- Modal Partial --}}
            @include('livewire.requester.modal.bookingModal')

            {{-- Live Search Script --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const tableRows = document.querySelectorAll('tbody tr');

                    searchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase().trim();

                        tableRows.forEach(row => {
                            const rowText = row.textContent.toLowerCase();

                            if (rowText.includes(query)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                });
            </script>

        </div>
    </div>
