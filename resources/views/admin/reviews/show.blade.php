@extends('admin.layout')

@section('title', 'Review Details')

@section('content')
<div class="dashboard-header">
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left mr-2"></i>Back to Reviews
    </a>
    <h1>Review Details</h1>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <!-- Review Details -->
    <div class="col-lg-8">
        <!-- Guest Review -->
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Guest Review: {{ $review->guest_name }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Guest Email</p>
                        <p class="font-weight-bold">{{ $review->guest_email }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Review Date</p>
                        <p class="font-weight-bold">{{ $review->review_date->format('F d, Y') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Hotel</p>
                        <p class="font-weight-bold">{{ $review->hotel->name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Booking ID</p>
                        <p class="font-weight-bold">#{{ $review->booking->booking_id ?? $review->booking->id }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Overall Rating</p>
                        <p class="font-weight-bold display-4" style="color: #3B82F6;">{{ $review->overall_rating }}<span style="font-size: 0.5em;">/10</span></p>
                    </div>
                </div>

                <!-- Ratings -->
                <h6 class="font-weight-bold mb-3 border-top pt-3">Detailed Ratings</h6>
                @php
                    $ratings = [
                        'Cleanliness' => (($review->cleanliness_rating ?? 0)/10)*100,
                        'Staff & Service' => (($review->staff_rating ?? 0)/10)*100,
                        'Comfort' => (($review->comfort_rating ?? 0)/10)*100,
                        'Facilities' => (($review->facilities_rating ?? 0)/10)*100,
                        'Value for Money' => (($review->value_for_money_rating ?? 0)/10)*100,
                        'Location' => (($review->location_rating ?? 0)/10)*100,
                    ];
                    $ratingValues = [
                        'Cleanliness' => $review->cleanliness_rating,
                        'Staff & Service' => $review->staff_rating,
                        'Comfort' => $review->comfort_rating,
                        'Facilities' => $review->facilities_rating,
                        'Value for Money' => $review->value_for_money_rating,
                        'Location' => $review->location_rating,
                    ];
                @endphp
                <div class="row">
                    @foreach(['Cleanliness' => 'cleanli', 'Staff & Service' => 'staff', 'Comfort' => 'comfort', 'Facilities' => 'facilities', 'Value for Money' => 'value', 'Location' => 'location'] as $label => $key)
                    <div class="col-md-6 {{ $loop->index >= 4 ? '' : 'mb-3' }}">
                        <p class="mb-1"><strong>{{ $label }}:</strong> {{ $ratingValues[$label] ?? 0 }}/10</p>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" data-width="{{ $ratings[$label] ?? 0 }}"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <script>
                    document.querySelectorAll('.progress-bar[data-width]').forEach(el => {
                        el.style.width = el.dataset.width + '%';
                    });
                </script>

                <!-- Guest Comment -->
                @if($review->comment)
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="font-weight-bold mb-2">Guest Comments</h6>
                    <p>{{ $review->comment }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Hotel Response -->
        @if($review->manager_reply)
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-reply mr-2"></i>Hotel Response
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">
                    <strong>{{ $review->manager->name ?? 'Hotel Manager' }}</strong> - {{ optional($review->reply_date)->format('F d, Y') }}
                </p>
                <div class="p-3 bg-light rounded">
                    <p>{{ $review->manager_reply }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Status Update Form -->
        <div class="dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Review Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.reviews.updateStatus', $review->id) }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="status" class="form-label"><strong>Update Status</strong></label>
                        <select name="status" id="status" class="form-control">
                            <option value="APPROVED" {{ $review->status === 'APPROVED' ? 'selected' : '' }}>Approved</option>
                            <option value="PENDING" {{ $review->status === 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="REJECTED" {{ $review->status === 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <!-- Quick Info -->
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="text-muted mb-1">Current Status</p>
                    @if($review->status === 'APPROVED')
                        <span class="badge bg-success">Approved</span>
                    @elseif($review->status === 'PENDING')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Hotel Response</p>
                    @if($review->manager_reply)
                        <span class="badge bg-success">Replied</span>
                    @else
                        <span class="badge bg-secondary">Not Replied</span>
                    @endif
                </div>

                <hr>

                <a href="mailto:{{ $review->guest_email }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="fas fa-envelope mr-1"></i>Email Guest
                </a>

                <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Delete this review? This cannot be undone.')">
                        <i class="fas fa-trash mr-1"></i>Delete Review
                    </button>
                </form>
            </div>
        </div>

        <!-- Review Guidelines -->
        <div class="alert alert-info small">
            <strong>Admin Guidelines:</strong>
            <ul class="mb-0 mt-2">
                <li>Approve reviews from verified guests</li>
                <li>Reject inappropriate or spam reviews</li>
                <li>Monitor hotel responses for professionalism</li>
                <li>Remove reviews violating community standards</li>
            </ul>
        </div>
    </div>
</div>

<style>
    .dashboard-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
    }

    .card-header {
        border-bottom: 1px solid #e0e0e0;
        padding: 15px;
        background-color: #f8f9fa;
    }

    .card-body {
        padding: 20px;
    }

    .display-4 {
        font-size: 2.5rem;
        margin: 0;
    }
</style>
@endsection
