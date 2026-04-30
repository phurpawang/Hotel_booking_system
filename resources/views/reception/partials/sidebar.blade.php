<aside class="w-64 bg-purple-900 text-white flex-shrink-0">
    <div class="p-4 border-b border-purple-800">
        <a href="{{ route('reception.dashboard') }}" class="block text-center">
            <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 60px; width: 60px; border-radius: 50%; object-fit: cover; margin: 0 auto 8px;">
            <p class="text-sm text-purple-200">{{ Auth::user()->hotel->name ?? 'Hotel Name' }}</p>
            <span class="text-xs bg-purple-700 px-2 py-1 rounded mt-2 inline-block">Receptionist</span>
        </a>
    </div>
    
    <nav class="p-3">
        <a href="{{ route('reception.dashboard') }}" class="flex items-center px-3 py-2 {{ ($active ?? '') == 'dashboard' ? 'bg-purple-800' : 'hover:bg-purple-800' }} rounded-lg mb-1 transition">
            <i class="fas fa-chart-line mr-3"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('reception.reservations.index') }}" class="flex items-center px-3 py-2 {{ ($active ?? '') == 'bookings' ? 'bg-purple-800' : 'hover:bg-purple-800' }} rounded-lg mb-1 transition">
            <i class="fas fa-calendar-check mr-3"></i>
            <span>Bookings</span>
        </a>
        <a href="{{ route('reception.rooms.index') }}" class="flex items-center px-3 py-2 {{ ($active ?? '') == 'rooms' ? 'bg-purple-800' : 'hover:bg-purple-800' }} rounded-lg mb-1 transition">
            <i class="fas fa-bed mr-3"></i>
            <span>Rooms</span>
        </a>
        <a href="{{ route('reception.guests.index') }}" class="flex items-center px-3 py-2 {{ ($active ?? '') == 'guests' ? 'bg-purple-800' : 'hover:bg-purple-800' }} rounded-lg mb-1 transition">
            <i class="fas fa-users mr-3"></i>
            <span>Guests</span>
        </a>
        <a href="{{ route('reception.payments.index') }}" class="flex items-center px-3 py-2 {{ ($active ?? '') == 'payments' ? 'bg-purple-800' : 'hover:bg-purple-800' }} rounded-lg mb-1 transition">
            <i class="fas fa-credit-card mr-3"></i>
            <span>Payments</span>
        </a>
        <a href="{{ route('reception.notifications.index') }}" class="flex items-center px-3 py-2 {{ ($active ?? '') == 'notifications' ? 'bg-purple-800' : 'hover:bg-purple-800' }} rounded-lg mb-1 transition">
            <i class="fas fa-bell mr-3"></i>
            <span>Notifications</span>
        </a>
        <a href="{{ route('reception.profile.index') }}" class="flex items-center px-3 py-2 {{ ($active ?? '') == 'profile' ? 'bg-purple-800' : 'hover:bg-purple-800' }} rounded-lg mb-1 transition">
            <i class="fas fa-user-circle mr-3"></i>
            <span>Profile</span>
        </a>
        
        <div class="border-t border-purple-800 mt-2 pt-2">
            <form method="POST" action="{{ route('hotel.logout') }}">
                @csrf
                <button type="submit" class="flex items-center px-3 py-2 hover:bg-red-600 rounded-lg w-full transition">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
