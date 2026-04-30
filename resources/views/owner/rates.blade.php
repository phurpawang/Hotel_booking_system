@extends('owner.layouts.app')

@section('title', 'Rates & Availability')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
    
    <!-- Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem 2.5rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"><i class="bi bi-cash-coin me-2"></i>Rates & Availability</h2>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage room pricing and availability</p>
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
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
        @forelse($rooms as $room)
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; border: 1px solid #f0f0f0; display: flex; flex-direction: column;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-weight: 700; font-size: 1rem;"><i class="bi bi-door-closed-fill me-2"></i>Room {{ $room->room_number }}</h5>
                <span style="background: rgba(255,255,255,0.3); padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">{{ $room->room_type }}</span>
            </div>
            
            <div style="padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column;">
                <!-- Status Badge -->
                <div style="margin-bottom: 0.75rem;">
                    @if(strtoupper($room->status) === 'AVAILABLE')
                        <span style="display: inline-block; background: #10b981; color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 600; font-size: 0.8rem;">✓ {{ $room->status }}</span>
                    @elseif(strtoupper($room->status) === 'OCCUPIED')
                        <span style="display: inline-block; background: #ef4444; color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 600; font-size: 0.8rem;">✗ {{ $room->status }}</span>
                    @else
                        <span style="display: inline-block; background: #f59e0b; color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 600; font-size: 0.8rem;">⚠ {{ $room->status }}</span>
                    @endif
                </div>
                
                <!-- Rate & Details Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div style="background: #f8fafb; padding: 0.8rem; border-radius: 8px; border-left: 3px solid #667eea;">
                        <div style="color: #999; font-size: 0.75rem; margin-bottom: 0.2rem;">Rate</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: #667eea;">Nu. {{ number_format($room->price_per_night, 2) }}</div>
                        <div style="color: #999; font-size: 0.7rem;">per night</div>
                    </div>
                    
                    <div style="background: #f8fafb; padding: 0.8rem; border-radius: 8px; border-left: 3px solid #10b981;">
                        <div style="color: #999; font-size: 0.75rem; margin-bottom: 0.2rem;">Capacity</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: #10b981;">{{ $room->capacity }}</div>
                        <div style="color: #999; font-size: 0.7rem;">{{ $room->capacity == 1 ? 'Person' : 'People' }}</div>
                    </div>
                </div>

                @if($room->quantity > 1)
                <div style="background: #e0f2fe; border-left: 3px solid #0284c7; padding: 0.6rem 0.8rem; border-radius: 6px; margin-bottom: 0.75rem; font-size: 0.85rem;">
                    <div style="color: #0284c7; font-weight: 600;"><i class="bi bi-stack me-1"></i>{{ $room->quantity }} rooms</div>
                </div>
                @endif
                
                @if($room->description)
                <div style="background: #f3f4f6; padding: 0.75rem; border-radius: 8px; margin-bottom: 0.75rem; flex-grow: 1;">
                    <p style="color: #666; font-size: 0.8rem; margin: 0;">{{ Str::limit($room->description, 80) }}</p>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: auto;">
                    <a href="{{ route('owner.rooms.edit', $room->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.6rem 0.8rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 600; font-size: 0.85rem; border: none; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.4rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <i class="bi bi-pencil-fill"></i>Edit
                    </a>
                    <a href="{{ route('owner.rooms.show', $room->id) }}" style="background: white; color: #667eea; padding: 0.6rem 0.8rem; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 600; font-size: 0.85rem; border: 2px solid #667eea; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.4rem;" onmouseover="this.style.background='#667eea'; this.style.color='white'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='white'; this.style.color='#667eea'; this.style.transform='translateY(0)';">
                        <i class="bi bi-eye-fill"></i>View
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; background: white; padding: 3rem; border-radius: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div style="font-size: 3rem; margin-bottom: 1rem;"><i class="bi bi-door-closed-fill" style="color: #ddd;"></i></div>
            <h5 style="color: #333; margin-bottom: 0.5rem;">No Rooms Found</h5>
            <p style="color: #999; margin-bottom: 1.5rem;">No rooms have been added yet. Create your first room to get started.</p>
            <a href="{{ route('owner.rooms.create') }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="bi bi-plus-circle me-2"></i>Add First Room
            </a>
        </div>
        @endforelse
    </div>

</div>

@endsection
