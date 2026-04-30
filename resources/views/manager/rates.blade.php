@extends('manager.layouts.app')

@section('title', 'Rates & Availability - ' . $hotel->name)

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
    
    <!-- Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem 2.5rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"><i class="bi bi-cash-coin me-2"></i>Rates & Availability</h2>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">{{ $hotel->name }}</p>
        </div>
    </div>

    <!-- Quick Stats Section -->
    @if($rooms->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center; border-left: 4px solid #667eea; transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">{{ $rooms->sum('quantity') }}</div>
            <div style="color: #666; font-size: 0.9rem;">Total Rooms</div>
        </div>
        <div style="background: white; padding: 1.2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center; border-left: 4px solid #28a745; transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; font-weight: 700; color: #28a745; margin-bottom: 0.5rem;">{{ $rooms->where('status', 'AVAILABLE')->sum('quantity') }}</div>
            <div style="color: #666; font-size: 0.9rem;">Available</div>
        </div>
        <div style="background: white; padding: 1.2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center; border-left: 4px solid #dc3545; transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; font-weight: 700; color: #dc3545; margin-bottom: 0.5rem;">{{ $rooms->where('status', 'OCCUPIED')->sum('quantity') }}</div>
            <div style="color: #666; font-size: 0.9rem;">Occupied</div>
        </div>
        <div style="background: white; padding: 1.2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center; border-left: 4px solid #ffc107; transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; font-weight: 700; color: #ffc107; margin-bottom: 0.5rem;">Nu. {{ number_format($rooms->avg('price_per_night'), 2) }}</div>
            <div style="color: #666; font-size: 0.9rem;">Avg Rate/Night</div>
        </div>
    </div>
    @endif

    <!-- Rooms List with Pricing -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.2rem;">
        @forelse($rooms as $room)
            @for($i = 1; $i <= $room->quantity; $i++)
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; border: 1px solid #f0f0f0; display: flex; flex-direction: column;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.9rem; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-weight: 700; font-size: 1rem;"><i class="bi bi-door-closed-fill me-2"></i>Room {{ $room->room_number + $i - 1 }}</h5>
                <span style="background: rgba(255,255,255,0.3); padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">{{ $room->room_type }}</span>
            </div>
            
            <div style="padding: 1rem; flex-grow: 1; display: flex; flex-direction: column;">
                <!-- Status Badge - More Prominent -->
                <div style="margin-bottom: 0.8rem;">
                    @if(strtoupper($room->status) === 'AVAILABLE')
                        <span style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059e0b 100%); color: white; padding: 0.35rem 0.9rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);">✓ AVAILABLE</span>
                    @elseif(strtoupper($room->status) === 'OCCUPIED')
                        <span style="display: inline-block; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 0.35rem 0.9rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">✗ OCCUPIED</span>
                    @else
                        <span style="display: inline-block; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 0.35rem 0.9rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; box-shadow: 0 2px 6px rgba(245, 158, 11, 0.3);">⚠ MAINTENANCE</span>
                    @endif
                </div>
                
                <!-- Rate & Capacity Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 0.8rem;">
                    <div style="background: #f8fafb; padding: 0.8rem; border-radius: 8px; border-left: 3px solid #667eea;">
                        <div style="color: #999; font-size: 0.75rem; margin-bottom: 0.2rem;">Rate</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: #667eea;">Nu. {{ number_format($room->price_per_night, 0) }}</div>
                        <div style="color: #999; font-size: 0.7rem;">per night</div>
                    </div>
                    
                    <div style="background: #f8fafb; padding: 0.8rem; border-radius: 8px; border-left: 3px solid #10b981;">
                        <div style="color: #999; font-size: 0.75rem; margin-bottom: 0.2rem;">Capacity</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: #10b981;">{{ $room->capacity }}</div>
                        <div style="color: #999; font-size: 0.7rem;">{{ $room->capacity == 1 ? 'Person' : 'People' }}</div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: auto;">
                    <a href="{{ route('manager.rooms.edit', $room->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.6rem 0.8rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 600; font-size: 0.8rem; border: none; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.4rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <i class="bi bi-pencil-fill" style="font-size: 0.8rem;"></i>Edit
                    </a>
                    <a href="{{ route('manager.rooms.show', $room->id) }}" style="background: white; color: #667eea; padding: 0.6rem 0.8rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 600; font-size: 0.8rem; border: 2px solid #667eea; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.4rem;" onmouseover="this.style.background='#667eea'; this.style.color='white'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='white'; this.style.color='#667eea'; this.style.transform='translateY(0)';">
                        <i class="bi bi-eye-fill" style="font-size: 0.8rem;"></i>View
                    </a>
                </div>
            </div>
        </div>
            @endfor
        @empty
        <div style="grid-column: 1 / -1; background: white; padding: 3rem; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 3rem; margin-bottom: 1rem;"><i class="bi bi-door-closed-fill" style="color: #ddd;"></i></div>
            <h5 style="color: #333; margin-bottom: 0.5rem;">No Rooms Found</h5>
            <p style="color: #999; margin-bottom: 1.5rem;">No rooms have been added yet. Create your first room to get started.</p>
            <a href="{{ route('manager.rooms.create') }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="bi bi-plus-circle me-2"></i>Add First Room
            </a>
        </div>
        @endforelse
    </div>

</div>

@endsection
