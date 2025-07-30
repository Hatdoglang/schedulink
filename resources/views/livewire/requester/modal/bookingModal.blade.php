<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Step Tracker Styles -->
<style>
    .step-circle {
        width: 30px;
        height: 30px;
        line-height: 30px;
        border-radius: 50%;
        display: inline-block;
        text-align: center;
    }
</style>

<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" x-data="{ showCalendar: false, showConfirm: false }">
        <div class="modal-content">
            <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Asset Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>



                <div class="modal-body">
                    <!-- Step 1 -->
                    <div x-data="{ selectedAssetType: '', vehicleTypeId: {{ $assetTypes->firstWhere('name', 'Vehicle')->id ?? 'null' }} }" x-show="!showCalendar && !showConfirm">

                        <!-- Asset Type -->
                        <div class="mb-3">
                            <label>Asset Type</label>
                            <select name="asset_type_id" class="form-select" required x-model="selectedAssetType">
                                <option value="">-- Select --</option>
                                @foreach ($assetTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Show when Asset Type is Vehicle -->
                        <template x-if="selectedAssetType == vehicleTypeId">
                            <div>
                                <div class="mb-3">
                                    <label>Purpose</label>
                                    <textarea name="purpose" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Destination</label>
                                    <input name="destination" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>No. of Seats</label>
                                    <input type="number" name="no_of_seats" class="form-control" min="1">
                                </div>
                            </div>
                        </template>

                        <!-- Show when NOT Vehicle (e.g., Conference Room) -->
                        <template x-if="selectedAssetType != vehicleTypeId">
                            <div>
                                <div class="mb-3">
                                    <label>Conference Room</label>
                                    <select name="asset_detail_id" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        @foreach ($assetDetails as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->asset_name }} ({{ $detail->location }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Venue</label>
                                    <input name="destination" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Purpose</label>
                                    <textarea name="purpose" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>No. of Seats</label>
                                    <input type="number" name="no_of_seats" class="form-control" min="1">
                                </div>
                            </div>
                        </template>

                        <!-- Button -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-custom"
                                @click="showCalendar = true; setTimeout(() => { initCalendar(); bindTimeSlotEvents(); }, 300)">
                                Check Availability
                            </button>
                        </div>
                    </div>


                    <!-- Step 2 -->
                    <div x-show="showCalendar && !showConfirm" class="row">
                        @include('livewire.requester.modal.partials.profile')

                        <div class="col-md-6">
                            <h6>Pick a Date</h6>
                            <input type="hidden" name="scheduled_date" id="selected_date">
                            <div id="calendar" style="height: 450px;" wire:ignore></div>
                            {{-- <p class="mt-2">
                                Selected Date: <span id="calendar-picked-date" class="text-primary fw-semibold"></span>
                            </p> --}}
                        </div>

                        <div class="col-md-3 border-start">
                            <h6>Choose Time Slot</h6>

                            {{-- Display Selected Time Range --}}
                            <div class="mb-2">
                                <label>From</label>
                                <input type="text" id="display_time_from" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label>To</label>
                                <input type="text" id="display_time_to" class="form-control" readonly>
                            </div>

                            {{-- Hidden Inputs --}}
                            <input type="hidden" name="time_from" id="time_from">
                            <input type="hidden" name="time_to" id="time_to">

                            {{-- All Day Checkbox --}}
                            <div class="form-check mb-2">
                                <input class="form-check-input time-slot-radio" type="radio" name="time_slot" data-all-day="true" id="slot_all_day">
                                <label class="form-check-label" for="slot_all_day">All Day</label>
                            </div>

                            {{-- Time Slot Options --}}
                            @php
                                $start = \Carbon\Carbon::createFromTime(8, 0);
                                $end = \Carbon\Carbon::createFromTime(17, 0);
                                $interval = 30;
                                $slots = [];

                                while ($start < $end) {
                                    $from = $start->copy();
                                    $to = $from->copy()->addMinutes($interval);
                                    $slots[] = [$from->format('H:i'), $to->format('H:i')];
                                    $start->addMinutes($interval);
                                }
                            @endphp

                            <div style="max-height: 280px; overflow-y: auto;">
                                @foreach ($slots as $i => [$from, $to])
                                    <div class="form-check mb-2">
                                        <input class="form-check-input time-slot-radio" type="radio" name="time_slot"
                                            value="{{ $from }}-{{ $to }}" id="slot{{ $i }}">
                                        <label class="form-check-label" for="slot{{ $i }}">
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $from)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $to)->format('g:i A') }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="col-md-12 mt-3 d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary me-2" @click="showCalendar = false">Back</button>
                            <button type="button" class="btn btn-outline-custom" @click="showConfirm = true; updateSummary()">Next</button>
                        </div>
                    </div>


                    <!-- Step 3: Confirmation -->
                    <div x-show="showConfirm" class="row">
                        {{-- Left Column: User Profile --}}
                        @include('livewire.requester.modal.partials.profile')

                        {{-- User Info --}}
                        <div class="col-md-3 text-center border-end">
                            <div class="border rounded p-3 mb-3 bg-light">
                                <h6 class="text-secondary fw-bold mb-2">User Info</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                <p class="mb-0"><strong>Department:</strong> {{ auth()->user()->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- Center Column: Reservation Summary --}}
                        <div class="col-md-6">
                            <h6 class="mb-3 text-secondary">Reservation Summary</h6>
                            <ul class="list-group mb-3">
                                <li class="list-group-item text-muted"><strong>Purpose:</strong> <span id="confirm_purpose"></span></li>
                                <li class="list-group-item text-muted"><strong>Date:</strong> <span id="confirm_date"></span></li>
                                <li class="list-group-item text-muted"><strong>Time:</strong> <span id="confirm_time"></span></li>
                            </ul>

                            <div class="mb-3">
                                <label class="text-secondary">Notes (Optional)</label>
                                <textarea name="notes" class="form-control border border-gray" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="text-secondary">Add Guests (Optional)</label>
                                <div id="guestInputs">
                                    <div class="input-group mb-2">
                                        <input type="email" name="guests[]" class="form-control" placeholder="guest@example.com">
                                        <button class="btn btn-outline-secondary" type="button" onclick="addGuestInput()">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Confirmation --}}
                        <div class="col-md-3 border-start">
                            <div class="p-3">
                                <h6 class="text-secondary">Ready to Submit</h6>
                                <p class="text-muted">Review your details before submitting.</p>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-secondary" @click="showConfirm = false">Back</button>
                                    <button type="submit" class="btn btn-outline-custom">Submit Reservation</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </form>
        </div>
    </div>
</div>
