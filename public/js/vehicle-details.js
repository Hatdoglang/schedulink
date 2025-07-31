
document.addEventListener("DOMContentLoaded", function () {
    // View Details Logic
    document.querySelectorAll(".view-details").forEach((button) => {
        button.addEventListener("click", function () {
            // Show card
            document.getElementById("vehicleBookingDetailsCard").style.display = "block";

            // Populate left column
            document.getElementById("vehicleDetailUser").textContent = this.dataset.user;
            document.getElementById("vehicleDetailDepartment").textContent = this.dataset.department;
            document.getElementById("vehicleDetailBranch").textContent = this.dataset.branch;
            document.getElementById("vehicleDetailRequestedAt").textContent = this.dataset.requestedAt;
            document.getElementById("vehicleDetailPurpose").textContent = this.dataset.purpose;
            document.getElementById("vehicleDetailAssetType").textContent = this.dataset.asset;
            document.getElementById("vehicleDetailSeats").textContent = this.dataset.seats;
            document.getElementById("vehicleDetailFirstApprover").textContent = this.dataset.firstApprover;
            document.getElementById("vehicleDetailSecondApprover").textContent = this.dataset.secondApprover;

            // Populate center column - Vehicle Info
            document.getElementById("vehicleDetailAssetName").textContent = this.dataset.assetName;
            document.getElementById("vehicleDetailBrand").textContent = this.dataset.brand || "N/A";
            document.getElementById("vehicleDetailModel").textContent = this.dataset.model || "N/A";
            document.getElementById("vehicleDetailCapacity").textContent = this.dataset.capacity;
            document.getElementById("vehicleDetailPlate").textContent = this.dataset.plate || "N/A";
            document.getElementById("vehicleDetailColor").textContent = this.dataset.color || "N/A";
            document.getElementById("vehicleDetailDriver").textContent = this.dataset.driver || "N/A";
            document.getElementById("vehicleDetailOdometerStart").textContent = this.dataset.odometerStart || "N/A";
            document.getElementById("vehicleDetailOdometerEnd").textContent = this.dataset.odometerEnd || "N/A";

            // Populate right column - Schedule
            document.getElementById("vehicleDetailPurposeRight").textContent = this.dataset.purpose;
            document.getElementById("vehicleDetailScheduleFull").textContent = this.dataset.schedule;
            document.getElementById("vehicleDetailNotes").textContent = this.dataset.notes;

            // Guests
            let guests = JSON.parse(this.dataset.guests || "[]");
            document.getElementById("vehicleDetailGuests").innerHTML = guests.length > 0
                ? guests.map((email) => `<span class="badge bg-secondary me-1">${email}</span>`).join("")
                : '<span class="text-muted">No guests listed.</span>';

            // Status badge
            const status = this.dataset.status || "pending";
            const statusMap = {
                approved: { text: "Approved", class: "bg-success text-white" },
                pending: { text: "Pending", class: "bg-primary text-white" },
                rejected: { text: "Rejected", class: "bg-danger text-white" },
                disapproved: { text: "Rejected", class: "bg-danger text-white" },
            };
            const statusInfo = statusMap[status] || { text: "Unknown", class: "bg-secondary text-white" };
            const statusBadge = document.getElementById("vehicleDetailStatus");
            statusBadge.textContent = statusInfo.text;
            statusBadge.className = `badge rounded-pill ${statusInfo.class}`;

            // Set booking ID for forms
            document.getElementById("vehicleApproveBookingId").value = this.dataset.id;
            document.getElementById("vehicleRejectBookingId").value = this.dataset.id;
        });
    });

    // Approve button confirmation
    const approveForm = document.getElementById("vehicleApproveForm");
    if (approveForm) {
        approveForm.addEventListener("submit", function (e) {
            e.preventDefault(); // prevent default form submission

            Swal.fire({
                title: 'Confirm Approval',
                text: 'Are you sure you want to approve this request? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Submit the form if confirmed
                }
            });
        });
    }

    // Disapprove button confirmation
    const rejectForm = document.querySelector('form[action*="bookings.reject"]');
    if (rejectForm) {
        rejectForm.addEventListener("submit", function (e) {
            e.preventDefault(); // prevent default form submission

            Swal.fire({
                title: 'Confirm Disapproval',
                text: 'Are you sure you want to disapprove this request? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Disapprove',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Submit the form if confirmed
                }
            });
        });
    }
});
