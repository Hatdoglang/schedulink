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
