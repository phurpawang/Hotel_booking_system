@extends('layouts.dashboard-bootstrap')

@section('title', 'Hotel Deregistration - ' . $hotel->name)

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(220, 53, 69, 0.4);
    }
    
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .warning-box {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-left: 5px solid #ffc107;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    
    .danger-box {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-left: 5px solid #dc3545;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    
    .success-box {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 5px solid #28a745;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    
    .info-card {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e0e6ed;
        border-radius: 10px;
        padding: 0.75rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-danger-gradient {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-danger-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-pending {
        background: #ffc107;
        color: #000;
    }
    
    .status-approved {
        background: #28a745;
        color: white;
    }
    
    .status-rejected {
        background: #dc3545;
        color: white;
    }
    
    .status-cancelled {
        background: #6c757d;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Hotel Deregistration
                </h1>
                <p class="mb-0 opacity-90">Request to remove your property from the platform</p>
            </div>
            <a href="{{ route('owner.dashboard') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Existing Request Status -->
    @if($deregistrationRequest)
        @if($deregistrationRequest->status == 'PENDING')
        <div class="warning-box">
            <h4><i class="bi bi-hourglass-split me-2"></i>Pending Deregistration Request</h4>
            <p class="mb-3">You have submitted a deregistration request on {{ $deregistrationRequest->created_at->format('F d, Y') }}.</p>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Reason:</strong> {{ ucwords(str_replace('_', ' ', $deregistrationRequest->reason)) }}</p>
                    <p class="mb-0"><strong>Details:</strong> {{ $deregistrationRequest->reason_details }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <form method="POST" action="{{ route('owner.deregistration.cancel', $deregistrationRequest->id) }}" onsubmit="return confirm('Are you sure you want to cancel this deregistration request?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-x-circle me-2"></i>Cancel Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @elseif($deregistrationRequest->status == 'APPROVED')
        <div class="success-box">
            <h4><i class="bi bi-check-circle me-2"></i>Deregistration Request Approved</h4>
            <p class="mb-2">Your deregistration request has been approved by the administrator.</p>
            <p class="mb-0"><strong>Note:</strong> {{ $deregistrationRequest->admin_notes ?? 'No additional notes.' }}</p>
        </div>
        @endif
    @endif

    <!-- Future Bookings Warning -->
    @if($futureBookingsCount > 0)
    <div class="danger-box">
        <h4><i class="bi bi-calendar-x me-2"></i>Active Future Bookings</h4>
        <p class="mb-0">
            You currently have <strong>{{ $futureBookingsCount }}</strong> confirmed booking(s) with future check-in dates. 
            You must complete or cancel all future bookings before submitting a deregistration request.
        </p>
        <div class="mt-3">
            <a href="{{ route('owner.reservations.index') }}" class="btn btn-primary">
                <i class="bi bi-calendar-check me-2"></i>View Bookings
            </a>
        </div>
    </div>
    @endif

    <!-- Deregistration Information -->
    <div class="card">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Important Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>What Happens</h5>
                        <ul class="mb-0">
                            <li>Your property will be marked as "Pending Closure"</li>
                            <li>No new bookings will be accepted</li>
                            <li>Your listing will be hidden from public view</li>
                            <li>Admin will review your request within 3-5 business days</li>
                            <li>Upon approval, all data will be archived</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Before You Proceed</h5>
                        <ul class="mb-0">
                            <li>Complete all confirmed bookings</li>
                            <li>Cancel future reservations with proper notice</li>
                            <li>Settle outstanding commission payments</li>
                            <li>Download copies of important reports</li>
                            <li>Export guest and booking data if needed</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="bi bi-lightbulb me-2"></i>
                <strong>Note:</strong> Deregistration is a permanent action. Once approved, your property will be removed from the platform. 
                If you're experiencing temporary issues, consider using "Seasonal Closure" instead.
            </div>
        </div>
    </div>

    <!-- Deregistration Request Form -->
    @if(!$deregistrationRequest || $deregistrationRequest->status == 'REJECTED')
    <div class="card">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
            <h4 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Submit Deregistration Request</h4>
        </div>
        <div class="card-body">
            @if($deregistrationRequest && $deregistrationRequest->status == 'REJECTED')
            <div class="alert alert-warning">
                <h5><i class="bi bi-x-circle me-2"></i>Previous Request Rejected</h5>
                <p class="mb-2"><strong>Rejection Date:</strong> {{ $deregistrationRequest->reviewed_at->format('F d, Y') }}</p>
                <p class="mb-0"><strong>Admin Notes:</strong> {{ $deregistrationRequest->admin_notes ?? 'No notes provided.' }}</p>
            </div>
            @endif

            @if($futureBookingsCount == 0 && (!$deregistrationRequest || in_array($deregistrationRequest->status, ['REJECTED', 'CANCELLED'])))
            <form method="POST" action="{{ route('owner.deregistration.store') }}" id="deregistrationForm">
                @csrf

                <div class="mb-4">
                    <label for="reason" class="form-label fw-bold">
                        <i class="bi bi-question-circle me-2"></i>Reason for Deregistration <span class="text-danger">*</span>
                    </label>
                    <select name="reason" id="reason" class="form-select @error('reason') is-invalid @enderror" required>
                        <option value="">-- Select Reason --</option>
                        <option value="BUSINESS_CLOSED">Business Permanently Closed</option>
                        <option value="RENOVATION">Property Under Major Renovation</option>
                        <option value="SEASONAL_CLOSURE">Seasonal/Temporary Closure</option>
                        <option value="SWITCHING_PLATFORM">Switching to Another Platform</option>
                        <option value="OTHER">Other Reason</option>
                    </select>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="reason_details" class="form-label fw-bold">
                        <i class="bi bi-chat-left-text me-2"></i>Additional Details <span class="text-danger">*</span>
                    </label>
                    <textarea name="reason_details" id="reason_details" rows="5" 
                        class="form-control @error('reason_details') is-invalid @enderror" 
                        placeholder="Please provide detailed information about your reason for deregistration..." 
                        maxlength="1000" required>{{ old('reason_details') }}</textarea>
                    <div class="form-text">Maximum 1000 characters</div>
                    @error('reason_details')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="confirmCheckbox" required>
                    <label class="form-check-label fw-bold" for="confirmCheckbox">
                        I understand that this is a permanent action and my property will be removed from the platform after approval.
                    </label>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-danger-gradient" id="submitBtn" disabled>
                        <i class="bi bi-send me-2"></i>Submit Deregistration Request
                    </button>
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-x me-2"></i>Cancel
                    </a>
                </div>
            </form>
            @else
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                You cannot submit a deregistration request at this time. Please complete or cancel all future bookings first.
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Request History -->
    @if($requestHistory && $requestHistory->count() > 0)
    <div class="card">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <h4 class="mb-0"><i class="bi bi-clock-history me-2"></i>Request History</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date Submitted</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Reviewed By</th>
                            <th>Reviewed Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requestHistory as $req)
                        <tr>
                            <td>{{ $req->created_at->format('M d, Y') }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $req->reason)) }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($req->status) }}">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td>{{ $req->reviewer->name ?? 'N/A' }}</td>
                            <td>{{ $req->reviewed_at ? $req->reviewed_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
    // Enable submit button when checkbox is checked
    document.getElementById('confirmCheckbox')?.addEventListener('change', function() {
        document.getElementById('submitBtn').disabled = !this.checked;
    });

    // Confirmation before submitting
    document.getElementById('deregistrationForm')?.addEventListener('submit', function(e) {
        if (!confirm('Are you absolutely sure you want to submit this deregistration request? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
</script>
@endsection
