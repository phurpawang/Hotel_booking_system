<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #2563EB 0%, #7C3AED 100%);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);
            color: white;
        }
        
        .profile-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }
        
        .profile-header .subtitle {
            margin-top: 8px;
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #E2E8F0;
            padding: 20px 24px;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-header-custom h5 {
            margin: 0;
            font-weight: 700;
            color: #1E293B;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }
        
        .card-header-custom i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2563EB 0%, #7C3AED 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 12px;
        }
        
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            height: 44px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            color: #1E293B;
        }
        
        .form-control:hover, .form-select:hover {
            border-color: #CBD5E1;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }
        
        .form-control:disabled {
            background-color: #F1F5F9;
            cursor: not-allowed;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #10B981 0%, #34D399 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
        }
        
        .btn-secondary {
            background: #64748B;
            border: none;
            padding: 12px 32px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #475569;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(100, 116, 139, 0.3);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        .alert-success {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .alert-danger {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .info-badge {
            display: inline-block;
            background: #EFF6FF;
            color: #1E40AF;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .required-star {
            color: #EF4444;
            margin-left: 2px;
        }
        
        .invalid-feedback {
            display: block;
            margin-top: 6px;
            font-size: 0.875rem;
            color: #DC2626;
        }
        
        .text-muted {
            color: #64748B !important;
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .profile-header {
                padding: 20px;
            }
            
            .profile-header h1 {
                font-size: 1.5rem;
            }
            
            .btn-save, .btn-secondary {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-person-circle me-3"></i>My Profile</h1>
                    <p class="subtitle mb-0">Manage your personal information and account settings</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Profile Information Card -->
        <div class="card profile-card">
            <div class="card-header-custom">
                <h5>
                    <i class="bi bi-person-badge"></i>
                    Personal Information
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-4">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Full Name<span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required
                                   placeholder="Enter your full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                Email Address<span class="required-star">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required
                                   placeholder="your.email@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($user->email_verified_at === null)
                                <small class="text-warning d-block mt-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Your email address is unverified.
                                </small>
                            @endif
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label for="mobile" class="form-label">
                                Phone Number<span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('mobile') is-invalid @enderror" 
                                   id="mobile" 
                                   name="mobile" 
                                   value="{{ old('mobile', $user->mobile) }}" 
                                   required
                                   placeholder="Enter your phone number">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hotel Name (Read-only) -->
                        <div class="col-md-6">
                            <label for="hotel_name" class="form-label">
                                Hotel Name
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="hotel_name" 
                                   value="{{ $user->hotel->name ?? 'Not assigned' }}" 
                                   disabled>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Contact administrator to change hotel assignment
                            </small>
                        </div>

                        <!-- Role (Read-only) -->
                        <div class="col-md-6">
                            <label for="role" class="form-label">
                                Role
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="role" 
                                   value="{{ ucfirst(strtolower($user->role)) }}" 
                                   disabled>
                        </div>

                        <!-- Account Status -->
                        <div class="col-md-6">
                            <label class="form-label">Account Status</label>
                            <div>
                                <span class="info-badge">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card profile-card">
            <div class="card-header-custom">
                <h5>
                    <i class="bi bi-shield-lock"></i>
                    Change Password
                </h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">
                    Ensure your account is using a long, random password to stay secure.
                </p>

                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Current Password -->
                        <div class="col-12">
                            <label for="current_password" class="form-label">
                                Current Password<span class="required-star">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   placeholder="Enter your current password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                New Password<span class="required-star">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Enter new password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Minimum 8 characters
                            </small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">
                                Confirm New Password<span class="required-star">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Information Card -->
        <div class="card profile-card">
            <div class="card-header-custom">
                <h5>
                    <i class="bi bi-info-circle"></i>
                    Account Information
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Member Since</p>
                        <h6 class="mb-0">{{ $user->created_at->format('F d, Y') }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Last Updated</p>
                        <h6 class="mb-0">{{ $user->updated_at->format('F d, Y') }}</h6>
                    </div>
                    @if($user->created_by)
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Account Created By</p>
                        <h6 class="mb-0">Administrator</h6>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
