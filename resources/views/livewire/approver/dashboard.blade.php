<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Approver Dashboard</h2>
                <button wire:click="refreshData" class="btn btn-outline-primary">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['pending_approvals'] ?? 0 }}</h3>
                            <p class="card-text">Pending Approvals</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['approved_today'] ?? 0 }}</h3>
                            <p class="card-text">Approved Today</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['rejected_today'] ?? 0 }}</h3>
                            <p class="card-text">Rejected Today</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title">{{ $statistics['total_processed'] ?? 0 }}</h3>
                            <p class="card-text">Total Processed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Approvals -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pending Approvals</h5>
                </div>
                <div class="card-body">
                    @if(count($pendingBookings) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Requester</th>
                                        <th>Asset</th>
                                        <th>Date & Time</th>
                                        <th>Purpose</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingBookings as $booking)
                                        <tr>
                                            <td>
                                                <strong>{{ $booking->user->full_name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $booking->user->email ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $booking->assetType->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $booking->assetDetail->asset_name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $booking->time_from }} - {{ $booking->time_to }}</small>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($booking->purpose, 30) }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/approver/approvals/{{ $booking->id }}/approve" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <a href="/approver/approvals/{{ $booking->id }}/reject" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                    <a href="/approver/approvals/{{ $booking->id }}/history" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="/approver/approvals/pending" class="btn btn-outline-primary btn-sm">View All Pending</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No pending approvals</p>
                            <small class="text-muted">All caught up! No bookings require your approval at this time.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Approval Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Actions</h5>
                </div>
                <div class="card-body">
                    @if(count($recentApprovals) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentApprovals as $approval)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <strong>{{ $approval->booking->user->full_name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $approval->booking->assetType->name ?? 'N/A' }}</small>
                                            <br>
                                            <small class="text-muted">{{ $approval->created_at->format('M d, H:i') }}</small>
                                        </div>
                                        <div>
                                            @if($approval->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($approval->comments)
                                        <small class="text-muted d-block mt-1">{{ Str::limit($approval->comments, 50) }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="/approver/approvals/my-history" class="btn btn-outline-primary btn-sm">View All History</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent actions</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Hierarchy -->
    @if(count($approvalHierarchy) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Approval Authority</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($approvalHierarchy as $assetTypeId => $approvers)
                            <div class="col-md-4 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $approvers->first()->assetType->name ?? 'N/A' }}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Approval Level: {{ $approvers->first()->approval_level ?? 'N/A' }}
                                            </small>
                                        </p>
                                        <a href="/approver/approvals/workflow/{{ $assetTypeId }}" class="btn btn-sm btn-outline-info">
                                            View Workflow
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="/approver/approvals/pending" class="btn btn-warning w-100">
                                <i class="fas fa-clock"></i> Pending Approvals
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/approver/approvals/my-history" class="btn btn-outline-primary w-100">
                                <i class="fas fa-history"></i> My History
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/requester/my-dashboard" class="btn btn-outline-info w-100">
                                <i class="fas fa-calendar"></i> My Bookings
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="/profile" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>