<div class="card shadow-sm border-0">
    {{-- Notification Header --}}
    <div class="px-4 pt-3">
        <livewire:requester.notification.notification-header />
    </div>

    {{-- Table Header --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center mt-3">
        <h5 class="mb-0 fw-bold">All Bookings</h5>
        <input type="search" id="bookingSearch" class="form-control form-control-sm w-auto" placeholder="Search...">
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
        <table id="bookingTable" class="table table-hover mb-0 align-middle" style="border-collapse: separate; border-spacing: 0 0.6rem;">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">No.</th>
                    <th>Asset Type</th>
                    <th>Requested</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Last Modified</th>
                    <th class="pe-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $index => $booking)
                    <tr style="background: #fff; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); border-radius: 0.375rem;">
                        <td class="ps-4">{{ $bookings->firstItem() + $index }}</td>
                        <td>{{ $booking->assetType->name ?? 'N/A' }}</td>
                        <td>{{ $booking->user->first_name }} {{ $booking->user->last_name }}</td>
                        <td>{{ $booking->destination ?? '—' }}</td>
                        <td>
                            @switch($booking->status)
                                @case('approved')
                                    <span class="px-3 py-1 rounded-pill fw-bold text-success" style="background-color: #d1e7dd; border: 1px solid #badbcc;">
                                        Approved
                                    </span>
                                @break

                                @case('pending')
                                    <span class="px-3 py-1 rounded-pill fw-bold text-primary" style="background-color: #cfe2ff; border: 1px solid #9ec5fe;">
                                        Pending
                                    </span>
                                @break

                                @case('rejected')
                                @case('disapproved')
                                    <span class="px-3 py-1 rounded-pill fw-bold text-danger" style="background-color: #f8d7da; border: 1px solid #f5c2c7;">
                                        Rejected
                                    </span>
                                @break

                                @default
                                    <span class="badge bg-secondary">Unknown</span>
                            @endswitch
                        </td>
                        <td>{{ \Carbon\Carbon::parse($booking->updated_at)->format('M d, Y h:i A') }}</td>
                        <td class="pe-4 align-middle text-center">
                            <div class="dropdown d-inline">
                                <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="#" class="dropdown-item view-details" data-id="{{ $booking->id }}"
                                            data-user="{{ $booking->user->first_name . ' ' . $booking->user->last_name ?? 'N/A' }}"
                                            data-branch="{{ $booking->user->branch->name ?? 'N/A' }}"
                                            data-department="{{ $booking->user->department->name ?? 'N/A' }}"
                                            data-requested-at="{{ $booking->created_at ? $booking->created_at->format('Y-m-d H:i') : 'N/A' }}"
                                            data-purpose="{{ $booking->purpose ?? 'N/A' }}" data-asset="{{ $booking->assetType->name ?? 'N/A' }}"
                                            data-seats="{{ $booking->no_of_seats ?? 'N/A' }}"
                                            data-first-approver="{{ $booking->first_approver ?? 'N/A' }}"
                                            data-second-approver="{{ $booking->second_approver ?? 'N/A' }}"
                                            data-asset-name="{{ $booking->assetDetail->asset_name ?? 'N/A' }}"
                                            data-location="{{ $booking->assetDetail->location ?? 'N/A' }}"
                                            data-capacity="{{ $booking->assetDetail->number_of_seats ?? 'N/A' }}"
                                            data-schedule="{{ $booking->formatted_schedule }}" data-time-to="{{ $booking->time_to ?? 'N/A' }}"
                                            data-notes="{{ $booking->notes ?? 'N/A' }}"
                                            data-guests="{{ json_encode($booking->bookedGuests->pluck('email')->toArray()) }}"
                                            data-status="{{ $booking->status ?? 'pending' }}">
                                            View Details
                                        </a>

                                    </li>
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
        </div>


        {{-- Pagination --}}
        @if ($bookings->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small" id="bookingCount">
                        Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} results
                    </div>
                    <div>
                        {{ $bookings->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @endif

        {{-- Booking Details Section --}}
        <div class="card shadow-sm border-0 mt-4" id="bookingDetailsCard" style="display: none;">
            <div class="card-body">
                <div class="d-flex justify-content-start mb-2">
                    <span class="badge px-2 py-1" id="detailStatus">Pending</span>
                </div>

                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-md-4 border-end">
                        <h6 class="fw-bold mb-3">Request Information</h6>
                        <div class="mb-2 row">
                            <div class="col-5">Requested By:</div>
                            <div class="col-7" id="detailUser"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Department:</div>
                            <div class="col-7" id="detailDepartment"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Branch:</div>
                            <div class="col-7" id="detailBranch"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Date Requested:</div>
                            <div class="col-7" id="detailRequestedAt"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Purpose:</div>
                            <div class="col-7" id="detailPurpose"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Type of Asset:</div>
                            <div class="col-7" id="detailAssetType"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">No. of Seats:</div>
                            <div class="col-7" id="detailSeats"></div>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">Approval Information</h6>
                        <div class="mb-2 row">
                            <div class="col-5">1st Level Approver:</div>
                            <div class="col-7" id="detailFirstApprover">Pending</div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">2nd Level Approver:</div>
                            <div class="col-7" id="detailSecondApprover">Pending</div>
                        </div>
                    </div>

                    <!-- CENTER COLUMN -->
                    <div class="col-md-4 border-end">
                        <h6 class="fw-bold mb-3">Schedule Information</h6>
                        <div class="text-center mb-3">
                            <img src="/images/placeholder-conference-room.png" alt="Asset Image" class="img-fluid rounded"
                                style="max-height: 180px; object-fit: cover;">
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Asset Name:</div>
                            <div class="col-7" id="detailAssetName"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Location:</div>
                            <div class="col-7" id="detailLocation"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5">Seating Capacity:</div>
                            <div class="col-7" id="detailCapacity"></div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-md-4">
                        <h6 class="fw-bold mb-3">Purpose/Event Details</h6>
                        <div class="mb-2 row">
                            <div class="col-5">Purpose/Event:</div>
                            <div class="col-7" id="detailPurposeRight"></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5"><i class="bi bi-calendar-event me-1"></i> Date & Time:</div>
                            <div class="col-7"><span id="detailScheduleFull"></span></div>
                        </div>
                        <div class="mb-2 row">
                            <div class="col-5"><i class="bi bi-card-text me-1"></i> Notes:</div>
                            <div class="col-7" id="detailNotes"></div>
                        </div>
                        <div class="mb-2">
                            <div><i class="bi bi-people-fill me-1"></i> Guests:</div>
                            <div id="detailGuests" class="ms-3"></div>
                        </div>

                        <!-- Buttons -->
                        <!-- Buttons + Remarks -->
                        <div class="mt-4">
                            <form method="POST" action="{{ route('bookings.approve') }}" id="approveForm" data-approver="{{ Auth::user()->name }}">
                                @csrf
                                <input type="hidden" name="booking_id" id="approveBookingId">

                                <div class="mb-2">
                                    <label for="approveRemarks" class="form-label small">Remarks (optional)</label>
                                    <textarea name="remarks" id="approveRemarks" class="form-control form-control-sm" rows="2"></textarea>
                                </div>

                                <button class="btn btn-success btn-sm" type="submit">Approve</button>
                            </form>


                            <form method="POST" action="{{ route('bookings.reject') }}">
                                @csrf
                                <input type="hidden" name="booking_id" id="rejectBookingId">

                                <div class="mb-2">
                                    <label for="rejectRemarks" class="form-label small">Remarks (optional)</label>
                                    <textarea name="remarks" id="rejectRemarks" class="form-control form-control-sm" rows="2"></textarea>
                                </div>

                                <button class="btn btn-danger btn-sm" type="submit">Disapprove</button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>




    </div>
