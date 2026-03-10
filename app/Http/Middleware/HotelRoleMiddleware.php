<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HotelRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is logged in
        if (!Session::has('hotel_user_id')) {
            return redirect()->route('hotel.login')->with('error', 'Please login to access this page');
        }

        // Check if user has required role
        $userRole = Session::get('hotel_role');
        
        if (!in_array($userRole, $roles)) {
            return redirect()->back()->with('error', 'Access denied');
        }

        return $next($request);
    }
}
