<style>
    @media (min-width: 992px) {
        #viewLeaveModal .modal-dialog {
            position: relative;
            left: 100px; /* Shift right to compensate for the left sidebar so it appears centered in the content area */
        }
    }
</style>
<div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                <h5 class="modal-title" id="viewLeaveModalLabel"><i class="fas fa-file-alt me-2"></i>Leave Request
                    Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div id="viewLeaveLoader" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="viewLeaveContent" style="display: none;">
                    <div class="row mb-3 border-bottom pb-2">
                        <div class="col-md-4 fw-bold text-muted">Employee:</div>
                        <div class="col-md-8 fw-semibold" id="vl-employee"></div>
                    </div>
                    <div class="row mb-3 border-bottom pb-2">
                        <div class="col-md-4 fw-bold text-muted">Leave Type:</div>
                        <div class="col-md-8" id="vl-type"></div>
                    </div>
                    <div class="row mb-3 border-bottom pb-2">
                        <div class="col-md-4 fw-bold text-muted">Dates:</div>
                        <div class="col-md-8">
                            <span id="vl-start"></span> to <span id="vl-end"></span>
                            <span class="badge bg-secondary ms-2" id="vl-days"></span>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom pb-2">
                        <div class="col-md-4 fw-bold text-muted">Status:</div>
                        <div class="col-md-8">
                            <span class="badge px-3 py-2" id="vl-status"></span>
                        </div>
                    </div>
                    <div class="row mb-3 border-bottom pb-2">
                        <div class="col-md-4 fw-bold text-muted">Reason:</div>
                        <div class="col-md-8" id="vl-reason"></div>
                    </div>
                    <div class="row mb-3 pb-2" id="vl-rejection-container" style="display: none;">
                        <div class="col-md-4 fw-bold text-danger">Rejection Reason:</div>
                        <div class="col-md-8 text-danger" id="vl-rejection"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function fetchAndShowLeaveModal(url) {
        const modalElement = document.getElementById('viewLeaveModal');
        const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);

        // UI elements
        const loader = document.getElementById('viewLeaveLoader');
        const content = document.getElementById('viewLeaveContent');
        const rejectionContainer = document.getElementById('vl-rejection-container');

        // Reset view
        loader.style.display = 'block';
        content.style.display = 'none';
        rejectionContainer.style.display = 'none';

        modal.show();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Force Laravel to return JSON if conditionally applied, or fetch API response
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then(data => {
                // Populate modal data
                document.getElementById('vl-employee').textContent = data.employee;
                document.getElementById('vl-type').textContent = data.leaveType;
                document.getElementById('vl-start').textContent = data.startDate;
                document.getElementById('vl-end').textContent = data.endDate;
                document.getElementById('vl-days').textContent = data.totalDays + (data.totalDays > 1 ? ' days' : ' day');
                document.getElementById('vl-reason').textContent = data.reason || 'N/A';

                const statusBadge = document.getElementById('vl-status');
                statusBadge.textContent = data.status;
                statusBadge.className = 'badge px-3 py-2 ';
                if (data.status === 'Approved') statusBadge.classList.add('bg-success');
                else if (data.status.includes('Rejected')) statusBadge.classList.add('bg-danger');
                else if (data.status.includes('Pending Admin')) statusBadge.classList.add('bg-primary');
                else statusBadge.classList.add('bg-warning', 'text-dark');

                if (data.rejectionReason) {
                    document.getElementById('vl-rejection').textContent = data.rejectionReason;
                    rejectionContainer.style.display = 'flex';
                }

                // Show content
                loader.style.display = 'none';
                content.style.display = 'block';
            })
            .catch(error => {
                console.error("Error fetching leave details:", error);
                loader.innerHTML = '<div class="alert alert-danger m-3">Failed to load leave details. Trying to navigate instead...</div>';
                // Fallback: simply navigate to the page if API fails
                setTimeout(() => { window.location.href = url; }, 1000);
            });
    }
</script>