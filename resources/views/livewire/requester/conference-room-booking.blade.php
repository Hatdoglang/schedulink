<div wire:ignore.self class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="dateModalLabel">Conference Room Booking</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="submitBooking">

                    <!-- Type of Asset -->
                    <div class="mb-3">
                        <label class="form-label">Type of Asset</label>
                        <select wire:model="asset_type_id" class="form-select" required>
                            <option value="" disabled>Select Type</option>
                            @foreach($assetTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Conference Room -->
                    <div class="mb-3">
                        <label class="form-label">Select Conference Room</label>
                        <select wire:model="asset_detail_id" class="form-select" required>
                            <option value="" disabled>Select Room</option>
                            @foreach($assetDetails as $detail)
                            <option value="{{ $detail->id }}">{{ $detail->asset_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Venue (Dropdown from location) -->
                    <div class="mb-3">
                        <label class="form-label">Venue</label>
                        <input type="text" class="form-control" value="{{ $venue }}" readonly>
                    </div>

                    <!-- Purpose -->
                    <div class="mb-3">
                        <label class="form-label">Purpose</label>
                        <textarea wire:model.defer="purpose" class="form-control" rows="2" required></textarea>
                    </div>

                    <!-- No. of Seats -->
                    <div class="mb-3">
                        <label class="form-label">No. of Seats</label>
                        <input wire:model.defer="no_of_seats" type="number" min="1" class="form-control" required>
                    </div>

                    <!-- Date -->
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input wire:model="scheduled_date" type="text" class="form-control" readonly>
                    </div>

                    <!-- Time From / To -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time From</label>
                            <input wire:model="time_from" type="time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Time To</label>
                            <input wire:model="time_to" type="time" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>