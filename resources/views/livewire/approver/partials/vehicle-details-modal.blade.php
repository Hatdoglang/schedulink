<!-- Booking Details Modal -->
<div wire:ignore.self class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            @if ($selectedBooking)
                <div class="modal-header">
                    <h5 class="modal-title">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-4">
                            <h6 class="fw-bold mb-3">Request Information</h6>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Requested By:</div>
                                <div class="col-7">{{ $selectedBooking->user->first_name }} {{ $selectedBooking->user->last_name }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Department:</div>
                                <div class="col-7">{{ $selectedBooking->user->department->name ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Branch:</div>
                                <div class="col-7">{{ $selectedBooking->user->branch->name ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Date Requested:</div>
                                <div class="col-7">{{ \Carbon\Carbon::parse($selectedBooking->created_at)->format('M d, Y h:i A') }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Type of Asset:</div>
                                <div class="col-7">{{ $selectedBooking->assetType->name ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Purpose:</div>
                                <div class="col-7">{{ $selectedBooking->purpose ?? '—' }}</div>
                            </div>
                            <div class="mb-4 row">
                                <div class="col-5 text-end fw-semibold">No. of Seats:</div>
                                <div class="col-7">{{ $selectedBooking->no_of_seats ?? '—' }}</div>
                            </div>

                            <h6 class="fw-bold mb-3">Approval Information</h6>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">1st Approver:</div>
                                <div class="col-7">
                                    {{ $selectedBooking->first_approver_name ?? '—' }}
                                    @if ($selectedBooking->first_approved_at)
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($selectedBooking->first_approved_at)->format('M d, Y h:i A') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">2nd Approver:</div>
                                <div class="col-7">
                                    {{ $selectedBooking->second_approver_name ?? '—' }}
                                    @if ($selectedBooking->second_approved_at)
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::parse($selectedBooking->second_approved_at)->format('M d, Y h:i A') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Center Column -->
                        <div class="col-md-4 border-start">
                            <h6 class="fw-bold mb-3">Vehicle Information</h6>

                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Name:</div>
                                <div class="col-7">{{ $selectedBooking->assetDetail->asset_name ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Brand:</div>
                                <div class="col-7">{{ $selectedBooking->assetDetail->brand ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Model:</div>
                                <div class="col-7">{{ $selectedBooking->assetDetail->model ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">No. of Seats:</div>
                                <div class="col-7">{{ $selectedBooking->assetDetail->number_of_seats ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Plate#:</div>
                                <div class="col-7">{{ $selectedBooking->assetDetail->plate_number ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Color:</div>
                                <div class="col-7">{{ $selectedBooking->assetDetail->color ?? '—' }}</div>
                            </div>

                            <h6 class="fw-bold mt-4 mb-3">Driver & Odometer</h6>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Driver:</div>
                                <div class="col-7">{{ $selectedBooking->vehicleAssignment->driver->name ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Odometer Start:</div>
                                <div class="col-7">{{ $selectedBooking->vehicleAssignment->odometer_start ?? '—' }} km</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Odometer End:</div>
                                <div class="col-7">{{ $selectedBooking->vehicleAssignment->odometer_end ?? '—' }} km</div>
                            </div>
                        </div>


                        <!-- Right Column -->
                        <div class="col-md-4 border-start">
                            <h6 class="fw-bold mb-3">Project/Event</h6>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Name:</div>
                                <div class="col-7">{{ $selectedBooking->asset_name ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Purpose:</div>
                                <div class="col-7">{{ $selectedBooking->purpose ?? '—' }}</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col-5 text-end fw-semibold">Date & Time:</div>
                                <div class="col-7">
                                    {{ \Carbon\Carbon::parse($selectedBooking->scheduled_date)->format('F d, Y') }} |
                                    {{ \Carbon\Carbon::parse($selectedBooking->time_from)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($selectedBooking->time_to)->format('h:i A') }}

                                </div>
                            </div>
                            <div class="mb-4 row">
                                <label class="col-5 text-end fw-semibold col-form-label">Notes:</label>
                                <div class="col-7">
                                    <textarea class="form-control" rows="3" readonly>{{ $selectedBooking->notes ?? '—' }}</textarea>
                                </div>
                            </div>


                            <h6 class="fw-bold mb-2">Guests</h6>
                            @if ($selectedBooking->bookedGuests->isNotEmpty())
                                <ul class="list-unstyled ms-3">
                                    @foreach ($selectedBooking->bookedGuests as $guest)
                                        <li><i class="bi bi-person"></i> {{ $guest->email }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="ms-3">No guests listed.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger disapprove-button" data-id="{{ $selectedBooking->id }}">
                        Disapprove
                    </button>
                    <button type="button" class="btn btn-success approve-button" data-id="{{ $selectedBooking->id }}">
                        Approve
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
