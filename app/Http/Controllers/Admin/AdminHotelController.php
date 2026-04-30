<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use App\Mail\HotelApprovalNotification;
use App\Mail\HotelRejectionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminHotelController extends Controller
{
    /**
     * Display a listing of hotels
     */
    public function index(Request $request)
    {
        $query = Hotel::with('owner');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        
        $hotels = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get total counts from ALL hotels (not just filtered)
        $totalHotels = Hotel::count();
        $approvedCount = Hotel::where('status', 'APPROVED')->count();
        $pendingCount = Hotel::where('status', 'PENDING')->count();
        $rejectedCount = Hotel::where('status', 'REJECTED')->count();
        
        return view('admin.hotels.index', compact('hotels', 'totalHotels', 'approvedCount', 'pendingCount', 'rejectedCount'));
    }

    /**
     * Show the form for creating a new hotel
     */
    public function create()
    {
        $users = User::where('role', 'OWNER')->get();
        $dzongkhags = DB::table('dzongkhags')->get();
        
        return view('admin.hotels.create', compact('users', 'dzongkhags'));
    }

    /**
     * Store a newly created hotel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'property_type' => 'required|string',
            'dzongkhag_id' => 'required|exists:dzongkhags,id',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Generate hotel_id before creation
        $validated['hotel_id'] = Hotel::generateHotelId();

        Hotel::create($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully');
    }

    /**
     * Display the specified hotel
     */
    public function show($id)
    {
        $hotel = Hotel::with(['owner', 'rooms', 'dzongkhag'])->findOrFail($id);
        $bookings = DB::table('bookings')
            ->where('hotel_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.hotels.show', compact('hotel', 'bookings'));
    }

    /**
     * Show the form for editing the specified hotel
     */
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $users = User::where('role', 'OWNER')->get();
        $dzongkhags = DB::table('dzongkhags')->get();
        
        return view('admin.hotels.edit', compact('hotel', 'users', 'dzongkhags'));
    }

    /**
     * Update the specified hotel
     */
    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'property_type' => 'required|string',
            'dzongkhag_id' => 'required|exists:dzongkhags,id',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully');
    }

    /**
     * Remove the specified hotel
     */
    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        
        // Check if hotel has active bookings
        $activeBookings = DB::table('bookings')
            ->where('hotel_id', $id)
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->count();
        
        if ($activeBookings > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete hotel with active bookings']);
        }
        
        $hotel->delete();
        
        return redirect()->route('admin.hotels.index')->with('success', 'Hotel deleted successfully');
    }

    /**
     * Approve hotel
     */
    public function approve($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->update([
            'status' => 'approved',
            'hotel_id' => 'HTL' . str_pad($hotel->id, 6, '0', STR_PAD_LEFT)
        ]);

        // Send approval email to hotel owner
        try {
            Mail::to($hotel->email)->send(new HotelApprovalNotification($hotel));
        } catch (\Exception $e) {
            // Log error but don't fail the operation
            Log::error('Failed to send hotel approval email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Hotel approved successfully and email sent to owner');
    }

    /**
     * Reject hotel
     */
    public function reject(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $reason = $request->rejection_reason;
        
        $hotel->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        // Send rejection email to hotel owner
        try {
            Mail::to($hotel->email)->send(new HotelRejectionNotification($hotel, $reason));
        } catch (\Exception $e) {
            // Log error but don't fail the operation
            Log::error('Failed to send hotel rejection email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Hotel rejected successfully and email sent to owner');
    }
}


