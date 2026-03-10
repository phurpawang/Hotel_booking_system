<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Registration - Bhutan Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .registration-card {
            max-width: 600px;
            margin: 2rem auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
        }
        .strength-weak { background-color: #dc3545; width: 33%; }
        .strength-medium { background-color: #ffc107; width: 66%; }
        .strength-strong { background-color: #28a745; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="registration-card card">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3><i class="bi bi-building"></i> Hotel Registration</h3>
                <p class="mb-0">Register your hotel with Bhutan Hotel Booking System</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('hotel.register.submit') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                    @csrf

                    <!-- Hotel Information -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-building"></i> Hotel Information</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hotel Name <span class="text-danger">*</span></label>
                        <input type="text" name="hotel_name" class="form-control @error('hotel_name') is-invalid @enderror" 
                               value="{{ old('hotel_name') }}" required>
                        @error('hotel_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Owner Information -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4"><i class="bi bi-person-badge"></i> Owner Information</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Owner Name <span class="text-danger">*</span></label>
                        <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" 
                               value="{{ old('owner_name') }}" required>
                        @error('owner_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Owner Email <span class="text-danger">*</span></label>
                        <input type="email" name="owner_email" class="form-control @error('owner_email') is-invalid @enderror" 
                               value="{{ old('owner_email') }}" required>
                        <small class="text-muted">This email will be used for login</small>
                        @error('owner_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" required>
                        <div class="password-strength" id="passwordStrength"></div>
                        <small class="text-muted">Minimum 8 characters</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Documents -->
                    <h5 class="border-bottom pb-2 mb-3 mt-4"><i class="bi bi-file-earmark-text"></i> Documents</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tourism License Document <span class="text-danger">*</span></label>
                        <input type="file" name="license_document" accept=".pdf,.jpg,.jpeg,.png"
                               class="form-control @error('license_document') is-invalid @enderror" required>
                        <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 2MB)</small>
                        @error('license_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ownership Document <span class="text-danger">*</span></label>
                        <input type="file" name="ownership_document" accept=".pdf,.jpg,.jpeg,.png"
                               class="form-control @error('ownership_document') is-invalid @enderror" required>
                        <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 2MB)</small>
                        @error('ownership_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Important:</strong> Your hotel registration will be reviewed by admin. 
                        You will be able to login only after admin approval.
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Submit Registration
                        </button>
                        <a href="{{ route('hotel.login') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthBar.className = 'password-strength';
            } else if (password.length < 6) {
                strengthBar.className = 'password-strength strength-weak';
            } else if (password.length < 10) {
                strengthBar.className = 'password-strength strength-medium';
            } else {
                strengthBar.className = 'password-strength strength-strong';
            }
        });
    </script>
</body>
</html>
