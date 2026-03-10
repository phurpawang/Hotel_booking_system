<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GuestController extends Controller
{
    /**
     * Display a listing of guests
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');

        // Get all guests who have made bookings at this hotel
        $query = Guest::whereHas('bookings', function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id);
            })
            ->withCount(['bookings' => function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id);
            }])
            ->with(['bookings' => function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id)
                  ->where('status', 'CHECKED_OUT')
                  ->latest('actual_check_out')
                  ->limit(1);
            }]);

        if ($search) {
            $query->search($search);
        }

        $guests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('reception.guests.index', compact('guests', 'search'));
    }

    /**
     * Show guest details and booking history
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $guest = Guest::whereHas('bookings', function ($q) use ($user) {
            $q->where('hotel_id', $user->hotel_id);
        })->findOrFail($id);

        $bookings = Booking::where('hotel_id', $user->hotel_id)
            ->where('guest_id', $id)
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_bookings' => Booking::where('hotel_id', $user->hotel_id)
                ->where('guest_id', $id)
                ->count(),
            'completed' => Booking::where('hotel_id', $user->hotel_id)
                ->where('guest_id', $id)
                ->where('status', 'CHECKED_OUT')
                ->count(),
            'upcoming' => Booking::where('hotel_id', $user->hotel_id)
                ->where('guest_id', $id)
                ->whereIn('status', ['CONFIRMED', 'CHECKED_IN'])
                ->count(),
        ];

        return view('reception.guests.show', compact('guest', 'bookings', 'stats'));
    }

    /**
     * Show the form for creating a new guest
     */
    public function create()
    {
        return view('reception.guests.create');
    }

    /**
     * Store a newly created guest
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:guests,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        Guest::create($validated);

        return redirect()->route('reception.guests.index')
            ->with('success', 'Guest added successfully.');
    }

    /**
     * Show the form for editing a guest
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        $guest = Guest::whereHas('bookings', function ($q) use ($user) {
            $q->where('hotel_id', $user->hotel_id);
        })->findOrFail($id);

        return view('reception.guests.edit', compact('guest'));
    }

    /**
     * Update guest information
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $guest = Guest::whereHas('bookings', function ($q) use ($user) {
            $q->where('hotel_id', $user->hotel_id);
        })->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('guests')->ignore($guest->id)
            ],
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $guest->update($validated);

        return redirect()->route('reception.guests.index')
            ->with('success', 'Guest updated successfully.');
    }
}
