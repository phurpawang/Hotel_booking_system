@extends('admin.layout')

@section('title', 'Admin Settings')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
    }
    .header-section h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .header-section i {
        font-size: 40px;
    }
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideDown 0.3s ease;
    }
    .alert-success {
        background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, white 100%);
        border: 2px solid #11998e;
        color: #11998e;
    }
    .alert-error {
        background: linear-gradient(135deg, rgba(235, 51, 73, 0.1) 0%, white 100%);
        border: 2px solid #eb3349;
        color: #eb3349;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .dashboard-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 25px;
    }
    .dashboard-card .card-title {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        margin: 0;
        padding: 18px 20px;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .form-group .required {
        color: #eb3349;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
    }
    .form-control:focus {
        border-color: #4facfe !important;
        box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1) !important;
        outline: none;
    }
    .form-control.is-invalid {
        border-color: #eb3349 !important;
    }
    .form-text {
        display: block;
        margin-top: 5px;
        color: #6c757d;
        font-size: 0.875rem;
    }
    .error-message {
        display: block;
        color: #eb3349;
        font-size: 13px;
        margin-top: 5px;
        font-weight: 500;
    }
    .btn-primary {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: white !important;
        padding: 12px 25px !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4) !important;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    .info-item {
        background: linear-gradient(135deg, rgba(79, 172, 254, 0.05) 0%, white 100%);
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #4facfe;
    }
    .info-item strong {
        display: block;
        color: #4facfe;
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-item span {
        color: #333;
        font-size: 15px;
        font-weight: 500;
    }
</style>

<div class="header-section">
    <h1><i class="fas fa-cog"></i>Admin Settings</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle" style="font-size: 20px;"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i> {{ session('error') }}
    </div>
@endif

<div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- Update Profile -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-user-edit"></i> Update Profile</h3>
        <div class="card-body" style="padding: 25px;">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="profile">
                
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control @error('username') is-invalid @enderror" 
                           value="{{ old('username', $admin->username) }}" 
                           required>
                    @error('username')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $admin->email) }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-lock"></i> Change Password</h3>
        <div class="card-body" style="padding: 25px;">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="password">
                
                <div class="form-group">
                    <label for="current_password">Current Password <span class="required">*</span></label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="form-control @error('current_password') is-invalid @enderror" 
                           required>
                    @error('current_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password <span class="required">*</span></label>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           class="form-control @error('new_password') is-invalid @enderror" 
                           required 
                           minlength="8">
                    <small class="form-text">Minimum 8 characters</small>
                    @error('new_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password <span class="required">*</span></label>
                    <input type="password" 
                           id="new_password_confirmation" 
                           name="new_password_confirmation" 
                           class="form-control" 
                           required 
                           minlength="8">
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Account Information -->
<div class="dashboard-card">
    <h3 class="card-title"><i class="fas fa-info-circle"></i> Account Information</h3>
    <div class="info-grid">
        <div class="info-item">
            <strong>Admin ID:</strong>
            <span>#{{ $admin->id }}</span>
        </div>
        <div class="info-item">
            <strong>Username:</strong>
            <span>{{ $admin->username }}</span>
        </div>
        <div class="info-item">
            <strong>Email:</strong>
            <span>{{ $admin->email ?? 'Not set' }}</span>
        </div>
        <div class="info-item">
            <strong>Account Created:</strong>
            <span>{{ \Carbon\Carbon::parse($admin->created_at)->format('M d, Y h:i A') }}</span>
        </div>
        <div class="info-item">
            <strong>Last Updated:</strong>
            <span>{{ \Carbon\Carbon::parse($admin->updated_at)->format('M d, Y h:i A') }}</span>
        </div>
    </div>
</div>
@endsection
