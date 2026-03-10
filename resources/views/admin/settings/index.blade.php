@extends('admin.layout')

@section('title', 'Admin Settings')

@section('content')
<div class="dashboard-header">
    <h1>Admin Settings</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="dashboard-grid">
    <!-- Update Profile -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-user-edit"></i> Update Profile</h3>
        <div class="card-body">
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
        <div class="card-body">
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
    <div class="card-body">
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
</div>

<style>
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
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
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

.form-text {
    display: block;
    margin-top: 5px;
    color: #6c757d;
    font-size: 0.875rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.is-invalid {
    border-color: #dc3545 !important;
}
</style>
@endsection
