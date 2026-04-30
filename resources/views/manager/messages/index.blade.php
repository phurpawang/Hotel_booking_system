@extends('manager.layouts.app')

@section('title', 'Messages')

@section('header')
<h1 class="text-2xl font-bold text-gray-800">Messages</h1>
<p class="text-gray-600 mt-1">View and manage guest messages</p>
@endsection

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem 2.5rem; border-radius: 20px; margin-bottom: 2.5rem; box-shadow: 0 10px 40px rgba(102, 126, 234, 0.35); animation: slideInDown 0.5s ease-out;">
        <h2 style="margin: 0 0 0.5rem 0; font-weight: 700; font-size: 2.2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-envelope me-3"></i>Messages</h2>
        <p style="margin: 0; opacity: 0.95; font-size: 1.05rem;">View and manage guest messages</p>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; animation: slideInUp 0.5s ease-out;">
        <!-- Total Messages Card -->
        <div style="background: white; border-radius: 15px; padding: 1.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.25)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 70px; height: 70px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-envelope"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: #667eea; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Total Messages</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #333;">{{ $messages->total() }}</div>
                    <small style="color: #999; font-weight: 600;">All messages</small>
                </div>
            </div>
        </div>

        <!-- Unread Messages Card -->
        <div style="background: white; border-radius: 15px; padding: 1.8rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(245, 158, 11, 0.25)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 70px; height: 70px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; flex-shrink: 0; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div style="flex: 1;">
                    <div style="color: #f59e0b; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 0.5rem;">Unread Messages</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #333;">{{ $messages->where('status', 'UNREAD')->count() }}</div>
                    <small style="color: #999; font-weight: 600;">Awaiting review</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages List Section -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.6s ease-out;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border: none;">
            <h5 style="margin: 0; font-weight: 700; font-size: 1.2rem;"><i class="fas fa-list me-2"></i>Messages List</h5>
        </div>
        
        <div style="padding: 2rem;">
            @forelse($messages as $message)
            <div style="border-bottom: 1px solid #e5e7eb; padding: 1.5rem; transition: all 0.3s ease; border-radius: 12px; margin-bottom: 1rem; background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);" onmouseover="this.style.backgroundColor='#f5f7fa'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.15)'" onmouseout="this.style.backgroundColor='linear-gradient(135deg, #ffffff 0%, #f9fafb 100%)'; this.style.boxShadow='none'">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-user-circle" style="font-size: 1.5rem; color: #667eea;"></i>
                            <h3 style="margin: 0; font-weight: 700; color: #333;">{{ $message->guest_name }}</h3>
                            @if($message->status == 'UNREAD')
                            <span style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-weight: 700; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-star"></i>New
                            </span>
                            @endif
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.85rem; color: #666; margin-bottom: 1rem; flex-wrap: wrap;">
                            <span><i class="fas fa-envelope" style="color: #667eea; margin-right: 0.4rem;"></i>{{ $message->guest_email }}</span>
                            <span style="color: #ccc;">•</span>
                            <span><i class="fas fa-clock" style="color: #667eea; margin-right: 0.4rem;"></i>{{ $message->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <p style="color: #555; line-height: 1.6; margin: 0;">{{ $message->message }}</p>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @if($message->status == 'UNREAD')
                        <form method="POST" action="{{ route('manager.messages.markAsRead', $message->id) }}" style="margin: 0;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px; border: none; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fas fa-check"></i>Mark as Read
                            </button>
                        </form>
                        @else
                        <span style="background: #e5e7eb; color: #666; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-align: center; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check-double"></i>Read
                        </span>
                        @endif
                        <form method="POST" action="{{ route('manager.messages.destroy', $message->id) }}" onsubmit="return confirm('Are you sure you want to delete this message?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px; border: none; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; width: 100%;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fas fa-trash"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.3;">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5 style="color: #333; margin-bottom: 0.5rem; font-weight: 700; font-size: 1.3rem;">No messages yet</h5>
                <p style="color: #999; margin-bottom: 0; font-size: 1rem;">Guest messages will appear here</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($messages->hasPages())
        <div style="padding: 2rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
            {{ $messages->links() }}
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
