<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Registration Status - Bhutan Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .status-card {
            max-width: 500px;
            margin: 2rem auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .status-badge {
            font-size: 1.2rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-card card">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3><i class="bi bi-search"></i> Check Registration Status</h3>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(isset($statusChecked) && $statusChecked)
                    <!-- Status Display -->
                    <div class="text-center mb-4">
                        <h5 class="mb-3">{{ $hotel->hotel_name }}</h5>
                        <div class="mb-3">
                            <strong>Hotel ID:</strong> 
                            <span class="badge bg-secondary">{{ $hotel->hotel_id }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Status:</strong><br>
                            @if($hotel->status === 'pending')
                                <span class="badge bg-warning status-badge">
                                    <i class="bi bi-clock-history"></i> PENDING APPROVAL
                                </span>
                                <p class="text-muted mt-2">Your registration is awaiting admin review.</p>
                            @elseif($hotel->status === 'approved')
                                <span class="badge bg-success status-badge">
                                    <i class="bi bi-check-circle"></i> APPROVED
                                </span>
                                <p class="text-success mt-2">Your hotel has been approved! You can now login.</p>
                            @elseif($hotel->status === 'rejected')
                                <span class="badge bg-danger status-badge">
                                    <i class="bi bi-x-circle"></i> REJECTED
                                </span>
                                @if($hotel->rejection_reason)
                                    <div class="alert alert-danger mt-3 text-start">
                                        <strong>Reason:</strong><br>
                                        {{ $hotel->rejection_reason }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        @if($hotel->status === 'approved')
                            <a href="{{ route('hotel.login') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login Now
                            </a>
                        @else
                            <button type="button" class="btn btn-secondary" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh Status
                            </button>
                        @endif
                    </div>

                    <hr>

                    <div class="text-center">
                        <a href="{{ route('hotel.check-status') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Check Another Hotel
                        </a>
                    </div>
                @else
                    <!-- Status Check Form -->
                    <form action="{{ route('hotel.check-status.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-building"></i> Hotel ID <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="hotel_id" class="form-control @error('hotel_id') is-invalid @enderror" 
                                   value="{{ old('hotel_id') }}" placeholder="e.g., HTL001" required>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" placeholder="your@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-search"></i> Check Status
                            </button>
                            <a href="{{ route('hotel.login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
