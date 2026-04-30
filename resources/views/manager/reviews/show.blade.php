@extends('manager.layouts.app')

@section('title', 'Review Details')

@section('content')
<div class="dashboard-content">
    <div class="content-header">
        <a href="{{ route('manager.reviews.index') }}" class="btn btn-secondary mb-3">
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
        <!-- Review Content -->
        <div class="col-lg-8">
            <!-- Guest Review -->
            <div class="dashboard-card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $review->guest_name }}'s Review</h5>
                        <small class="text-muted">{{ $review->review_date->format('F d, Y') }}</small>
                    </div>
                    <div class="badge bg-primary text-white" style="font-size: 18px; padding: 10px 15px;">
                        {{ $review->overall_rating }}<span style="font-size: 12px;">/10</span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Rating Breakdown -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold mb-3">Detailed Ratings</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><strong>Cleanliness:</strong></p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" data-rating="{{ ($review->cleanliness_rating/10)*100 }}">
                                        {{ $review->cleanliness_rating }}/10
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><strong>Staff & Service:</strong></p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" data-rating="{{ ($review->staff_rating/10)*100 }}">
                                        {{ $review->staff_rating }}/10
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><strong>Comfort:</strong></p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" data-rating="{{ ($review->comfort_rating/10)*100 }}">
                                        {{ $review->comfort_rating }}/10
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="mb-1"><strong>Facilities:</strong></p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" data-rating="{{ ($review->facilities_rating/10)*100 }}">
                                        {{ $review->facilities_rating }}/10
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Value for Money:</strong></p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" data-rating="{{ ($review->value_for_money_rating/10)*100 }}">
                                        {{ $review->value_for_money_rating }}/10
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Location:</strong></p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" data-rating="{{ ($review->location_rating/10)*100 }}">
                                        {{ $review->location_rating }}/10
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Comment -->
                    @if($review->comment)
                    <div class="mb-4">
                        <h6 class="font-weight-bold mb-2">Guest Comments</h6>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Booking Info -->
                    <div class="border-top pt-3">
                        <p class="mb-1"><strong>Booking ID:</strong> #{{ $review->booking->booking_id ?? $review->booking->id }}</p>
                        <p class="mb-1"><strong>Guest Email:</strong> <a href="mailto:{{ $review->guest_email }}">{{ $review->guest_email }}</a></p>
                        <p class="mb-0"><strong>Stay Dates:</strong> {{ $review->booking->check_in_date->format('M d, Y') }} - {{ $review->booking->check_out_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Hotel Response Form -->
            <div class="dashboard-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-reply mr-2"></i>
                        {{ $review->manager_reply ? 'Edit Your Response' : 'Write a Response' }}
                    </h5>
                </div>

                <div class="card-body">
                    @if($review->manager_reply)
                    <div class="alert alert-info mb-3">
                        <strong>Your previous response ({{ optional($review->reply_date)->format('M d, Y') }}):</strong>
                        <p class="mb-0 mt-2">{{ $review->manager_reply }}</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('manager.reviews.reply', $review->id) }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="manager_reply" class="form-label"><strong>Your Response *</strong></label>
                            <textarea name="manager_reply" id="manager_reply" class="form-control @error('manager_reply') is-invalid @enderror" 
                                rows="5" placeholder="Thank the guest for their feedback and address any concerns..." required>{{ old('manager_reply', $review->manager_reply) }}</textarea>
                            <small class="form-text text-muted">Provide a thoughtful response to the guest's feedback</small>
                            @error('manager_reply')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane mr-2"></i>{{ $review->manager_reply ? 'Update Response' : 'Send Response' }}
                            </button>
                            @if($review->manager_reply)
                            <form method="POST" action="{{ route('manager.reviews.destroy', $review->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this review?')">
                                    <i class="fas fa-trash mr-2"></i>Delete Review
                                </button>
                            </form>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-muted mb-1">Hotel</p>
                        <p class="font-weight-bold">{{ $review->hotel->name }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted mb-1">Guest</p>
                        <p class="font-weight-bold">{{ $review->guest_name }}</p>
                        <a href="mailto:{{ $review->guest_email }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-envelope mr-1"></i>Send Email to Guest
                        </a>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted mb-1">Review Status</p>
                        @if($review->manager_reply)
                            <span class="badge bg-success">
                                <i class="fas fa-check mr-1"></i>Response Sent
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock mr-1"></i>Pending Response
                            </span>
                        @endif
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-lightbulb mr-2"></i>
                        <strong>Tip:</strong> Always respond to reviews professionally and politely. This helps build trust with potential guests.
                    </div>
                </div>
            </div>
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
    }

    .card-body {
        padding: 20px;
    }
</style>

<script>
    document.querySelectorAll('.progress-bar[data-rating]').forEach(function(bar) {
        const rating = bar.getAttribute('data-rating');
        bar.style.width = rating + '%';
    });
</script>
@endsection
