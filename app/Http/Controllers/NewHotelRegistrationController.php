<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HotelRegistrationController extends Controller
{
    /**
     * Show hotel registration form (Owner only)
     */
    public function showRegistrationForm()
    {
        return view('auth.hotel-register');
    }

    /**
     * Handle hotel registration
     */
    public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'hotel_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'license_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ownership_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'hotel_name.required' => 'Hotel name is required.',
            'owner_name.required' => 'Owner name is required.',
            'owner_email.required' => 'Email is required.',
            'owner_email.email' => 'Please enter a valid email address.',
            'owner_email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'license_document.required' => 'License document is required.',
            'license_document.mimes' => 'License must be a PDF or image file.',
            'ownership_document.required' => 'Ownership document is required.',
            'ownership_document.mimes' => 'Ownership document must be a PDF or image file.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate unique hotel ID
            $hotelId = Hotel::generateHotelId();

            // Store license document
            $licensePath = null;
            if ($request->hasFile('license_document')) {
                $licenseFile = $request->file('license_document');
                $licenseName = $hotelId . '_license_' . time() . '.' . $licenseFile->getClientOriginalExtension();
                $licensePath = $licenseFile->storeAs('hotel_documents', $licenseName, 'public');
            }

            // Store ownership document
            $ownershipPath = null;
            if ($request->hasFile('ownership_document')) {
                $ownershipFile = $request->file('ownership_document');
                $ownershipName = $hotelId . '_ownership_' . time() . '.' . $ownershipFile->getClientOriginalExtension();
                $ownershipPath = $ownershipFile->storeAs('hotel_documents', $ownershipName, 'public');
            }

            // Create hotel entry
            $hotel = Hotel::create([
                'hotel_id' => $hotelId,
                'hotel_name' => $request->hotel_name,
                'email' => $request->owner_email,
                'license_document' => $licensePath,
                'ownership_document' => $ownershipPath,
                'status' => 'pending',
            ]);

            // Create owner user entry
            $user = User::create([
                'hotel_id' => $hotelId,
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
                'created_by' => null, // Owner is not created by anyone
            ]);

            DB::commit();

            return redirect()->route('hotel.registration.success')
                ->with('success', 'Registration submitted successfully!')
                ->with('hotel_id', $hotelId);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show registration success page
     */
    public function registrationSuccess()
    {
        $hotelId = session('hotel_id');
        
        if (!$hotelId) {
            return redirect()->route('hotel.register');
        }

        return view('auth.registration-success', compact('hotelId'));
    }

    /**
     * Show check status form
     */
    public function showCheckStatusForm()
    {
        return view('auth.check-status');
    }

    /**
     * Check hotel registration status
     */
    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $hotel = Hotel::where('hotel_id', $request->hotel_id)
                     ->where('email', $request->email)
                     ->first();

        if (!$hotel) {
            return redirect()->back()
                ->with('error', 'Hotel registration not found.')
                ->withInput();
        }

        return view('auth.check-status', [
            'hotel' => $hotel,
            'statusChecked' => true,
        ]);
    }
}
