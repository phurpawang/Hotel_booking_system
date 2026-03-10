<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        $query = Room::where('hotel_id', $user->hotel_id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->status));
        }

        // Filter by room type
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        // Search by room number
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }

        $rooms = $query->orderBy('room_number')->paginate(15);

        // Statistics (using sum of quantities, not count of room types)
        $stats = [
            'total' => Room::where('hotel_id', $user->hotel_id)->sum('quantity'),
            'available' => Room::where('hotel_id', $user->hotel_id)->where('status', 'AVAILABLE')->sum('quantity'),
            'occupied' => Room::where('hotel_id', $user->hotel_id)->where('status', 'OCCUPIED')->sum('quantity'),
            'maintenance' => Room::where('hotel_id', $user->hotel_id)->where('status', 'MAINTENANCE')->sum('quantity'),
        ];

        return view('rooms.index', compact('rooms', 'hotel', 'stats'));
    }

    /**
     * Show the form for creating a new room
     */
    public function create()
    {
        $user = Auth::user();
        $hotel = $user->hotel;

        // Check authorization
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER'])) {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners and managers can add rooms.');
        }

        return view('rooms.create', compact('hotel'));
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check authorization
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER'])) {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners and managers can add rooms.');
        }

        $validator = Validator::make($request->all(), [
            'room_number' => [
                'nullable',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value) {
                        $exists = Room::where('hotel_id', $user->hotel_id)
                                     ->where('room_number', $value)
                                     ->exists();
                        if ($exists) {
                            $fail('Room number already exists for this hotel.');
                        }
                    }
                },
            ],
            'room_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1|max:100',
            'capacity' => 'required|integer|min:1|max:20',
            'base_price' => 'required|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'amenities' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'photos.*.image' => 'Each photo must be a valid image file.',
            'photos.*.mimes' => 'Photos must be JPEG, PNG, JPG, or GIF format.',
            'photos.*.max' => 'Each photo must not exceed 2MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Auto-generate room number if not provided
            $roomNumber = $request->room_number;
            if (empty($roomNumber)) {
                // Find highest room number for this room type
                $highestRoom = Room::where('hotel_id', $user->hotel_id)
                                  ->where('room_type', $request->room_type)
                                  ->orderByRaw('CAST(room_number AS UNSIGNED) DESC')
                                  ->first();
                
                if ($highestRoom) {
                    // Increment the highest number
                    $roomNumber = (string)((int)$highestRoom->room_number + 1);
                } else {
                    // First room of this type, assign default starting number based on type
                    $typeDefaults = [
                        'Single' => '101',
                        'Double' => '201',
                        'Twin' => '201',
                        'Deluxe' => '301',
                        'Suite' => '401',
                        'Family' => '501',
                        'VIP' => '601',
                    ];
                    $roomNumber = $typeDefaults[$request->room_type] ?? '101';
                }
            }

            // Calculate commission
            $commissionRate = $request->commission_rate ?? 10.00;
            $calculated = Room::calculateCommission($request->base_price, $commissionRate);

            $roomData = [
                'hotel_id' => $user->hotel_id,
                'room_number' => $roomNumber,
                'room_type' => $request->room_type,
                'quantity' => $request->quantity ?? 1,
                'capacity' => $request->capacity,
                'base_price' => $calculated['base_price'],
                'commission_rate' => $calculated['commission_rate'],
                'commission_amount' => $calculated['commission_amount'],
                'final_price' => $calculated['final_price'],
                'price_per_night' => $calculated['final_price'],
                'description' => $request->description,
                'amenities' => $request->amenities ? explode(',', $request->amenities) : [],
                'status' => 'AVAILABLE',
                'is_available' => true,
            ];

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                $photos = [];
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('room_photos', 'public');
                    $photos[] = $path;
                }
                $roomData['photos'] = $photos;
            }

            $room = Room::create($roomData);

            return redirect()->route(strtolower($user->role) . '.rooms.index')
                ->with('success', 'Room added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add room. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified room
     */
    public function show($id)
    {
        $user = Auth::user();
        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->with(['bookings' => function($query) {
                       $query->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
                             ->orderBy('check_in_date');
                   }])
                   ->firstOrFail();

        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room
     */
    public function edit($id)
    {
        $user = Auth::user();

        // Check authorization
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER'])) {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners and managers can edit rooms.');
        }

        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->firstOrFail();

        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Check authorization
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER'])) {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners and managers can edit rooms.');
        }

        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'room_number' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($user, $id) {
                    $exists = Room::where('hotel_id', $user->hotel_id)
                                 ->where('room_number', $value)
                                 ->where('id', '!=', $id)
                                 ->exists();
                    if ($exists) {
                        $fail('Room number already exists for this hotel.');
                    }
                },
            ],
            'room_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1|max:100',
            'capacity' => 'required|integer|min:1|max:20',
            'base_price' => 'required|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'amenities' => 'nullable|string',
            'status' => 'required|in:AVAILABLE,OCCUPIED,MAINTENANCE',
            'is_available' => 'nullable|boolean',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ], [
            'photos.*.image' => 'Each photo must be a valid image file.',
            'photos.*.mimes' => 'Photos must be JPEG, PNG, JPG, or GIF format.',
            'photos.*.max' => 'Each photo must not exceed 2MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Calculate commission
            $commissionRate = $request->commission_rate ?? $room->commission_rate ?? 10.00;
            $calculated = Room::calculateCommission($request->base_price, $commissionRate);

            $roomData = [
                'room_number' => $request->room_number,
                'room_type' => $request->room_type,
                'quantity' => $request->quantity ?? 1,
                'capacity' => $request->capacity,
                'base_price' => $calculated['base_price'],
                'commission_rate' => $calculated['commission_rate'],
                'commission_amount' => $calculated['commission_amount'],
                'final_price' => $calculated['final_price'],
                'price_per_night' => $calculated['final_price'],
                'description' => $request->description,
                'amenities' => $request->amenities ? explode(',', $request->amenities) : [],
                'status' => $request->status,
                'is_available' => $request->has('is_available') ? (bool)$request->is_available : true,
            ];

            // Handle new photo uploads
            if ($request->hasFile('photos')) {
                $photos = $room->photos ?? [];
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('room_photos', 'public');
                    $photos[] = $path;
                }
                $roomData['photos'] = $photos;
            }

            $room->update($roomData);

            return redirect()->route(strtolower($user->role) . '.rooms.index')
                ->with('success', 'Room updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update room.')
                ->withInput();
        }
    }

    /**
     * Toggle room availability
     */
    public function toggleAvailability($id)
    {
        $user = Auth::user();

        // Check authorization
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER'])) {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners and managers can change room availability.');
        }

        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->firstOrFail();

        try {
            $room->update([
                'is_available' => !$room->is_available
            ]);

            $status = $room->is_available ? 'available' : 'unavailable';
            return redirect()->back()
                ->with('success', "Room marked as {$status}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update room availability.');
        }
    }

    /**
     * Change room status
     */
    public function changeStatus(Request $request, $id)
    {
        $user = Auth::user();

        // Check authorization
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER'])) {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners and managers can change room status.');
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:AVAILABLE,OCCUPIED,MAINTENANCE',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->firstOrFail();

        try {
            $room->update(['status' => $request->status]);

            return redirect()->back()
                ->with('success', 'Room status updated to ' . $request->status);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update room status.');
        }
    }

    /**
     * Remove the specified room
     */
    public function destroy($id)
    {
        $user = Auth::user();

        // Only owners can delete rooms
        if (strtoupper($user->role) !== 'OWNER') {
            return redirect()->back()
                ->with('error', 'Unauthorized. Only owners can delete rooms.');
        }

        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->firstOrFail();

        // Check if room has active bookings
        $hasActiveBookings = $room->bookings()
                                  ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
                                  ->exists();

        if ($hasActiveBookings) {
            return redirect()->back()
                ->with('error', 'Cannot delete room with active bookings.');
        }

        try {
            // Delete photos from storage
            if ($room->photos) {
                foreach ($room->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }

            $room->delete();

            return redirect()->back()
                ->with('success', 'Room deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete room.');
        }
    }

    /**
     * Delete a specific photo from a room
     */
    public function deletePhoto(Request $request, $id)
    {
        $user = Auth::user();

        // Check authorization - only owners and managers can delete photos
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only owners and managers can delete photos.'
            ], 403);
        }

        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->firstOrFail();

        $photoPath = $request->input('photo');

        if (!$photoPath) {
            return response()->json([
                'success' => false,
                'message' => 'Photo path is required.'
            ], 400);
        }

        try {
            $photos = $room->photos ?? [];
            
            // Find and remove the photo from array
            $photoIndex = array_search($photoPath, $photos);
            
            if ($photoIndex === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo not found.'
                ], 404);
            }

            // Delete from storage
            Storage::disk('public')->delete($photoPath);

            // Remove from array
            unset($photos[$photoIndex]);
            
            // Re-index array
            $photos = array_values($photos);

            // Update room
            $room->update(['photos' => $photos]);

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room availability for calendar
     */
    public function availability($id, Request $request)
    {
        $user = Auth::user();
        $room = Room::where('id', $id)
                   ->where('hotel_id', $user->hotel_id)
                   ->firstOrFail();

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Get bookings for this room in the specified month
        $bookings = Booking::where('room_id', $room->id)
                          ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
                          ->whereYear('check_in_date', $year)
                          ->whereMonth('check_in_date', $month)
                          ->get(['check_in_date', 'check_out_date', 'guest_name', 'status']);

        return response()->json([
            'room' => $room,
            'bookings' => $bookings
        ]);
    }
}
