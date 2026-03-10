<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Bhutan Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .reset-card {
            max-width: 500px;
            margin: 2rem auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .password-strength {
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="reset-card card">
            <div class="card-header bg-success text-white text-center py-4">
                <h3><i class="bi bi-shield-lock"></i> Reset Password</h3>
                <p class="mb-0">Create Your New Password</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('password.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <!-- Hidden Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email (read-only) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $request->email) }}" required readonly>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-lock"></i> New Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password" id="password" 
                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               placeholder="Enter new password" required autocomplete="off">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="password-strength text-muted">
                            <i class="bi bi-info-circle"></i> Password must be at least 8 characters
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-lock-fill"></i> Confirm Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                               placeholder="Confirm new password" required autocomplete="off">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Reset Password
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('hotel.login') }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center text-muted py-3">
                <small>
                    <i class="bi bi-shield-check"></i> Your password will be securely encrypted
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password match validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            
            if (confirm && password !== confirm) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>
