@extends('owner.layouts.app')

@section('title', 'Review Management')

@section('content')
<!-- Guest Reviews Container -->
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-star" style="font-size: 2.5rem; opacity: 0.95;"></i>
            <div>
                <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Guest Reviews</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage and respond to guest reviews of your hotel</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Overall Rating Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out;" onmouseover="this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Overall Rating</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $stats['average_overall'] ?? '0' }}<span style="font-size: 0.7em;">/10</span></p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-comments mr-1" style="color: #667eea;"></i>{{ $stats['total_reviews'] ?? 0 }} review{{ ($stats['total_reviews'] ?? 0) != 1 ? 's' : '' }}
            </div>
        </div>

        <!-- Total Reviews Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.1s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(16, 185, 129, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Total Reviews</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $stats['total_reviews'] ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-users mr-1" style="color: #10b981;"></i>Guest feedback
            </div>
        </div>

        <!-- Pending Replies Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.2s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(251, 146, 60, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Pending Replies</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $stats['pending_replies'] ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-clock mr-1" style="color: #f97316;"></i>Awaiting response
            </div>
        </div>

        <!-- Avg. Cleanliness Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.3s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(168, 85, 247, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-sparkles"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Avg. Cleanliness</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $stats['average_cleanliness'] ?? '0' }}<span style="font-size: 0.7em;">/10</span></p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-home mr-1" style="color: #a855f7;"></i>Room quality rating
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.6s ease-out;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 1.5rem;">
            <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-list mr-2"></i>Recent Reviews</h3>
        </div>

        <!-- Content -->
        <div style="padding: 2rem;">
            @if($reviews->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Guest</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Rating</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Date</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Booking</th>
                                <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Reply Status</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='white'">
                                <td style="padding: 1rem; color: #333;">
                                    <div style="font-weight: 600;">{{ $review->guest_name }}</div>
                                    <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">{{ $review->guest_email }}</div>
                                </td>
                                <td style="padding: 1rem;">
                                    <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
                                        <i class="fas fa-star mr-1"></i>{{ $review->overall_rating }}/10
                                    </span>
                                </td>
                                <td style="padding: 1rem; color: #666;">
                                    {{ $review->review_date->format('M d, Y') }}
                                </td>
                                <td style="padding: 1rem; color: #666; font-weight: 500;">
                                    #{{ $review->booking->booking_id ?? $review->booking->id }}
                                </td>
                                <td style="padding: 1rem;">
                                    @if($review->manager_reply)
                                        <span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                            <i class="fas fa-check-circle mr-1"></i>Replied
                                        </span>
                                    @else
                                        <span style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <a href="{{ route('owner.reviews.show', $review->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.6rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <i class="fas fa-eye"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                <div style="padding-top: 2rem; border-top: 1px solid #f0f0f0; display: flex; justify-content: center;">
                    {{ $reviews->links() }}
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div style="text-align: center; padding: 3rem 2rem;">
                    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.2;">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 style="font-size: 1.25rem; color: #333; margin-bottom: 0.5rem; font-weight: 700;">No reviews yet</h4>
                    <p style="color: #666; margin: 0; font-size: 0.95rem;">Guests who have completed their stay can leave reviews here</p>
                    <p style="color: #999; margin: 1rem 0 0 0; font-size: 0.9rem;">Once guests submit their reviews, you'll be able to see and respond to them here</p>
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
