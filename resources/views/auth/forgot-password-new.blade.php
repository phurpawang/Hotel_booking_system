<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Bhutan Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .forgot-card {
            max-width: 500px;
            margin: 2rem auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="forgot-card card">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3><i class="bi bi-key"></i> Forgot Password</h3>
                <p class="mb-0">Bhutan Hotel Booking System</p>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i> 
                    Enter your registered email address and we'll send you a password reset link.
                </div>

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-envelope"></i> Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Enter the email you used during registration</small>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send"></i> Send Reset Link
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
                    <i class="bi bi-shield-check"></i> Secure password reset via email
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
