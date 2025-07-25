<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-semibold">Create New Booking</h5>
                    <small class="text-muted">Fill in the details below to create your booking request</small>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <!-- Asset Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="assetType" class="form-label">Asset Type <span class="text-danger">*</span></label>
                                <select wire:model.live="selectedAssetType" id="assetType" class="form-select @error('selectedAssetType') is-invalid @enderror">
                                    <option value="">Select Asset Type</option>
                                    @foreach($assetTypes as $assetType)
                                        <option value="{{ $assetType->id }}">{{ $assetType->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedAssetType')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="assetDetail" class="form-label">Specific Asset <span class="text-danger">*</span></label>
                                <select wire:model="selectedAssetDetail" id="assetDetail" class="form-select @error('selectedAssetDetail') is-invalid @enderror" 
                                        {{ empty($assetDetails) ? 'disabled' : '' }}>
                                    <option value="">{{ empty($assetDetails) ? 'First select asset type' : 'Select Specific Asset' }}</option>
                                    @foreach($assetDetails as $assetDetail)
                                        <option value="{{ $assetDetail->id }}">{{ $assetDetail->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedAssetDetail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date and Time Selection -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="scheduledDate" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" wire:model="scheduledDate" id="scheduledDate" 
                                       class="form-control @error('scheduledDate') is-invalid @enderror"
                                       min="{{ now()->format('Y-m-d') }}">
                                @error('scheduledDate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="timeFrom" class="form-label">Start Time <span class="text-danger">*</span></label>
                                <input type="time" wire:model="timeFrom" id="timeFrom" 
                                       class="form-control @error('timeFrom') is-invalid @enderror">
                                @error('timeFrom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="timeTo" class="form-label">End Time <span class="text-danger">*</span></label>
                                <input type="time" wire:model="timeTo" id="timeTo" 
                                       class="form-control @error('timeTo') is-invalid @enderror">
                                @error('timeTo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Purpose -->
                        <div class="mb-4">
                            <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                            <textarea wire:model="purpose" id="purpose" rows="3" 
                                      class="form-control @error('purpose') is-invalid @enderror"
                                      placeholder="Please describe the purpose of your booking..."></textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 5 characters required</div>
                        </div>

                        <!-- Guests -->
                        <div class="mb-4">
                            <label class="form-label">Guests (Optional)</label>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" wire:model="newGuest" 
                                           class="form-control" 
                                           placeholder="Enter guest name"
                                           wire:keydown.enter="addGuest">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" wire:click="addGuest" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-plus me-2"></i>Add Guest
                                    </button>
                                </div>
                            </div>
                            
                            @if(!empty($guests))
                                <div class="mt-3">
                                    <div class="form-text mb-2">Guests added:</div>
                                    @foreach($guests as $index => $guest)
                                        <span class="badge bg-light text-dark border me-2 mb-1">
                                            {{ $guest }}
                                            <button type="button" wire:click="removeGuest({{ $index }})" 
                                                    class="btn-close btn-close-sm ms-2"></button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Additional Notes (Optional)</label>
                            <textarea wire:model="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Any additional information or special requirements..."></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('requester.bookings') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                            </a>
                            <div>
                                <button type="button" onclick="location.reload()" class="btn btn-outline-warning me-2">
                                    <i class="fas fa-undo me-2"></i>Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-save me-2"></i>Create Booking
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin me-2"></i>Creating...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Booking Guidelines</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled small">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bookings must be made at least 24 hours in advance</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>All bookings require approval from your manager</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>You will be notified once your booking is processed</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled small">
                                <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Booking confirmation usually takes 1-2 business days</li>
                                <li class="mb-2"><i class="fas fa-users text-info me-2"></i>Guest information is required for security purposes</li>
                                <li class="mb-2"><i class="fas fa-edit text-secondary me-2"></i>You can edit pending bookings before approval</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>