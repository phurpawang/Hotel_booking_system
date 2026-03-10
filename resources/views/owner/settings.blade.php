@extends('layouts.dashboard-bootstrap')

@section('title', 'Property Settings - ' . $hotel->name)

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <h2 class="mb-2"><i class="bi bi-gear-fill me-2"></i>Property Settings</h2>
        <p class="text-muted mb-0">Manage your hotel information and preferences</p>
    </div>

    <!-- Hotel Information Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Hotel Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Hotel Name</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->name }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Hotel ID</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->hotel_id }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Property Type</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->property_type }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Star Rating</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->star_rating }} Star" readonly>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold text-muted">Address</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->address }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Phone</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->phone }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Email</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->email }}" readonly>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold text-muted">Description</label>
                    <textarea class="form-control" rows="4" readonly>{{ $hotel->description }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- License Information Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Tourism License Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">License Number</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->tourism_license_number }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Issuing Authority</label>
                    <input type="text" class="form-control form-control-lg" value="{{ $hotel->issuing_authority }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Issue Date</label>
                    <input type="text" class="form-control form-control-lg" value="{{ \Carbon\Carbon::parse($hotel->license_issue_date)->format('F d, Y') }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Expiry Date</label>
                    <input type="text" class="form-control form-control-lg" value="{{ \Carbon\Carbon::parse($hotel->license_expiry_date)->format('F d, Y') }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-muted">Status</label>
                    <div>
                        <span class="badge bg-success" style="font-size: 1rem; padding: 0.6rem 1.2rem; border-radius: 20px;">
                            <i class="bi bi-check-circle me-1"></i>{{ $hotel->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow-sm">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); color: white;">
            <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Actions</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                To update hotel information, please contact the system administrator or use the hotel deregistration process.
            </div>
            
            <div class="d-grid gap-2 d-md-flex">
                <a href="{{ route('owner.deregistration.index') }}" class="btn btn-outline-danger btn-lg">
                    <i class="bi bi-x-octagon me-2"></i>Request Deregistration
                </a>
                <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2.5rem;
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        margin-bottom: 2rem;
    }

    .dashboard-header h2 {
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .card-header {
        border: none;
        font-weight: 600;
    }

    .form-control {
        border: 2px solid #e0e6ed;
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-outline-danger {
        border: 2px solid #dc3545;
        color: #dc3545;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }
</style>

@endsection
