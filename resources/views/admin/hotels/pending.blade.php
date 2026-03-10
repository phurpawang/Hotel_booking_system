@extends('layouts.dashboard')

@section('title', 'Pending Hotel Approvals')

@section('sidebar')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.hotels.pending') }}">
            <i class="bi bi-clock-history"></i> Pending Hotels
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.hotels.all') }}">
            <i class="bi bi-building"></i> All Hotels
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users') }}">
            <i class="bi bi-people"></i> Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.bookings') }}">
            <i class="bi bi-calendar-check"></i> Bookings
        </a>
    </li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clock-history"></i> Pending Hotel Approvals</h2>
        <span class="badge bg-warning fs-5">{{ $hotels->total() }} Pending</span>
    </div>

    @if($hotels->count() > 0)
        @foreach($hotels as $hotel)
            <div class="card mb-3">
                <div class="card-header bg-warning bg-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-building"></i> {{ $hotel->hotel_name }}
                        </h5>
                        <span class="badge bg-secondary">{{ $hotel->hotel_id }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $hotel->email }}</p>
                            <p><strong>Owner:</strong> {{ $hotel->owner->name ?? 'N/A' }}</p>
                            <p><strong>Submitted:</strong> {{ $hotel->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Documents:</strong></p>
                            <div class="mb-2">
                                @if($hotel->license_document)
                                    <a href="{{ asset('storage/' . $hotel->license_document) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-pdf"></i> View License
                                    </a>
                                @endif
                                @if($hotel->ownership_document)
                                    <a href="{{ asset('storage/' . $hotel->ownership_document) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-pdf"></i> View Ownership Doc
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.hotels.approve', $hotel->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Are you sure you want to approve this hotel?')">
                                <i class="bi bi-check-circle"></i> Approve Hotel
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" 
                                data-bs-target="#rejectModal{{ $hotel->id }}">
                            <i class="bi bi-x-circle"></i> Reject Hotel
                        </button>

                        <a href="{{ route('admin.hotels.details', $hotel->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal{{ $hotel->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.hotels.reject', $hotel->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Reject Hotel Registration</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Hotel:</strong> {{ $hotel->hotel_name }}</p>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Reason for Rejection <span class="text-danger">*</span></label>
                                    <textarea name="rejection_reason" class="form-control" rows="4" 
                                              placeholder="Please provide a clear reason for rejection..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i> Reject Hotel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{ $hotels->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                <h4 class="mt-3">No Pending Approvals</h4>
                <p class="text-muted">All hotel registrations have been reviewed.</p>
            </div>
        </div>
    @endif
@endsection
