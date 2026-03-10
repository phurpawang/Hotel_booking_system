<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        
        // Get unique guests who have booked at this hotel
        $guests = Booking::where('hotel_id', $hotel->id)
            ->select(
                'guest_name as name',
                'guest_email as email',
                'guest_phone as phone',
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('MAX(check_out_date) as last_visit')
            )
            ->groupBy('guest_name', 'guest_email', 'guest_phone')
            ->orderBy('last_visit', 'desc')
            ->paginate(20);

        return view('owner.guests.index', compact('hotel', 'guests'));
    }

    public function show($email)
    {
        $hotel = $this->getOwnerHotel();
        
        $bookings = Booking::where('hotel_id', $hotel->id)
            ->where('guest_email', $email)
            ->with('room')
            ->orderBy('check_in_date', 'desc')
            ->get();

        if ($bookings->isEmpty()) {
            abort(404, 'Guest not found');
        }

        $guest = [
            'name' => $bookings->first()->guest_name,
            'email' => $bookings->first()->guest_email,
            'phone' => $bookings->first()->guest_phone,
        ];

        return view('owner.guests.show', compact('hotel', 'guest', 'bookings'));
    }
}
