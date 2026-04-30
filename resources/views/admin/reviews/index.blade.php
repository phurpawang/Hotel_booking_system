@extends('admin.layout')

@section('title', 'Reviews Management')

@section('content')
<div class="dashboard-header">
    <h1>Guest Reviews Management</h1>
    <p class="text-muted">Monitor and moderate all guest reviews in the system</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-dismiss="alert"></button>
</div>
@endif

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-body text-center">
                <h3 class="mb-1">{{ $reviews->total() }}</h3>
                <p class="text-muted">Total Reviews</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-body text-center">
                <h3 class="mb-1">
                    <span class="badge bg-success">{{ $reviews->where('status', 'APPROVED')->count() }}</span>
                </h3>
                <p class="text-muted">Approved</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-body text-center">
                <h3 class="mb-1">
                    <span class="badge bg-warning text-dark">{{ $reviews->where('status', 'PENDING')->count() }}</span>
                </h3>
                <p class="text-muted">Pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card">
            <div class="card-body text-center">
                <h3 class="mb-1">
                    <span class="badge bg-danger">{{ $reviews->where('status', 'REJECTED')->count() }}</span>
                </h3>
                <p class="text-muted">Rejected</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="dashboard-card" style="margin-bottom: 20px;">
    <div class="card-body">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" placeholder="Search guest name or email..." class="form-control" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="hotel_id" class="form-control">
                    <option value="">All Hotels</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>
                            {{ $hotel->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reviews Table -->
<div class="dashboard-card">
    <div class="card-body">
        @if($reviews->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Guest & Hotel</th>
                            <th>Rating</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Reply</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>
                                <div>
                                    <p class="mb-0"><strong>{{ $review->guest_name }}</strong></p>
                                    <small class="text-muted">{{ $review->guest_email }}</small>
                                    <p class="mb-0 mt-1"><small><strong>Hotel:</strong> {{ $review->hotel->name }}</small></p>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $review->overall_rating }}/10</span>
                            </td>
                            <td>
                                <small>{{ $review->review_date->format('M d, Y') }}</small>
                            </td>
                            <td>
                                @if($review->status === 'APPROVED')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($review->status === 'PENDING')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if($review->manager_reply)
                                    <span class="badge bg-info">
                                        <i class="fas fa-reply mr-1"></i>Replied
                                    </span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-star text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-600 text-lg">No reviews found</p>
            </div>
        @endif
    </div>
</div>

<style>
    .dashboard-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
    }

    .card-body {
        padding: 20px;
    }
</style>
@endsection
