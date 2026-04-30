@extends('manager.layouts.app')

@section('title', 'Review Management')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem 2.5rem; border-radius: 20px; margin-bottom: 2.5rem; box-shadow: 0 10px 40px rgba(102, 126, 234, 0.35); animation: slideInDown 0.5s ease-out;">
        <h2 style="margin: 0 0 0.5rem 0; font-weight: 700; font-size: 2.2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-star me-3"></i>Guest Reviews</h2>
        <p style="margin: 0; opacity: 0.95; font-size: 1.05rem;">Manage and respond to guest reviews of your hotel</p>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; animation: slideInUp 0.5s ease-out;">
        <!-- Overall Rating Card -->
        <div style="background: white; border-radius: 15px; padding: 1.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.25)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 70px; height: 70px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-star"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: #667eea; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Overall Rating</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #333; margin-bottom: 0.3rem;">
                        {{ $stats['average_overall'] ?? '0' }}<span style="font-size: 0.5em; color: #999;">/10</span>
                    </div>
                    <small style="color: #999; font-weight: 600;">{{ $stats['total_reviews'] }} {{ $stats['total_reviews'] == 1 ? 'review' : 'reviews' }}</small>
                </div>
            </div>
        </div>

        <!-- Total Reviews Card -->
        <div style="background: white; border-radius: 15px; padding: 1.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(34, 197, 94, 0.25)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 70px; height: 70px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                    <i class="fas fa-comments"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: #10b981; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Total Reviews</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #333;">{{ $stats['total_reviews'] }}</div>
                    <small style="color: #999; font-weight: 600;">All guest reviews</small>
                </div>
            </div>
        </div>

        <!-- Pending Replies Card -->
        <div style="background: white; border-radius: 15px; padding: 1.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(249, 115, 22, 0.25)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 70px; height: 70px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: #f59e0b; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Pending Replies</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #333;">{{ $stats['pending_replies'] }}</div>
                    <small style="color: #999; font-weight: 600;">Awaiting response</small>
                </div>
            </div>
        </div>

        <!-- Avg. Cleanliness Card -->
        <div style="background: white; border-radius: 15px; padding: 1.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(168, 85, 247, 0.25)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); width: 70px; height: 70px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3);">
                    <i class="fas fa-building"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: #a855f7; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Avg. Cleanliness</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #333;">
                        {{ $stats['average_cleanliness'] ?? '0' }}<span style="font-size: 0.5em; color: #999;">/10</span>
                    </div>
                    <small style="color: #999; font-weight: 600;">Cleanliness rating</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews List Section -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.6s ease-out;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border: none;">
            <h5 style="margin: 0; font-weight: 700; font-size: 1.2rem;"><i class="fas fa-list me-2"></i>Reviews List</h5>
        </div>
        
        <div style="padding: 2rem;">
            @if($reviews->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e5e7eb; background: #f9f9f9;">
                                <th style="padding: 1rem; text-align: left; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Guest</th>
                                <th style="padding: 1rem; text-align: left; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Rating</th>
                                <th style="padding: 1rem; text-align: left; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Date</th>
                                <th style="padding: 1rem; text-align: left; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Booking ID</th>
                                <th style="padding: 1rem; text-align: left; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Status</th>
                                <th style="padding: 1rem; text-align: left; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr style="border-bottom: 1px solid #e5e7eb; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='white'">
                                <td style="padding: 1rem; color: #333;">
                                    <div style="font-weight: 700;">{{ $review->guest_name }}</div>
                                    <small style="color: #999;">{{ $review->guest_email }}</small>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem;">{{ $review->overall_rating }}/10</span>
                                        <small style="color: #999;">Overall</small>
                                    </div>
                                </td>
                                <td style="padding: 1rem; color: #666; font-size: 0.9rem;">
                                    {{ $review->review_date->format('M d, Y') }}
                                </td>
                                <td style="padding: 1rem; color: #666; font-weight: 600;">#{{ $review->booking->booking_id ?? $review->booking->id }}</td>
                                <td style="padding: 1rem;">
                                    @if($review->manager_reply)
                                        <span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <i class="fas fa-check"></i>Replied
                                        </span>
                                    @else
                                        <span style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                                            <i class="fas fa-clock"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 1rem;">
                                    <a href="{{ route('manager.reviews.show', $review->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <i class="fas fa-eye"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="display: flex; justify-content: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    {{ $reviews->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.3;">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 style="color: #333; margin-bottom: 0.5rem; font-weight: 700; font-size: 1.3rem;">No reviews yet</h5>
                    <p style="color: #999; margin-bottom: 0; font-size: 1rem;">Guests who have completed their stay can leave reviews here</p>
                </div>
            @endif
        </div>
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
