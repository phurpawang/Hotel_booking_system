<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HotelAuthController extends Controller
{
    /**
     * Show hotel login form
     */
    public function showLogin()
    {
        // Redirect if already logged in
        if (Session::has('hotel_user_id')) {
            return $this->redirectToDashboard(Session::get('hotel_role'));
        }

        return view('hotel.login');
    }

    /**
     * Handle hotel login
     */
    public function login(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|string',
            'pin' => 'required|string|size:4',
        ]);

        // Find hotel by hotel_id code
        $hotel = Hotel::where('hotel_id', $request->hotel_id)->first();

        if (!$hotel) {
            return back()->withErrors(['error' => 'Hotel not found'])->withInput();
        }

        // Find user by hotel's primary key (id) and validate PIN
        $user = User::where('hotel_id', $hotel->id)->first();

        // Validate user and PIN
        if (!$user || !Hash::check($request->pin, $user->pin)) {
            return back()->withErrors(['error' => 'Invalid Hotel ID or PIN'])->withInput();
        }

        // Check if user is hotel staff
        if (!in_array(strtoupper($user->role), ['OWNER', 'MANAGER', 'RECEPTION'])) {
            return back()->withErrors(['error' => 'Access denied'])->withInput();
        }

        // Check if user is active
        if ($user->status !== 'active') {
            return back()->withErrors(['error' => 'User account is inactive'])->withInput();
        }

        // Store user info in session
        Session::put('hotel_user_id', $user->id);
        Session::put('hotel_id', $user->hotel_id);
        Session::put('hotel_role', strtoupper($user->role));
        Session::put('hotel_user_name', $user->name);
        Session::put('hotel_name', $hotel->name);

        // Redirect based on role
        return $this->redirectToDashboard(strtoupper($user->role));
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'OWNER':
                return redirect()->route('owner.dashboard');
            case 'MANAGER':
                return redirect()->route('manager.dashboard');
            case 'RECEPTION':
                return redirect()->route('reception.dashboard');
            default:
                return redirect()->route('hotel.login');
        }
    }

    /**
     * Logout hotel user
     */
    public function logout()
    {
        Session::forget('hotel_user_id');
        Session::forget('hotel_id');
        Session::forget('hotel_role');
        Session::forget('hotel_user_name');
        Session::forget('hotel_name');

        return redirect()->route('hotel.login')->with('success', 'Logged out successfully');
    }
}
