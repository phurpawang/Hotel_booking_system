<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('hotel.login')->with('error', 'Please login to continue.');
        }

        $user = auth()->user();

        // Check if user is an owner (case-insensitive)
        if (strtoupper($user->role) !== 'OWNER') {
            abort(403, 'Unauthorized access. Owner role required.');
        }

        // Check for hotel - support both ownership types:
        // 1. True owner: hotel.owner_id = user.id (ownedHotel relationship)
        // 2. Staff owner: user.hotel_id = hotel.id (hotel relationship)
        $hotel = $user->ownedHotel ?? $user->hotel;
        
        if (!$hotel) {
            auth()->logout();
            return redirect()->route('hotel.login')
                ->with('error', 'No hotel found for this owner account.');
        }

        if (strtoupper($hotel->status) !== 'APPROVED') {
            auth()->logout();
            return redirect()->route('hotel.login')
                ->with('error', 'Your hotel is not approved yet. Please wait for admin approval.');
        }

        return $next($request);
    }
}
