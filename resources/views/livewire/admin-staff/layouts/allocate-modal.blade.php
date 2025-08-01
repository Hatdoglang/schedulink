<!-- Vehicle Allocation Modal -->
<div wire:ignore.self class="modal fade" id="vehicleAllocationModal" tabindex="-1" aria-labelledby="vehicleAllocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vehicle Allocation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="allocateVehicle">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Vehicle Name Dropdown -->
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <select id="vehicleSelect" wire:model="form.asset_detail_id" class="form-select">
                                <option value="">Select Vehicle</option>
                                @foreach ($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->asset_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Auto-filled Fields -->
                        <div class="col-md-6">
                            <label class="form-label">Brand</label>
                            <input type="text" id="brand" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <input type="text" id="model" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. of Seats</label>
                            <input type="text" id="seats" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Plate Number</label>
                            <input type="text" id="plate" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Color</label>
                            <input type="text" id="color" class="form-control" readonly>
                        </div>

                        <div class="col-12">
                            <hr>
                        </div>

                        <!-- Driver + Odometer -->
                        <div class="col-md-6">
                            <label class="form-label">Driver</label>
                            <select wire:model="form.driver_id" class="form-select">
                                <option value="">Select Driver</option>
                                @foreach ($drivers as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Odometer Start</label>
                            <input type="number" wire:model="form.odometer_start" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Odometer End</label>
                            <input type="number" wire:model="form.odometer_end" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Allocate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const vehicles = @json($vehicles);

    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("vehicleSelect");
        if (select) {
            select.addEventListener("change", function() {
                const id = parseInt(this.value);
                const vehicle = vehicles.find(v => v.id === id);

                document.getElementById("brand").value = vehicle?.brand || "";
                document.getElementById("model").value = vehicle?.model || "";
                document.getElementById("seats").value = vehicle?.number_of_seats || "";
                document.getElementById("plate").value = vehicle?.plate_number || "";
                document.getElementById("color").value = vehicle?.color || "";
            });
        }

        const notyf = new Notyf({
            duration: 3000,
            position: {
                x: "right",
                y: "top"
            },
        });

        // ✅ Listen for browser event from PHP (Livewire dispatch)
        window.addEventListener('notify', event => {
            const {
                type,
                message
            } = event.detail;
            console.log("Notify event:", type, message); // 🧪 Debug log

            if (type === "success") {
                if (message.includes("Done submitting")) {
                    notyf.success("✅ Done submitting — already assigned.");
                } else {
                    notyf.success(message || "Success!");
                }
            } else {
                notyf.error(message || "Something went wrong.");
            }
        });


        // ✅ Close modal when event is dispatched
        window.addEventListener('close-allocate-modal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById("vehicleAllocationModal"));
            if (modal) modal.hide();
        });
    });
</script>
