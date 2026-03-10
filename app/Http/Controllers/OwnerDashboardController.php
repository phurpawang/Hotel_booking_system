<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    /**
     * Get hotel for owner user - supports both ownership types
     * 1. True owner: hotel.owner_id = user.id 
     * 2. Staff owner: user.hotel_id = hotel.id
     */
    private function getOwnerHotel($user)
    {
        return Hotel::where('owner_id', $user->id)->first() ?? $user->hotel;
    }

    /**
     * Show owner dashboard with Chart.js data
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get the hotel - support both ownership types:
        // 1. True owner: hotel.owner_id = user.id
        // 2. Staff owner: user.hotel_id = hotel.id
        $hotel = Hotel::where('owner_id', $user->id)->first() ?? $user->hotel;
        
        if (!$hotel) {
            abort(404, 'Hotel not found for this owner.');
        }

        $totalRooms = Room::where('hotel_id', $hotel->id)->sum('quantity');
        $totalBookings = Booking::where('hotel_id', $hotel->id)->count();
        $pendingBookings = Booking::where('hotel_id', $hotel->id)
                                  ->where(DB::raw('UPPER(status)'), 'CONFIRMED')
                                  ->count();
        $totalStaff = User::where('hotel_id', $hotel->id)
                         ->whereIn(DB::raw('UPPER(role)'), ['MANAGER', 'RECEPTION'])
                         ->count();

        // Calculate additional metrics
        $monthlyRevenue = Booking::where('hotel_id', $hotel->id)
                                ->whereMonth('check_in_date', now()->month)
                                ->where('payment_status', 'PAID')
                                ->sum('total_price') ?? 0;
        
        $occupiedRooms = Room::where('hotel_id', $hotel->id)
                            ->where(DB::raw('UPPER(status)'), 'OCCUPIED')
                            ->sum('quantity');
        $availableRooms = $totalRooms - $occupiedRooms;
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
        
        $todayCheckIns = Booking::where('hotel_id', $hotel->id)
                               ->whereDate('check_in_date', now())
                               ->whereIn(DB::raw('UPPER(status)'), ['CONFIRMED', 'CHECKED_IN'])
                               ->count();
        $todayCheckOuts = Booking::where('hotel_id', $hotel->id)
                                ->whereDate('check_out_date', now())
                                ->where(DB::raw('UPPER(status)'), 'CHECKED_IN')
                                ->count();

        $recentBookings = Booking::where('hotel_id', $hotel->id)
                                ->with('room')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        // Get rooms overview
        $rooms = Room::where('hotel_id', $hotel->id)
                    ->orderBy('room_number')
                    ->limit(10)
                    ->get();

        // Get staff members
        $staffMembers = User::where('hotel_id', $hotel->id)
                           ->whereIn(DB::raw('UPPER(role)'), ['MANAGER', 'RECEPTION'])
                           ->orderBy('created_at', 'desc')
                           ->get();

        // 6-Month Revenue Trend for Chart.js
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueLabels[] = $month->format('M Y');
            $revenue = Booking::where('hotel_id', $hotel->id)
                ->where('payment_status', 'PAID')
                ->whereYear('check_in_date', $month->year)
                ->whereMonth('check_in_date', $month->month)
                ->sum('total_price') ?? 0;
            $revenueData[] = $revenue;
        }

        // 6-Month Booking Trend for Chart.js
        $bookingData = [];
        $bookingLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $bookingLabels[] = $month->format('M Y');
            $count = Booking::where('hotel_id', $hotel->id)
                ->whereYear('check_in_date', $month->year)
                ->whereMonth('check_in_date', $month->month)
                ->count();
            $bookingData[] = $count;
        }

        // Enhanced Statistics (like ReservationController)
        $stats = [
            'total' => $totalBookings,
            'today_checkins' => $todayCheckIns,
            'today_checkouts' => $todayCheckOuts,
            'pending' => Booking::where('hotel_id', $hotel->id)
                ->where(DB::raw('UPPER(status)'), 'CONFIRMED')
                ->where('payment_status', 'PENDING')
                ->count(),
            'monthly_revenue' => $monthlyRevenue,
            'confirmed' => Booking::where('hotel_id', $hotel->id)->where(DB::raw('UPPER(status)'), 'CONFIRMED')->count(),
            'checked_in' => Booking::where('hotel_id', $hotel->id)->where(DB::raw('UPPER(status)'), 'CHECKED_IN')->count(),
            'checked_out' => Booking::where('hotel_id', $hotel->id)->where(DB::raw('UPPER(status)'), 'CHECKED_OUT')->count(),
            'cancelled' => Booking::where('hotel_id', $hotel->id)->where(DB::raw('UPPER(status)'), 'CANCELLED')->count(),
        ];

        return view('owner.dashboard', compact(
            'hotel',
            'totalRooms',
            'totalBookings',
            'pendingBookings',
            'totalStaff',
            'monthlyRevenue',
            'occupancyRate',
            'availableRooms',
            'todayCheckIns',
            'todayCheckOuts',
            'recentBookings',
            'rooms',
            'staffMembers',
            'revenueData',
            'revenueLabels',
            'bookingData',
            'bookingLabels',
            'stats'
        ));
    }

    /**
     * Show hotel profile
     */
    public function hotelProfile()
    {
        $user = Auth::user();
        
        // Get the hotel owned by this user
        $hotel = $this->getOwnerHotel($user);
        
        if (!$hotel) {
            abort(404, 'Hotel not found for this owner.');
        }
        
        return view('owner.hotel-profile', compact('hotel'));
    }

    /**
     * Update hotel profile
     */
    public function updateHotelProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = Auth::user();
            
            // Get the hotel owned by this user
            $hotel = $this->getOwnerHotel($user);
            
            if (!$hotel) {
                return redirect()->back()
                    ->with('error', 'Hotel not found.');
            }
            
            $hotel->update([
                'hotel_name' => $request->hotel_name,
                'email' => $request->email,
            ]);

            return redirect()->back()
                ->with('success', 'Hotel profile updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Show staff management page
     */
    public function manageStaff()
    {
        $user = Auth::user();
        
        // Get the hotel owned by this user
        $hotel = $this->getOwnerHotel($user);
        
        if (!$hotel) {
            abort(404, 'Hotel not found for this owner.');
        }
        
        $staff = User::where('hotel_id', $hotel->id)
                    ->whereIn(DB::raw('UPPER(role)'), ['MANAGER', 'RECEPTION'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('owner.staff', compact('staff', 'hotel'));
    }

    /**
     * Show create staff form
     */
    public function showCreateStaffForm()
    {
        return view('owner.create-staff');
    }

    /**
     * Create new staff member (Manager or Reception)
     */
    public function createStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:MANAGER,RECEPTION',
        ], [
            'name.required' => 'Staff name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Please select a role.',
            'role.in' => 'Invalid role selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $owner = Auth::user();

            // Get the hotel owned by this user
            $hotel = $this->getOwnerHotel($owner);

            if (!$hotel) {
                return redirect()->back()
                    ->with('error', 'No hotel found for your account. Please contact support.')
                    ->withInput();
            }

            // Create staff user
            $staff = User::create([
                'hotel_id' => $hotel->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => strtoupper($request->role),
                'created_by' => $owner->id,
            ]);

            return redirect()->route('owner.staff')
                ->with('success', ucfirst($request->role) . ' account created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create staff account. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete staff member
     */
    public function deleteStaff($id)
    {
        try {
            $owner = Auth::user();
            
            // Get the hotel owned by this user
            $hotel = $this->getOwnerHotel($owner);
            
            if (!$hotel) {
                return redirect()->back()
                    ->with('error', 'Hotel not found.');
            }
            
            $staff = User::where('id', $id)
                        ->where('hotel_id', $hotel->id)
                        ->whereIn(DB::raw('UPPER(role)'), ['MANAGER', 'RECEPTION'])
                        ->firstOrFail();

            $staff->delete();

            return redirect()->back()
                ->with('success', 'Staff member deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete staff member.');
        }
    }

    /**
     * Show edit staff form
     */
    public function editStaff($id)
    {
        $owner = Auth::user();
        
        // Get the hotel owned by this user
        $hotel = $this->getOwnerHotel($owner);
        
        if (!$hotel) {
            abort(404, 'Hotel not found.');
        }
        
        $staff = User::where('id', $id)
                    ->where('hotel_id', $hotel->id)
                    ->whereIn(DB::raw('UPPER(role)'), ['MANAGER', 'RECEPTION'])
                    ->firstOrFail();

        return response()->json($staff);
    }

    /**
     * Update staff member
     */
    public function updateStaff(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:MANAGER,RECEPTION',
        ], [
            'name.required' => 'Staff name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered to another user.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Please select a role.',
            'role.in' => 'Invalid role selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $owner = Auth::user();

            // Get the hotel owned by this user
            $hotel = $this->getOwnerHotel($owner);

            if (!$hotel) {
                return redirect()->back()
                    ->with('error', 'No hotel found for your account.')
                    ->withInput();
            }

            // Find staff member
            $staff = User::where('id', $id)
                        ->where('hotel_id', $hotel->id)
                        ->whereIn(DB::raw('UPPER(role)'), ['MANAGER', 'RECEPTION'])
                        ->firstOrFail();

            // Update staff details
            $staff->name = $request->name;
            $staff->email = $request->email;
            $staff->role = strtoupper($request->role);

            // Update password only if provided
            if ($request->filled('password')) {
                $staff->password = Hash::make($request->password);
            }

            $staff->save();

            return redirect()->route('owner.staff')
                ->with('success', 'Staff member updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update staff member. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show rooms management
     */
    public function manageRooms()
    {
        $user = Auth::user();
        
        // Get the hotel owned by this user
        $hotel = $this->getOwnerHotel($user);
        
        if (!$hotel) {
            abort(404, 'Hotel not found for this owner.');
        }
        
        $rooms = Room::where('hotel_id', $hotel->id)
                    ->orderBy('room_number', 'asc')
                    ->get();

        return view('owner.rooms', compact('rooms'));
    }

    /**
     * View bookings - Redirect to new reservations system
     */
    public function viewBookings()
    {
        // Redirect to the new modern reservations dashboard
        return redirect()->route('owner.reservations.index');
    }

    /**
     * View reports
     */
    public function viewReports()
    {
        $user = Auth::user();
        
        // Get the hotel owned by this user
        $hotel = $this->getOwnerHotel($user);
        
        if (!$hotel) {
            abort(404, 'Hotel not found for this owner.');
        }

        // Calculate statistics
        $totalRevenue = Booking::where('hotel_id', $hotel->id)
                              ->where('status', 'completed')
                              ->sum('total_price');

        $monthlyRevenue = Booking::where('hotel_id', $hotel->id)
                                ->where('status', 'completed')
                                ->whereMonth('created_at', date('m'))
                                ->whereYear('created_at', date('Y'))
                                ->sum('total_price');

        $totalBookings = Booking::where('hotel_id', $hotel->id)->count();
        $completedBookings = Booking::where('hotel_id', $hotel->id)
                                    ->where('status', 'completed')
                                    ->count();

        return view('owner.reports', compact(
            'hotel',
            'totalRevenue',
            'monthlyRevenue',
            'totalBookings',
            'completedBookings'
        ));
    }

    /**
     * Bookings page - Redirect to new reservations system
     */
    public function bookings()
    {
        // Redirect to the new modern reservations dashboard
        return redirect()->route('owner.reservations.index');
    }

    /**
     * Rooms page (alias for manageRooms)
     */
    public function rooms()
    {
        return $this->manageRooms();
    }

    /**
     * Rates & Availability page
     */
    public function rates()
    {
        $user = Auth::user();
        
        // Get the hotel owned by this user
        $hotel = $this->getOwnerHotel($user);
        
        if (!$hotel) {
            abort(404, 'Hotel not found for this owner.');
        }
        
        $rooms = Room::where('hotel_id', $hotel->id)->get();

        return view('owner.rates', compact('hotel', 'rooms'));
    }

    /**
     * Reports page (alias for viewReports)
     */
    public function reports()
    {
        return $this->viewReports();
    }

    /**
     * Settings page
     */
    public function settings()
    {
        $user = Auth::user();
        
        // Get the hotel owned by this user
        $hotel = $this->getOwnerHotel($user);
        
        if (!$hotel) {
            abort(404, 'Hotel not found for this owner.');
        }

        return view('owner.settings', compact('hotel'));
    }
}
