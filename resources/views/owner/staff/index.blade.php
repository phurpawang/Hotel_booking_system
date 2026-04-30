@extends('owner.layouts.app')

@section('title', 'Staff Management')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Staff Management</h2>
    <p class="text-gray-600 text-sm">Manage your hotel staff members</p>
@endsection

@section('content')
<!-- Staff Management Container -->
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Page Header with Add Button -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; flex: 1; margin-right: 2rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-users" style="font-size: 2.5rem; opacity: 0.95;"></i>
                <div>
                    <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Staff Management</h1>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage your hotel staff members</p>
                </div>
            </div>
        </div>
        <a href="{{ route('owner.staff.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 1.5rem; border-radius: 12px; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; align-self: center; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out 0.1s both;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.3)'">
            <i class="fas fa-plus"></i>Add New Staff
        </a>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Total Staff Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out;" onmouseover="this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Total Staff</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $staff->total() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-layer-group mr-1" style="color: #667eea;"></i>All staff members
            </div>
        </div>

        <!-- Managers Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.1s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(249, 115, 22, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Managers</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $staff->where('role', 'MANAGER')->count() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-shield-alt mr-1" style="color: #f97316;"></i>Management staff
            </div>
        </div>

        <!-- Reception Staff Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.2s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(34, 197, 94, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Reception</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $staff->where('role', 'RECEPTION')->count() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-door-open mr-1" style="color: #10b981;"></i>Front desk staff
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.6s ease-out;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 1.5rem;">
            <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-list mr-2"></i>Staff Members</h3>
        </div>

        <!-- Content -->
        @if($staff->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Name</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Email</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Role</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Mobile</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Joined</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $member)
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.9rem;">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div style="font-weight: 600; color: #333;">{{ $member->name }}</div>
                                </div>
                            </td>
                            <td style="padding: 1rem; color: #666;">
                                <a href="mailto:{{ $member->email }}" style="color: #667eea; text-decoration: none; font-weight: 500;">{{ $member->email }}</a>
                            </td>
                            <td style="padding: 1rem;">
                                @if($member->role === 'MANAGER')
                                    <span style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                        <i class="fas fa-user-tie mr-1"></i>Manager
                                    </span>
                                @else
                                    <span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                        <i class="fas fa-headset mr-1"></i>Reception
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1rem; color: #666;">
                                @if($member->mobile)
                                    <span style="color: #333; font-weight: 500;"><i class="fas fa-phone mr-1" style="color: #11998e;"></i>{{ $member->mobile }}</span>
                                @else
                                    <span style="color: #999; font-style: italic;">N/A</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: center; color: #666; font-size: 0.9rem;">
                                {{ $member->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <a href="{{ route('owner.staff.edit', $member->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 0.75rem; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <i class="fas fa-edit"></i>Edit
                                    </a>
                                    <form action="{{ route('owner.staff.destroy', $member->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.3rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                            <i class="fas fa-trash"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding: 3rem 2rem; text-align: center;">
                                <div style="font-size: 3.5rem; margin-bottom: 1rem; opacity: 0.15;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 style="font-size: 1.25rem; color: #333; margin-bottom: 0.5rem; font-weight: 700;">No staff members found.</h4>
                                <p style="color: #666; margin: 0; font-size: 0.95rem;">Start by adding your first staff member to your hotel team.</p>
                                <a href="{{ route('owner.staff.create') }}" style="color: #667eea; text-decoration: none; font-weight: 600; margin-top: 1rem; display: inline-block;">Add First Staff Member →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($staff->hasPages())
            <div style="padding: 1.5rem; border-top: 1px solid #f0f0f0; display: flex; justify-content: center;">
                {{ $staff->links() }}
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.15;">
                    <i class="fas fa-users"></i>
                </div>
                <h4 style="font-size: 1.25rem; color: #333; margin-bottom: 0.5rem; font-weight: 700;">No staff members found.</h4>
                <p style="color: #666; margin: 0; font-size: 0.95rem;">Start by adding your first staff member to your hotel team.</p>
                <a href="{{ route('owner.staff.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; margin-top: 1.5rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-plus mr-1"></i>Add First Staff Member
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
