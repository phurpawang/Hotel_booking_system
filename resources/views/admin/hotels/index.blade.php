@extends('admin.layout')

@section('title', 'Hotels Management')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-section h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .header-section i {
        font-size: 40px;
    }
    .header-actions a {
        background: rgba(255, 255, 255, 0.25) !important;
        border: 2px solid white;
        color: white !important;
        padding: 12px 25px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .header-actions a:hover {
        background: rgba(255, 255, 255, 0.35) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(20, 184, 166, 0.4) !important;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        border-left: 4px solid;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.total {
        border-left-color: #14b8a6;
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.05) 0%, white 100%);
    }

    .stat-card.approved {
        border-left-color: #22c55e;
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.05) 0%, white 100%);
    }

    .stat-card.pending {
        border-left-color: #f59e0b;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, white 100%);
    }

    .stat-card.rejected {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, white 100%);
    }

    .stat-card-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-card.total .stat-card-icon {
        background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
    }

    .stat-card.approved .stat-card-icon {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .stat-card.pending .stat-card-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-card.rejected .stat-card-icon {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .stat-card-body {
        flex: 1;
    }

    .stat-card-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 5px;
    }

    .stat-card-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .filter-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 15px;
        align-items: center;
    }

    .filter-item {
        display: flex;
        gap: 10px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus {
        border-color: #14b8a6 !important;
        box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1) !important;
        outline: none;
    }

    .btn-filter {
        background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%) !important;
        color: white !important;
        padding: 12px 25px !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(20, 184, 166, 0.4) !important;
    }

    .btn-reset {
        background: #6b7280 !important;
        color: white !important;
        padding: 12px 25px !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-left: 10px;
    }

    .btn-reset:hover {
        background: #4b5563 !important;
        transform: translateY(-2px);
    }

    .table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
        color: white;
    }

    .data-table thead th {
        padding: 18px 20px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
        border: none;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }

    .data-table tbody tr:nth-child(even) {
        background: rgba(20, 184, 166, 0.02);
    }

    .data-table tbody tr:hover {
        background: rgba(20, 184, 166, 0.08);
    }

    .data-table tbody td {
        padding: 18px 20px;
        font-size: 14px;
        color: #374151;
    }

    .data-table tbody td strong {
        color: #14b8a6;
        font-weight: 600;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .badge-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        align-items: center;
    }

    .btn-action {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        white-space: nowrap;
        flex-shrink: 0;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-view {
        background: linear-gradient(135deg, #14b8a6 0%, #06b6d4 100%);
    }

    .btn-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .text-muted {
        color: #9ca3af;
        font-size: 12px;
    }

    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #6b7280;
        font-size: 16px;
    }

    /* Modal Styling */
    .modal-content {
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: none !important;
        padding: 18px 20px !important;
    }

    .modal-header .modal-title {
        font-weight: 700 !important;
        font-size: 16px !important;
        color: white !important;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header .modal-title::before {
        content: "\f071";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 18px;
    }

    .modal-header .close {
        color: white !important;
        opacity: 0.8 !important;
        transition: opacity 0.3s ease;
    }

    .modal-header .close:hover {
        opacity: 1 !important;
    }

    .modal-body {
        padding: 20px !important;
        background: #f9fafb;
    }

    .modal-body .form-group {
        margin-bottom: 0;
    }

    .modal-body .form-group label {
        display: block;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modal-body textarea.form-control {
        padding: 12px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-family: inherit !important;
        resize: vertical;
        background: white !important;
        transition: all 0.3s ease !important;
        min-height: 90px !important;
        max-height: 120px !important;
    }

    .modal-body textarea.form-control:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        outline: none;
        background: white !important;
    }

    .modal-body textarea.form-control::placeholder {
        color: #9ca3af;
    }

    .modal-footer {
        background: white !important;
        border-top: 1px solid #e5e7eb !important;
        padding: 15px !important;
        display: flex !important;
        gap: 10px !important;
        justify-content: flex-end !important;
    }

    .modal-footer .btn {
        padding: 8px 20px !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .modal-footer .btn-secondary {
        background: #e5e7eb !important;
        color: #374151 !important;
    }

    .modal-footer .btn-secondary:hover {
        background: #d1d5db !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .modal-footer .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
    }

    .modal-footer .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4) !important;
    }

    .modal-footer .btn-danger::before {
        content: "\f05e";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
    }

    .modal-dialog {
        max-width: 400px !important;
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        .header-section h1 {
            font-size: 24px;
        }
        .filter-grid {
            grid-template-columns: 1fr;
        }
        .action-buttons {
            flex-direction: column;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .modal-dialog {
            margin: 15px !important;
        }
    }
</style>

<div class="header-section">
    <h1><i class="fas fa-building"></i> Hotels Management</h1>
    <div class="header-actions">
        <a href="{{ route('admin.hotels.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Add New Hotel
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card total">
        <div class="stat-card-content">
            <div class="stat-card-icon">
                <i class="fas fa-hotel"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Total Hotels</span>
                <div class="stat-card-value">{{ $totalHotels ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="stat-card approved">
        <div class="stat-card-content">
            <div class="stat-card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Approved</span>
                <div class="stat-card-value">{{ $approvedCount ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="stat-card pending">
        <div class="stat-card-content">
            <div class="stat-card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Pending</span>
                <div class="stat-card-value">{{ $pendingCount ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="stat-card rejected">
        <div class="stat-card-content">
            <div class="stat-card-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Rejected</span>
                <div class="stat-card-value">{{ $rejectedCount ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-section">
    <form action="{{ route('admin.hotels.index') }}" method="GET" class="filter-form">
        <div class="filter-grid">
            <div class="filter-item">
                <input type="text" name="search" placeholder="Search by name..." value="{{ request('search') }}" class="form-control">
            </div>
            <div class="filter-item">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="filter-item">
                <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                <a href="{{ route('admin.hotels.index') }}" class="btn-reset"><i class="fas fa-redo"></i> Reset</a>
            </div>
        </div>
    </form>
</div>


<!-- Hotels Table -->
<div class="table-card">
    <div class="table-responsive">
        @if($hotels->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hotel Name</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hotels as $hotel)
                        <tr>
                            <td><strong>#{{ $hotel->id }}</strong></td>
                            <td>
                                <strong>{{ $hotel->name }}</strong><br>
                                <small class="text-muted">{{ $hotel->property_type }}</small>
                            </td>
                            <td>{{ $hotel->owner->name ?? 'N/A' }}</td>
                            <td>Bhutan</td>
                            <td>{{ $hotel->phone }}</td>
                            <td>
                                @if(strtolower($hotel->status) == 'approved')
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Approved</span>
                                @elseif(strtolower($hotel->status) == 'pending')
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                @elseif(strtolower($hotel->status) == 'rejected')
                                    <span class="badge badge-danger"><i class="fas fa-ban"></i> Rejected</span>
                                @endif
                            </td>
                            <td>{{ $hotel->created_at->format('M d, Y') }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="btn-action btn-view" title="View">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                @if(strtolower($hotel->status) == 'pending')
                                    <form action="{{ route('admin.hotels.approve', $hotel->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-success" title="Approve" data-toggle="tooltip">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn-action btn-danger" title="Reject" data-toggle="modal" data-target="#rejectModal{{ $hotel->id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                    
                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $hotel->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $hotel->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel{{ $hotel->id }}">Reject Hotel Registration</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.hotels.reject', $hotel->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="rejection_reason{{ $hotel->id }}"><i class="fas fa-comment-slash" style="color: #ef4444; margin-right: 8px;"></i>Rejection Reason</label>
                                                            <textarea class="form-control" id="rejection_reason{{ $hotel->id }}" name="rejection_reason" rows="5" required placeholder="Please provide a detailed reason for rejecting this hotel registration..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                            <i class="fas fa-times"></i> Cancel
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-ban"></i> Reject Hotel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-data"><i class="fas fa-inbox"></i> No hotels found</p>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($hotels->count() > 0)
    <div class="pagination-wrapper">
        {{ $hotels->links() }}
    </div>
@endif

@endsection
