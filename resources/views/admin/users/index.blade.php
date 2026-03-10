@extends('admin.layout')

@section('title', 'Users Management')

@section('content')
<div class="dashboard-header">
    <h1>Users Management</h1>
</div>

<!-- Filters -->
<div class="dashboard-card" style="margin-bottom: 20px;">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET" class="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" class="form-control">
                </div>
                <div class="filter-item">
                    <select name="role" class="form-control">
                        <option value="">All Roles</option>
                        <option value="guest" {{ request('role') == 'guest' ? 'selected' : '' }}>Guest</option>
                        <option value="hotel_owner" {{ request('role') == 'hotel_owner' ? 'selected' : '' }}>Hotel Owner</option>
                        <option value="hotel_staff" {{ request('role') == 'hotel_staff' ? 'selected' : '' }}>Hotel Staff</option>
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-reset"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #007bff;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-details">
            <h3>{{ $users->total() }}</h3>
            <p>Total Users</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #28a745;">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-details">
            <h3>{{ \App\Models\User::where('status', 'active')->count() }}</h3>
            <p>Active Users</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #dc3545;">
            <i class="fas fa-user-times"></i>
        </div>
        <div class="stat-details">
            <h3>{{ \App\Models\User::where('status', 'suspended')->count() }}</h3>
            <p>Suspended Users</p>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="dashboard-card">
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'guest')
                                        <span class="badge badge-info">Guest</span>
                                    @elseif($user->role == 'hotel_owner')
                                        <span class="badge badge-primary">Hotel Owner</span>
                                    @elseif($user->role == 'hotel_staff')
                                        <span class="badge badge-secondary">Hotel Staff</span>
                                    @else
                                        <span class="badge badge-light">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($user->status == 'active')
                                        <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-warning" title="Suspend">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-success" title="Activate">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" data-confirm="Are you sure you want to delete this user? This action cannot be undone.">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        @else
            <p class="no-data">No users found</p>
        @endif
    </div>
</div>
@endsection
