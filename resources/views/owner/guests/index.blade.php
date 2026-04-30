@extends('owner.layouts.app')

@section('title', 'Guests')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Guest Management</h2>
        <p class="text-gray-600 text-sm">View guest information and booking history</p>
    </div>
@endsection

@section('content')
<!-- Guest Management Container -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" style="animation: slideInUp 0.6s ease-out;">
    <!-- Colorful Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-users" style="font-size: 2rem; opacity: 0.9;"></i>
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0;">Guest Directory</h3>
                <p style="margin: 0.25rem 0 0 0; opacity: 0.9; font-size: 0.9rem;">Total Guests: <strong>{{ $guests->total() }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead style="background: linear-gradient(90deg, #0f7d6f 0%, #0d9d7c 100%); border-bottom: 2px solid #0d6b60;">
                <tr>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-user-circle mr-2" style="color: white; opacity: 0.95;"></i>Guest Name
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-envelope mr-2" style="color: white; opacity: 0.95;"></i>Email
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-phone mr-2" style="color: white; opacity: 0.95;"></i>Mobile
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-calendar-check mr-2" style="color: white; opacity: 0.95;"></i>Total Bookings
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: left; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-clock mr-2" style="color: white; opacity: 0.95;"></i>Last Visit
                    </th>
                    <th style="padding: 1.25rem 1.5rem; text-align: center; font-weight: 700; color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="fas fa-cogs mr-2" style="color: white; opacity: 0.95;"></i>Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($guests as $guest)
                <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.background='linear-gradient(90deg, #f0f9f8 0%, #f5fffe 100%)'; this.style.boxShadow='inset 0 2px 6px rgba(17, 153, 142, 0.08)'" onmouseout="this.style.background='white'; this.style.boxShadow='none'">
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.1rem; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);">
                                {{ strtoupper(substr($guest->guest_name, 0, 1)) }}
                            </div>
                            <div style="font-weight: 600; color: #333; font-size: 0.95rem;">{{ $guest->guest_name }}</div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #666; font-size: 0.9rem;">
                        {{ $guest->email }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="color: #666; font-size: 0.9rem; font-weight: 500;">
                            @if($guest->mobile)
                                <i class="fas fa-mobile-alt mr-1" style="color: #667eea;"></i>{{ $guest->mobile }}
                            @else
                                <span style="color: #999;"><i class="fas fa-ban mr-1"></i>N/A</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.9rem; display: inline-block;">
                            <i class="fas fa-check-circle mr-1"></i>{{ $guest->total_bookings }} {{ $guest->total_bookings == 1 ? 'booking' : 'bookings' }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="color: #666; font-size: 0.9rem;">
                            <i class="fas fa-calendar mr-2" style="color: #11998e;"></i>{{ \Carbon\Carbon::parse($guest->last_visit)->format('M d, Y') }}
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <a href="{{ route('owner.guests.show', $guest->email) }}" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(17, 153, 142, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-eye"></i>View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 3rem 1.5rem; text-align: center;">
                        <div style="color: #999;">
                            <i class="fas fa-inbox" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.5; display: block;"></i>
                            <p style="font-size: 1rem; margin: 0;">No guests found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($guests->hasPages())
    <div style="padding: 1.5rem; border-top: 1px solid #f0f0f0; display: flex; justify-content: center;">
        {{ $guests->links() }}
    </div>
    @endif
</div>

<style>
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
