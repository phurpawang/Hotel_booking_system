@extends('admin.layout')

@section('title', 'Hotels Management')

@section('content')
<div class="dashboard-header">
    <h1>Hotels Management</h1>
    <div class="header-actions">
        <a href="{{ route('admin.hotels.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Add New Hotel
        </a>
    </div>
</div>

<!-- Filters -->
<div class="dashboard-card" style="margin-bottom: 20px;">
    <div class="card-body">
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
</div>

<!-- Hotels Table -->
<div class="dashboard-card">
    <div class="card-body">
        @if($hotels->count() > 0)
            <div class="table-responsive">
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
                                <td>#{{ $hotel->id }}</td>
                                <td>
                                    <strong>{{ $hotel->name }}</strong><br>
                                    <small class="text-muted">{{ $hotel->property_type }}</small>
                                </td>
                                <td>{{ $hotel->owner->name ?? 'N/A' }}</td>
                                <td>Bhutan</td>
                                <td>{{ $hotel->phone }}</td>
                                <td>
                                    @if($hotel->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($hotel->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($hotel->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $hotel->created_at->format('M d, Y') }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($hotel->status == 'pending')
                                        <form action="{{ route('admin.hotels.approve', $hotel->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-success" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" data-confirm="Are you sure you want to delete this hotel?">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $hotels->links() }}
            </div>
        @else
            <p class="no-data">No hotels found</p>
        @endif
    </div>
</div>

@push('styles')
<style>
.header-actions {
    display: flex;
    gap: 10px;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #003580;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #00285f;
}

.filter-form {
    width: 100%;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 15px;
    align-items: center;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.btn-filter, .btn-reset {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-filter {
    background: #003580;
    color: #fff;
}

.btn-reset {
    background: #6c757d;
    color: #fff;
    margin-left: 10px;
}

.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-action {
    padding: 6px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    color: #fff;
    background: none;
    transition: opacity 0.2s;
}

.btn-action:hover {
    opacity: 0.8;
}

.btn-view {
    background: #17a2b8;
}

.btn-edit {
    background: #ffc107;
}

.btn-success {
    background: #28a745;
}

.btn-delete {
    background: #dc3545;
}

.text-muted {
    color: #6c757d;
    font-size: 13px;
}

.pagination-wrapper {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
@endsection
