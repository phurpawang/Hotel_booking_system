<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;
use App\Models\BookingCommission;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HotelDashboardController extends Controller
{
    /**
     * Show hotel dashboard
     */
    public function index()
    {
        $hotel = Auth::user()->hotel;

        if (!$hotel) {
            return redirect()->route('home')->with('error', 'No hotel found for your account.');
        }

        $totalRooms = $hotel->rooms()->sum('quantity');
        $availableRooms = $hotel->rooms()->where('status', 'AVAILABLE')->sum('quantity');
        $todayCheckIns = Booking::where('hotel_id', $hotel->id)
            ->whereDate('check_in_date', now())
            ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
            ->count();
        $upcomingBookings = Booking::where('hotel_id', $hotel->id)
            ->whereDate('check_in_date', '>', now())
            ->where('status', 'CONFIRMED')
            ->count();

        $recentBookings = Booking::where('hotel_id', $hotel->id)
            ->with('room')
            ->latest()
            ->take(5)
            ->get();

        return view('hotel.dashboard', compact('hotel', 'totalRooms', 'availableRooms', 'todayCheckIns', 'upcomingBookings', 'recentBookings'));
    }

    /**
     * Show hotel login form
     */
    public function showLoginForm()
    {
        return view('hotel.login');
    }

    /**
     * Handle hotel login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|string',
            'pin' => 'required|digits:4',
        ]);

        $hotel = Hotel::where('hotel_id', $validated['hotel_id'])
            ->where('status', 'approved')
            ->first();

        if (!$hotel) {
            return back()->withErrors(['hotel_id' => 'Invalid Hotel ID or hotel not approved.'])->withInput();
        }

        $user = $hotel->owner;

        if (!Hash::check($validated['pin'], $user->pin)) {
            return back()->withErrors(['pin' => 'Invalid PIN.'])->withInput();
        }

        Auth::login($user);

        return redirect()->route('hotel.dashboard');
    }

    /**
     * Manage rooms
     */
    public function manageRooms()
    {
        $hotel = Auth::user()->hotel;
        $rooms = $hotel->rooms()->paginate(10);

        return view('hotel.manage-rooms', compact('hotel', 'rooms'));
    }

    /**
     * Show add room form
     */
    public function showAddRoomForm()
    {
        $hotel = Auth::user()->hotel;
        return view('hotel.add-room', compact('hotel'));
    }

    /**
     * Store new room
     */
    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'cancellation_policy' => 'nullable|string',
        ]);

        $hotel = Auth::user()->hotel;

        Room::create([
            'hotel_id' => $hotel->id,
            'room_number' => $validated['room_number'],
            'room_type' => $validated['room_type'],
            'quantity' => $validated['quantity'],
            'capacity' => $validated['capacity'],
            'price_per_night' => $validated['price_per_night'],
            'description' => $validated['description'],
            'amenities' => $validated['amenities'] ?? [],
            'cancellation_policy' => $validated['cancellation_policy'],
            'status' => 'AVAILABLE',
        ]);

        return redirect()->route('hotel.manage-rooms')
            ->with('success', 'Room added successfully!');
    }

    /**
     * Update room
     */
    public function updateRoom(Request $request, $id)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50',
            'room_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:AVAILABLE,OCCUPIED,MAINTENANCE',
        ]);

        $hotel = Auth::user()->hotel;
        $room = Room::where('hotel_id', $hotel->id)->findOrFail($id);

        $room->update($validated);

        return back()->with('success', 'Room updated successfully!');
    }

    /**
     * View hotel bookings
     */
    public function viewBookings(Request $request)
    {
        $hotel = Auth::user()->hotel;
        $status = $request->query('status', 'all');

        $query = Booking::where('hotel_id', $hotel->id)->with('room');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('hotel.bookings', compact('bookings', 'status'));
    }

    // ========== NEW ROLE-BASED DASHBOARDS ==========

    /**
     * Owner Dashboard
     */
    public function ownerDashboard()
    {
        $userId = Session::get('hotel_user_id');
        $user = User::find($userId);
        
        // Get hotel owned by this user
        $hotel = Hotel::where('owner_id', $userId)->first();
        
        $data = [
            'user' => $user,
            'hotel' => $hotel,
            'totalRooms' => 0,
            'totalBookings' => 0,
            'totalRevenue' => 0,
            'pendingBookings' => 0,
            'recentBookings' => collect(),
            'rooms' => collect(),
            'staff' => collect(),
        ];

        if ($hotel) {
            $data['totalRooms'] = Room::where('hotel_id', $hotel->id)->sum('quantity');
            $data['totalBookings'] = Booking::where('hotel_id', $hotel->id)->count();
            $data['totalRevenue'] = Booking::where('hotel_id', $hotel->id)
                ->where('payment_status', 'PAID')
                ->sum('total_price');
            $data['pendingBookings'] = Booking::where('hotel_id', $hotel->id)
                ->where('status', 'pending')
                ->count();
            $data['recentBookings'] = Booking::where('hotel_id', $hotel->id)
                ->with('room')
                ->latest()
                ->take(10)
                ->get();
            $data['rooms'] = Room::where('hotel_id', $hotel->id)->get();
            $data['staff'] = User::where('hotel_id', $user->hotel_id)
                ->whereIn('role', ['MANAGER', 'RECEPTION'])
                ->get();
        }

        return view('hotel.dashboard.owner', $data);
    }

    /**
     * Manager Dashboard
     */
    public function managerDashboard()
    {
        $userId = Session::get('hotel_user_id');
        $user = User::find($userId);
        
        // Get hotel by hotel_id from user
        $hotel = Hotel::where('hotel_id', $user->hotel_id)->first();
        
        $data = [
            'user' => $user,
            'hotel' => $hotel,
            'todayCheckIns' => 0,
            'todayCheckOuts' => 0,
            'totalBookings' => 0,
            'availableRooms' => 0,
            'bookings' => collect(),
            'rooms' => collect(),
        ];

        if ($hotel) {
            $today = date('Y-m-d');
            $data['todayCheckIns'] = Booking::where('hotel_id', $hotel->id)
                ->whereDate('check_in_date', $today)
                ->count();
            $data['todayCheckOuts'] = Booking::where('hotel_id', $hotel->id)
                ->whereDate('check_out_date', $today)
                ->count();
            $data['totalBookings'] = Booking::where('hotel_id', $hotel->id)
                ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
                ->count();
            $data['availableRooms'] = Room::where('hotel_id', $hotel->id)
                ->where('is_available', true)
                ->count();
            $data['bookings'] = Booking::where('hotel_id', $hotel->id)
                ->with('room')
                ->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
                ->latest()
                ->get();
            $data['rooms'] = Room::where('hotel_id', $hotel->id)->get();
        }

        return view('hotel.dashboard.manager', $data);
    }

    /**
     * Reception Dashboard
     */
    public function receptionDashboard()
    {
        $userId = Session::get('hotel_user_id');
        $user = User::find($userId);
        
        // Get hotel by hotel_id from user
        $hotel = Hotel::where('hotel_id', $user->hotel_id)->first();
        
        $data = [
            'user' => $user,
            'hotel' => $hotel,
            'todayCheckIns' => 0,
            'todayCheckOuts' => 0,
            'pendingBookings' => 0,
            'recentBookings' => collect(),
        ];

        if ($hotel) {
            $today = date('Y-m-d');
            $data['todayCheckIns'] = Booking::where('hotel_id', $hotel->id)
                ->whereDate('check_in_date', $today)
                ->count();
            $data['todayCheckOuts'] = Booking::where('hotel_id', $hotel->id)
                ->whereDate('check_out_date', $today)
                ->count();
            $data['pendingBookings'] = Booking::where('hotel_id', $hotel->id)
                ->where('status', 'pending')
                ->count();
            $data['recentBookings'] = Booking::where('hotel_id', $hotel->id)
                ->with('room')
                ->latest()
                ->take(20)
                ->get();
        }

        return view('hotel.dashboard.reception', $data);
    }

    /**
     * Check-in guest
     */
    public function checkIn($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'CHECKED_IN']);

        return back()->with('success', 'Guest checked in successfully');
    }

    /**
     * Check-out guest
     */
    public function checkOut($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'CHECKED_OUT']);

        // Create commission record if not already exists
        if (!BookingCommission::where('booking_id', $booking->id)->exists()) {
            $commissionService = app(CommissionService::class);
            $commissionService->createBookingCommission($booking);
        }

        return back()->with('success', 'Guest checked out successfully');
    }

    /**
     * Update room availability
     */
    public function updateRoomAvailability(Request $request, $roomId)
    {
        $room = Room::findOrFail($roomId);
        $room->update(['is_available' => $request->is_available]);

        return back()->with('success', 'Room availability updated');
    }
}

