document.addEventListener("DOMContentLoaded", function () {
    const notyf = new Notyf({
        duration: 3000,
        position: { x: "right", y: "top" },
    });

    const searchInput = document.getElementById("bookingSearch");
    const tableBody = document.querySelector("#bookingTable tbody");

    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const filter = searchInput.value.toLowerCase();
            const rows = tableBody.querySelectorAll("tr");

            rows.forEach((row) => {
                const cells = row.querySelectorAll("td");
                let match = false;

                cells.forEach((cell, index) => {
                    if (index < cells.length - 1) {
                        const text = cell.textContent.toLowerCase();
                        if (text.includes(filter)) match = true;
                    }
                });

                row.style.display = filter === "" || match ? "" : "none";
            });
        });
    }

    function cleanBackdrop() {
        document
            .querySelectorAll(".modal-backdrop")
            .forEach((el) => el.remove());
        document.body.classList.remove("modal-open");
        document.body.style.paddingRight = null;
    }

    // Open/close details modal
    document.addEventListener("open-details-modal", () => {
        new bootstrap.Modal(
            document.getElementById("bookingDetailsModal")
        ).show();
    });

    document.addEventListener("close-details-modal", () => {
        const modalEl = document.getElementById("bookingDetailsModal");
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        setTimeout(cleanBackdrop, 300);
    });

    // Open/close disapprove modal
    document.addEventListener("open-disapprove-modal", () => {
        new bootstrap.Modal(document.getElementById("disapproveModal")).show();
    });

    document.addEventListener("close-disapprove-modal", () => {
        const modalEl = document.getElementById("disapproveModal");
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        setTimeout(cleanBackdrop, 300);
    });

    // Bootstrap event cleanup for all modals
    document.querySelectorAll(".modal").forEach((modal) => {
        modal.addEventListener("hidden.bs.modal", cleanBackdrop);
    });

    // Approve button
    document.body.addEventListener("click", function (e) {
        const approveBtn = e.target.closest(".approve-button");
        if (approveBtn && approveBtn.dataset.id) {
            const bookingId = approveBtn.dataset.id;
            const componentId = document
                .querySelector("[wire\\:id]")
                ?.getAttribute("wire:id");

            if (componentId) {
                Livewire.find(componentId).call("approveBooking", bookingId);
                notyf.success("Approval request sent.");
            }
        }
    });

    // Disapprove button
    document.body.addEventListener("click", function (e) {
        const disBtn = e.target.closest(".disapprove-button");
        if (disBtn && disBtn.dataset.id) {
            const bookingId = disBtn.dataset.id;
            const componentId = document
                .querySelector("[wire\\:id]")
                ?.getAttribute("wire:id");

            if (componentId) {
                Livewire.find(componentId).call(
                    "openDisapproveModal",
                    bookingId
                );
            }
        }
    });

    // Livewire notifications
    document.addEventListener("notify", function (e) {
        const { type, message } = e.detail;

        switch (type) {
            case "success":
                notyf.success(message);
                break;
            case "error":
            case "warning":
                notyf.error(message);
                break;
            case "info":
            default:
                notyf.open({ type: "info", message });
                break;
        }
    });

    // Disapproval success
    document.addEventListener("disapproval-success", () => {
        notyf.error("Booking was disapproved.");
    });

    // Clear Livewire state
    document.addEventListener("reset-selected-booking", () => {
        setTimeout(() => {
            const componentId = document
                .querySelector("[wire\\:id]")
                ?.getAttribute("wire:id");
            if (componentId) {
                Livewire.find(componentId).call("clearSelectedBooking");
            }
        }, 300);
    });
});
