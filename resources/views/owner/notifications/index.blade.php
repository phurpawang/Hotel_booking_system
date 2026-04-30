@extends('owner.layouts.app')

@section('title', 'Notifications')

@section('content')
<!-- Notifications Container -->
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Page Header with Mark All as Read Button -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; flex: 1; margin-right: 2rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-bell" style="font-size: 2.5rem; opacity: 0.95;"></i>
                <div>
                    <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Notifications</h1>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Stay updated with system notifications</p>
                </div>
            </div>
        </div>
        @if($unreadCount > 0)
        <form action="{{ route('owner.notifications.readAll') }}" method="POST" style="align-self: center;">
            @csrf
            <button type="submit" class="hover-button-green" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);">
                <i class="fas fa-check-double"></i>Mark all as read
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    @if($notifications->count() > 0 || $unreadCount > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Total Notifications Card -->
        <div class="hover-card" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out; --shadow-color: rgba(102, 126, 234, 0.2);">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Total Notifications</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $notifications->total() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-list mr-1" style="color: #667eea;"></i>All notifications
            </div>
        </div>

        <!-- Unread Notifications Card -->
        <div class="hover-card" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.1s both; --shadow-color: rgba(249, 115, 22, 0.2);">
            <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Unread</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $unreadCount ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-inbox mr-1" style="color: #f97316;"></i>Needs attention
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="hover-card" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.2s both; --shadow-color: rgba(59, 130, 246, 0.2);">
            <div style="background: linear-gradient(135deg, #3B82F6 0%, #1e40af 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Booking Alerts</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $notifications->where('type', 'new_booking')->count() ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-check-circle mr-1" style="color: #3B82F6;"></i>New bookings
            </div>
        </div>
    </div>
    @endif

    <!-- Notifications List -->
    @if($notifications->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 1rem; animation: slideInUp 0.6s ease-out;">
            @foreach($notifications as $notification)
                @if($notification->type === 'new_booking')
                <div class="hover-notification {{ !$notification->is_read ? 'notification-unread' : '' }}" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease;">
                    <div style="background: linear-gradient(135deg, #3B82F6 0%, #1e40af 100%); color: white; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700;">{{ $notification->title }}</h4>
                                <p style="margin: 0.25rem 0 0 0; opacity: 0.85; font-size: 0.85rem;"><i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if(!$notification->is_read)
                        <span style="background: rgba(255,255,255,0.3); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase;">
                            New
                        </span>
                        @endif
                    </div>
                    <div style="padding: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <p style="margin: 0; color: #666; line-height: 1.6;">{{ $notification->message }}</p>
                    </div>
                    <div style="padding: 1rem 1.5rem; background: #f9fafb; display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #667eea; font-weight: 600; font-size: 0.9rem;">
                            <i class="fas fa-tag mr-1"></i>New Booking
                        </span>
                        @if(!$notification->is_read)
                        <form action="{{ route('owner.notifications.read', $notification->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="hover-button-blue" style="background: linear-gradient(135deg, #3B82F6 0%, #1e40af 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.85rem; transition: all 0.3s ease;">
                                <i class="fas fa-check mr-1"></i>Mark as read
                            </button>
                        </form>
                        @else
                        <span style="color: #10b981; font-weight: 600; font-size: 0.9rem;">
                            <i class="fas fa-check-circle mr-1"></i>Read
                        </span>
                        @endif
                    </div>
                </div>
                @else
                <div class="hover-notification {{ !$notification->is_read ? 'notification-unread' : '' }}" style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease;">
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700;">{{ $notification->title }}</h4>
                                <p style="margin: 0.25rem 0 0 0; opacity: 0.85; font-size: 0.85rem;"><i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if(!$notification->is_read)
                        <span style="background: rgba(255,255,255,0.3); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase;">
                            New
                        </span>
                        @endif
                    </div>
                    <div style="padding: 1.5rem; border-bottom: 1px solid #f0f0f0;">
                        <p style="margin: 0; color: #666; line-height: 1.6;">{{ $notification->message }}</p>
                    </div>
                    <div style="padding: 1rem 1.5rem; background: #f9fafb; display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #667eea; font-weight: 600; font-size: 0.9rem;">
                            <i class="fas fa-tag mr-1"></i>New Review
                        </span>
                        @if(!$notification->is_read)
                        <form action="{{ route('owner.notifications.read', $notification->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="hover-button-blue" style="background: linear-gradient(135deg, #3B82F6 0%, #1e40af 100%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.85rem; transition: all 0.3s ease;">
                                <i class="fas fa-check mr-1"></i>Mark as read
                            </button>
                        </form>
                        @else
                        <span style="color: #10b981; font-weight: 600; font-size: 0.9rem;">
                            <i class="fas fa-check-circle mr-1"></i>Read
                        </span>
                        @endif
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div style="padding: 1.5rem; margin-top: 2rem; display: flex; justify-content: center;">
            {{ $notifications->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 4rem 2rem; text-align: center; animation: slideInUp 0.5s ease-out;">
            <div style="font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.15;">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.75rem; font-weight: 700;">No Notifications</h3>
            <p style="color: #666; margin: 0; font-size: 1rem; line-height: 1.6;">You're all caught up! Notifications will appear here when you receive new bookings, reviews, or system updates.</p>
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

    .hover-button-green:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4) !important;
    }

    .hover-button-blue:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
    }

    .hover-card:hover {
        box-shadow: 0 8px 25px var(--shadow-color) !important;
        transform: translateY(-5px);
    }

    .hover-notification {
        border: 2px solid #f0f0f0;
    }

    .hover-notification.notification-unread {
        border: 2px solid #3B82F6;
    }

    .hover-notification:hover {
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.15) !important;
        transform: translateY(-4px);
    }
</style>
@endsection
