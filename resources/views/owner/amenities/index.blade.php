@extends('owner.layouts.app')

@section('title', 'Amenities')

@section('content')
<!-- Amenities Management Container -->
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Page Header with Add Button -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; flex: 1; margin-right: 2rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-concierge-bell" style="font-size: 2.5rem; opacity: 0.95;"></i>
                <div>
                    <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Amenities & Facilities</h1>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage your hotel amenities</p>
                </div>
            </div>
        </div>
        <a href="{{ route('owner.amenities.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 1.5rem; border-radius: 12px; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; align-self: center; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out 0.1s both;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.3)'">
            <i class="fas fa-plus"></i>Add New Amenity
        </a>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Total Amenities Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out;" onmouseover="this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Total Amenities</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $amenities->total() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-list mr-1" style="color: #667eea;"></i>All amenities
            </div>
        </div>

        <!-- Active Amenities Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.1s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(34, 197, 94, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Active</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $amenities->where('is_active', true)->count() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-check mr-1" style="color: #10b981;"></i>Enabled
            </div>
        </div>

        <!-- Inactive Amenities Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.2s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(249, 115, 22, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Inactive</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $amenities->where('is_active', false)->count() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-pause mr-1" style="color: #f97316;"></i>Disabled
            </div>
        </div>
    </div>

    <!-- Amenities Grid -->
    @if($amenities->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; animation: slideInUp 0.6s ease-out;">
            @foreach($amenities as $amenity)
            <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; border: 1px solid #f0f0f0;" onmouseover="this.style.boxShadow='0 12px 30px rgba(102, 126, 234, 0.15)'; this.style.transform='translateY(-8px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
                <!-- Card Header with Gradient Background -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 65px; height: 65px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: white;">
                        <i class="{{ $amenity->icon }}"></i>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: white;">{{ $amenity->name }}</h3>
                        @if($amenity->is_active)
                            <span style="background: rgba(34, 197, 94, 0.3); color: white; padding: 0.3rem 0.6rem; border-radius: 4px; font-weight: 600; font-size: 0.75rem; display: inline-block; margin-top: 0.5rem;">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                        @else
                            <span style="background: rgba(107, 114, 128, 0.3); color: white; padding: 0.3rem 0.6rem; border-radius: 4px; font-weight: 600; font-size: 0.75rem; display: inline-block; margin-top: 0.5rem;">
                                <i class="fas fa-pause-circle mr-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div style="padding: 1.5rem;">
                    <p style="margin: 0; color: #666; font-size: 0.95rem; line-height: 1.6;">{{ $amenity->description }}</p>
                </div>

                <!-- Card Footer with Actions -->
                <div style="padding: 1rem 1.5rem; background: #f9fafb; border-top: 1px solid #f0f0f0; display: flex; gap: 0.75rem;">
                    <a href="{{ route('owner.amenities.edit', $amenity->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600; flex: 1; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease; font-size: 0.9rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <i class="fas fa-edit"></i>Edit
                    </a>
                    <form action="{{ route('owner.amenities.destroy', $amenity->id) }}" method="POST" style="display: contents;" onsubmit="return confirm('Are you sure you want to delete this amenity?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease; font-size: 0.9rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-trash"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($amenities->hasPages())
        <div style="padding: 1.5rem; margin-top: 2rem; display: flex; justify-content: center;">
            {{ $amenities->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 4rem 2rem; text-align: center; animation: slideInUp 0.5s ease-out;">
            <div style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.15;">
                <i class="fas fa-concierge-bell"></i>
            </div>
            <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.75rem; font-weight: 700;">No Amenities Added</h3>
            <p style="color: #666; margin: 0 0 1.5rem 0; font-size: 1rem; line-height: 1.6;">Add amenities to showcase your hotel facilities and highlight what makes your property special.</p>
            <a href="{{ route('owner.amenities.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <i class="fas fa-plus mr-1"></i>Add your first amenity
            </a>
        </div>
    @endif
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
