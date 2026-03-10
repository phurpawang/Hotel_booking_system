<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
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

        // Check if user is a manager (case-insensitive)
        if (strtoupper($user->role) !== 'MANAGER') {
            abort(403, 'Unauthorized access. Manager role required.');
        }

        // Check if manager has an approved hotel (using hotel relationship)
        $hotel = $user->hotel;
        if (!$hotel) {
            auth()->logout();
            return redirect()->route('hotel.login')
                ->with('error', 'No hotel found for this account.');
        }

        if (strtoupper($hotel->status) !== 'APPROVED') {
            auth()->logout();
            return redirect()->route('hotel.login')
                ->with('error', 'Your hotel is not approved yet.');
        }

        return $next($request);
    }
}
