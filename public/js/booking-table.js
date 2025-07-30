document.addEventListener("DOMContentLoaded", function () {
    // Show logged-in user in 1st Level Approver on Approve
    const approveForm = document.getElementById("approveForm");
    if (approveForm) {
        const approverName = approveForm.dataset.approver;

        approveForm.addEventListener("submit", function () {
            const firstApproverElement = document.getElementById(
                "detailFirstApprover"
            );

            // Only overwrite if it still says Pending or is empty
            if (
                firstApproverElement &&
                (firstApproverElement.innerText.trim() === "Pending" ||
                    firstApproverElement.innerText.trim() === "")
            ) {
                firstApproverElement.innerText = approverName;
            }
        });
    }

    const searchInput = document.getElementById("bookingSearch");
    const tableBody = document
        .getElementById("bookingTable")
        ?.getElementsByTagName("tbody")[0];
    const detailBox = document.getElementById("bookingDetailsCard");

    // Search filter
    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const filter = searchInput.value.toLowerCase();
            const rows = tableBody.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td");
                let match = false;
                for (let j = 0; j < cells.length - 1; j++) {
                    const text = cells[j].textContent || cells[j].innerText;
                    if (text.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display =
                    filter === "" ? "" : match ? "" : "none";
            }
        });
    }

    // View Details Handler
    document.querySelectorAll(".view-details").forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            const fields = {
                detailUser: this.dataset.user,
                detailBranch: this.dataset.branch,
                detailDepartment: this.dataset.department,
                detailRequestedAt: this.dataset.requestedAt,
                detailPurpose: this.dataset.purpose,
                detailAssetType: this.dataset.asset,
                detailSeats: this.dataset.seats,
                detailFirstApprover: this.dataset.firstApprover,
                detailSecondApprover: this.dataset.secondApprover,
                detailAssetName: this.dataset.assetName,
                detailLocation: this.dataset.location,
                detailCapacity: this.dataset.capacity,
                detailPurposeRight: this.dataset.purpose,
                detailScheduleFull: this.dataset.schedule,
                detailNotes: this.dataset.notes,
            };

            // Populate each detail field
            for (const [id, value] of Object.entries(fields)) {
                const element = document.getElementById(id);
                if (element) {
                    element.innerText = value || "N/A";
                }
            }

            // Guests display
            const guestsData = this.dataset.guests || "";
            const guestsElement = document.getElementById("detailGuests");

            try {
                let guestsArray = [];

                if (guestsData.trim().startsWith("[")) {
                    guestsArray = JSON.parse(guestsData);
                } else {
                    guestsArray = guestsData
                        .split(",")
                        .map((g) => g.trim())
                        .filter(Boolean);
                }

                if (guestsArray.length > 0) {
                    guestsElement.innerHTML = guestsArray
                        .map(
                            (email) =>
                                `<p class="mb-0" style="color: #0d6efd;"><i class="bi bi-person-circle me-1"></i>${email}</p>`
                        )
                        .join("");
                } else {
                    guestsElement.innerText = "None";
                }
            } catch (error) {
                console.warn("Invalid guests data:", guestsData);
                guestsElement.innerText = "None";
            }

            // Booking ID for approve/reject
            const approveId = document.getElementById("approveBookingId");
            const rejectId = document.getElementById("rejectBookingId");
            if (approveId) approveId.value = this.dataset.id;
            if (rejectId) rejectId.value = this.dataset.id;

            // Set and style status badge
            const status = this.dataset.status?.toLowerCase() || "pending";
            const statusBadge = document.getElementById("detailStatus");

            if (statusBadge) {
                statusBadge.textContent =
                    status.charAt(0).toUpperCase() + status.slice(1);
                statusBadge.className = "badge px-2 py-1";

                switch (status) {
                    case "approved":
                        statusBadge.classList.add("bg-success", "text-white");
                        break;
                    case "rejected":
                        statusBadge.classList.add("bg-danger", "text-white");
                        break;
                    case "cancelled":
                        statusBadge.classList.add("bg-secondary", "text-white");
                        break;
                    case "pending":
                    default:
                        statusBadge.classList.add(
                            "bg-light",
                            "text-primary",
                            "border",
                            "border-primary"
                        );
                        break;
                }
            }

            // Show detail card
            if (detailBox) {
                detailBox.style.display = "block";
                detailBox.scrollIntoView({ behavior: "smooth" });
            }
        });
    });
});
