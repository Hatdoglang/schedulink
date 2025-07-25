<div>
    <div class="container py-5">
        {{-- Header with Status Filter, Search, and + Reservation Button --}}
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-auto d-flex align-items-center">
                {{-- Status Filter Dropdown --}}
                <div class="dropdown me-3">
                    <button
                        class="btn btn-outline-secondary dropdown-toggle"
                        type="button"
                        id="statusDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Filter by Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                        <li>
                            <a class="dropdown-item {{ request('status') === null ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => null]) }}">
                                All
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}">
                                Pending
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('status') === 'approved' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}">
                                Approved
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('status') === 'rejected' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}">
                                Rejected
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-auto flex-grow-1 d-flex justify-content-end">
                {{-- Live Search Input --}}
                <input
                    type="search"
                    id="searchInput"
                    class="form-control form-control-sm w-auto"
                    style="min-width: 180px; max-width: 300px;"
                    placeholder="Search bookings..."
                    aria-label="Search bookings"
                />
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
                        <table class="table table-striped table-hover mb-0" style="border-collapse: separate; border-spacing: 0 0.5rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Asset Type</th>
                                    <th>Requested</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                    <th>Last Modified</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $index => $booking)
                                    <tr style="background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 0.25rem;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $booking->assetType->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->user->first_name }} {{ $booking->user->last_name }}</td>
                                        <td>{{ $booking->destination ?? '—' }}</td>
                                        <td>
                                            @php $status = $booking->status; @endphp
                                            @if ($status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif ($status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ $booking->updated_at->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No bookings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reservation Modal --}}
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('bookings.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="bookingModalLabel">New Asset Reservation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="asset_type_id" class="form-label">Asset Type</label>
                                <select class="form-select" name="asset_type_id" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($assetTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="destination" class="form-label">Venue</label>
                                <input type="text" class="form-control" name="destination" required>
                            </div>

                            <div class="mb-3">
                                <label for="scheduled_date" class="form-label">Schedule Date</label>
                                <input type="date" class="form-control" name="scheduled_date" required>
                            </div>

                            <div class="mb-3">
                                <label for="time_from" class="form-label">From</label>
                                <input type="time" class="form-control" name="time_from" required>
                            </div>

                            <div class="mb-3">
                                <label for="time_to" class="form-label">To</label>
                                <input type="time" class="form-control" name="time_to" required>
                            </div>

                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose</label>
                                <textarea class="form-control" name="purpose" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Reserve</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Live Search Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    // Combine all cell texts to one string
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
