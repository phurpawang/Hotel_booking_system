<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (auth()->check()) {
            return $this->redirectBasedOnRole(auth()->user());
        }

        return view('auth.hotel-login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'hotel_id.required' => 'Hotel ID is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('hotel_id', 'email'));
        }

        try {
            // Check if hotel exists
            $hotel = Hotel::where('hotel_id', $request->hotel_id)->first();

            if (!$hotel) {
                return redirect()->back()
                    ->with('error', 'Invalid Hotel ID. Please check your Hotel ID and try again. Contact admin if you need assistance.')
                    ->withInput($request->only('hotel_id', 'email'));
            }

            // Check hotel status (case-insensitive)
            $hotelStatus = strtolower($hotel->status);
            
            if ($hotelStatus === 'pending') {
                return redirect()->back()
                    ->with('error', 'Your hotel registration is awaiting admin approval.')
                    ->withInput($request->only('hotel_id', 'email'));
            }

            if ($hotelStatus === 'rejected') {
                return redirect()->back()
                    ->with('error', 'Your hotel registration has been rejected. Reason: ' . ($hotel->rejection_reason ?? 'Not specified'))
                    ->withInput($request->only('hotel_id', 'email'));
            }

            // Find user by hotel's internal ID (not hotel_id string)
            $user = User::where('hotel_id', $hotel->id)  // Use $hotel->id, not $request->hotel_id
                       ->where('email', $request->email)
                       ->first();

            if (!$user) {
                return redirect()->back()
                    ->with('error', 'Invalid credentials.')
                    ->withInput($request->only('hotel_id', 'email'));
            }

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Invalid credentials.')
                    ->withInput($request->only('hotel_id', 'email'));
            }

            // Double-check hotel is approved (case-insensitive)
            if ($hotelStatus !== 'approved') {
                return redirect()->back()
                    ->with('error', 'Your hotel is not approved yet.')
                    ->withInput($request->only('hotel_id', 'email'));
            }

            // Login the user using Laravel's auth system
            Auth::login($user, $request->has('remember'));
            
            // Also set custom session variables for hotel middleware compatibility
            $request->session()->put('hotel_user_id', $user->id);
            $request->session()->put('hotel_role', $user->role);
            $request->session()->put('hotel_id', $hotel->id);

            $request->session()->regenerate();

            // Redirect based on role
            return $this->redirectBasedOnRole($user);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Login failed. Please try again.')
                ->withInput($request->only('hotel_id', 'email'));
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hotel.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectBasedOnRole(User $user)
    {
        // Convert role to lowercase for comparison
        $role = strtolower($user->role);
        
        switch ($role) {
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'manager':
                return redirect()->route('manager.dashboard');
            case 'reception':
            case 'receptionist':
                return redirect()->route('reception.dashboard');
            default:
                Auth::logout();
                return redirect()->route('hotel.login')
                    ->with('error', 'Invalid user role.');
        }
    }
}
