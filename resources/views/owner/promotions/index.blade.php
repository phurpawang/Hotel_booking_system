@extends('owner.layouts.app')

@section('title', 'Promotions')

@section('content')
<!-- Promotions Management Container -->
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Page Header with Add Button -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; flex: 1; margin-right: 2rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-tags" style="font-size: 2.5rem; opacity: 0.95;"></i>
                <div>
                    <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Promotions & Discounts</h1>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage your promotional offers</p>
                </div>
            </div>
        </div>
        <a href="{{ route('owner.promotions.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 1.5rem; border-radius: 12px; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; align-self: center; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out 0.1s both;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.3)'">
            <i class="fas fa-plus"></i>Add New Promotion
        </a>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Total Promotions Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out;" onmouseover="this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Total Promotions</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $promotions->total() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-list mr-1" style="color: #667eea;"></i>All promotions
            </div>
        </div>

        <!-- Active Promotions Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.1s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(34, 197, 94, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Active</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $promotions->where('start_date', '<=', \Carbon\Carbon::today())->where('end_date', '>=', \Carbon\Carbon::today())->count() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-rocket mr-1" style="color: #10b981;"></i>Running now
            </div>
        </div>

        <!-- Inactive Promotions Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.2s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(249, 115, 22, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Inactive</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $promotions->total() - ($promotions->where('start_date', '<=', \Carbon\Carbon::today())->where('end_date', '>=', \Carbon\Carbon::today())->count() ?? 0) ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-pause mr-1" style="color: #f97316;"></i>Not running
            </div>
        </div>
    </div>

    <!-- Promotions List -->
    @if($promotions->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 1.5rem; animation: slideInUp 0.6s ease-out;">
            @foreach($promotions as $promotion)
            <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 12px 30px rgba(102, 126, 234, 0.15)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
                <!-- Card Header -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; color: white; display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1;">
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700;">{{ $promotion->title }}</h3>
                        <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">{{ $promotion->description }}</p>
                    </div>
                    <div style="margin-left: 2rem; text-align: right;">
                        @if($promotion->isActive())
                            <span style="background: rgba(34, 197, 94, 0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.9rem; display: inline-block;">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                        @else
                            <span style="background: rgba(107, 114, 128, 0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; font-size: 0.9rem; display: inline-block;">
                                <i class="fas fa-pause-circle mr-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div style="padding: 2rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                        <!-- Discount Info -->
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem; font-weight: 700; text-align: center;">
                                {{ $promotion->discount_type == 'percentage' ? $promotion->discount_value . '%' : 'Nu.' . number_format($promotion->discount_value, 0) }}
                            </div>
                            <div>
                                <p style="margin: 0; color: #666; font-size: 0.9rem;">Discount</p>
                                <p style="margin: 0.25rem 0 0 0; font-weight: 700; color: #333; font-size: 1rem;">
                                    {{ $promotion->discount_type == 'percentage' ? $promotion->discount_value . '% Off' : 'Nu. ' . number_format($promotion->discount_value, 2) . ' off' }}
                                </p>
                            </div>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <p style="margin: 0; color: #666; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Start Date</p>
                            <p style="margin: 0.5rem 0 0 0; font-weight: 700; color: #333; font-size: 1rem;">{{ \Carbon\Carbon::parse($promotion->start_date)->format('M d, Y') }}</p>
                        </div>

                        <!-- End Date -->
                        <div>
                            <p style="margin: 0; color: #666; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">End Date</p>
                            <p style="margin: 0.5rem 0 0 0; font-weight: 700; color: #333; font-size: 1rem;">{{ \Carbon\Carbon::parse($promotion->end_date)->format('M d, Y') }}</p>
                        </div>

                        <!-- Room Types -->
                        <div>
                            <p style="margin: 0; color: #666; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Applies To</p>
                            <p style="margin: 0.5rem 0 0 0; font-weight: 700; color: #333; font-size: 1rem;">
                                @if($promotion->room_type)
                                    {{ $promotion->room_type }} Rooms
                                @else
                                    <span style="color: #10b981; font-weight: 700;">All Room Types</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Actions -->
                <div style="padding: 1.25rem 2rem; background: #f9fafb; border-top: 1px solid #f0f0f0; display: flex; gap: 0.75rem; justify-content: flex-end;">
                    <a href="{{ route('owner.promotions.edit', $promotion->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.6rem 1.2rem; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; font-size: 0.9rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <i class="fas fa-edit"></i>Edit
                    </a>
                    <form action="{{ route('owner.promotions.destroy', $promotion->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this promotion?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.6rem 1.2rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; font-size: 0.9rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-trash"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($promotions->hasPages())
        <div style="padding: 1.5rem; margin-top: 2rem; display: flex; justify-content: center;">
            {{ $promotions->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 4rem 2rem; text-align: center; animation: slideInUp 0.5s ease-out;">
            <div style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.15;">
                <i class="fas fa-tags"></i>
            </div>
            <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.75rem; font-weight: 700;">No Promotions Created</h3>
            <p style="color: #666; margin: 0 0 1.5rem 0; font-size: 1rem; line-height: 1.6;">Create promotional offers to attract more guests and increase your bookings.</p>
            <a href="{{ route('owner.promotions.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <i class="fas fa-plus mr-1"></i>Create your first promotion
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
