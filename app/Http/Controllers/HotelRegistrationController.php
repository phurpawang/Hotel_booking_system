<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use App\Models\Dzongkhag;
use App\Models\HotelDocument;
use App\Rules\BhutanPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HotelRegistrationController extends Controller
{
    /**
     * Show the hotel registration form
     */
    public function showRegistrationForm()
    {
        $dzongkhags = Dzongkhag::all();
        return view('hotel.register', compact('dzongkhags'));
    }

    /**
     * Handle the hotel registration submission
     */
    public function register(Request $request)
    {
        // Custom validation for property image to accept all valid image formats
        $propertyImageRules = 'nullable|max:2048';
        if ($request->hasFile('property_image')) {
            $extension = strtolower($request->file('property_image')->getClientOriginalExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                return back()->withErrors(['property_image' => 'The property image must be a file of type: jpeg, jpg, png, or gif.'])
                            ->withInput();
            }
            // Check file size
            if ($request->file('property_image')->getSize() > 2048 * 1024) {
                return back()->withErrors(['property_image' => 'The property image must not exceed 2MB.'])
                            ->withInput();
            }
        }

        // Validate the request
        $validated = $request->validate([
            // Personal Details
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => ['required', new BhutanPhoneNumber()],
            'password' => 'required|string|min:8|confirmed',
            
            // Property Details
            'hotel_name' => 'required|string|max:255',
            'property_type' => 'required|string',
            'dzongkhag_id' => 'required|exists:dzongkhags,id',
            'address' => 'required|string',
            'pin_location' => 'nullable|string',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'description' => 'nullable|string',
            'property_image' => $propertyImageRules,
            'phone' => ['required', new BhutanPhoneNumber()],
            
            // Tourism Registration Details
            'tourism_license_number' => 'required|string|max:255',
            'issuing_authority' => 'required|string|max:255',
            'license_issue_date' => 'required|date',
            'license_expiry_date' => 'required|date|after:license_issue_date',
            
            // Documents
            'tourism_license_doc' => 'required|file|extensions:pdf,jpg,jpeg,png|max:5120',
            'property_ownership_doc' => 'required|file|extensions:pdf,jpg,jpeg,png|max:5120',
            
            // Declaration
            'declaration' => 'required|accepted',
        ], [
            'property_image.max' => 'The property image must not exceed 2MB.',
            'tourism_license_doc.extensions' => 'The tourism license document must be a file of type: pdf, jpg, jpeg, or png.',
            'tourism_license_doc.max' => 'The tourism license document must not exceed 5MB.',
            'property_ownership_doc.extensions' => 'The property ownership doc field must be a file of type: pdf, jpg, jpeg, png.',
            'property_ownership_doc.max' => 'The property ownership document must not exceed 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // Create the user (auto-assigned as OWNER)
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['mobile'],
                'password' => Hash::make($validated['password']),
                'role' => 'OWNER', // Always OWNER for property registration
            ]);

            // Handle property image upload
            $propertyImagePath = null;
            if ($request->hasFile('property_image')) {
                $file = $request->file('property_image');
                $extension = strtolower($file->getClientOriginalExtension());
                $filename = 'property_' . time() . '.' . $extension;
                $propertyImagePath = $file->storeAs('property_images', $filename, 'public');
            }

            // Get dzongkhag name
            $dzongkhag = Dzongkhag::find($validated['dzongkhag_id']);

            // Generate hotel_id
            $hotelId = Hotel::generateHotelId();

            // Create the hotel
            $hotel = Hotel::create([
                'hotel_id' => $hotelId,
                'owner_id' => $user->id,
                'name' => $validated['hotel_name'],
                'property_type' => $validated['property_type'],
                'address' => $validated['address'],
                'dzongkhag' => $dzongkhag ? $dzongkhag->name : null,
                'dzongkhag_id' => $validated['dzongkhag_id'],
                'pin_location' => $validated['pin_location'] ?? null,
                'phone' => $validated['phone'],
                'mobile' => $validated['mobile'],
                'email' => $validated['email'],
                'description' => $validated['description'],
                'property_image' => $propertyImagePath,
                'star_rating' => $validated['star_rating'],
                'tourism_license_number' => $validated['tourism_license_number'],
                'issuing_authority' => $validated['issuing_authority'],
                'license_issue_date' => $validated['license_issue_date'],
                'license_expiry_date' => $validated['license_expiry_date'],
                'status' => 'pending',
            ]);

            // Update user's hotel_id
            $user->update(['hotel_id' => $hotel->id]);

            // Handle document uploads
            if ($request->hasFile('tourism_license_doc')) {
                $file = $request->file('tourism_license_doc');
                $extension = strtolower($file->getClientOriginalExtension());
                
                // Validate extension
                if (!in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
                    DB::rollBack();
                    return back()->withErrors(['tourism_license_doc' => 'The tourism license document must be a PDF, JPG, JPEG, or PNG file.'])
                                ->withInput();
                }
                
                $filename = 'tourism_license_' . time() . '.' . $extension;
                $path = $file->storeAs('hotel_documents/' . $hotel->id, $filename, 'public');
                
                HotelDocument::create([
                    'hotel_id' => $hotel->id,
                    'document_type' => 'TOURISM_LICENSE',
                    'file_name' => $filename,
                    'file_path' => $path,
                    'file_type' => $extension,
                    'file_size' => $file->getSize(),
                ]);
            }

            if ($request->hasFile('property_ownership_doc')) {
                $file = $request->file('property_ownership_doc');
                $extension = strtolower($file->getClientOriginalExtension());
                
                // Validate extension
                if (!in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
                    DB::rollBack();
                    return back()->withErrors(['property_ownership_doc' => 'The property ownership document must be a PDF, JPG, JPEG, or PNG file.'])
                                ->withInput();
                }
                
                $filename = 'property_ownership_' . time() . '.' . $extension;
                $path = $file->storeAs('hotel_documents/' . $hotel->id, $filename, 'public');
                
                HotelDocument::create([
                    'hotel_id' => $hotel->id,
                    'document_type' => 'PROPERTY_OWNERSHIP',
                    'file_name' => $filename,
                    'file_path' => $path,
                    'file_type' => $extension,
                    'file_size' => $file->getSize(),
                ]);
            }

            DB::commit();

            return redirect()->route('hotel.registration.success')
                           ->with('success', 'Your registration has been submitted successfully! You will receive your Hotel ID via email/SMS once approved by admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Show registration success page
     */
    public function registrationSuccess()
    {
        return view('hotel.registration-success');
    }

    /**
     * Show check approval status form
     */
    public function showCheckStatusForm()
    {
        return view('hotel.check-status');
    }

    /**
     * Check hotel approval status
     */
    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'mobile' => 'required|string',
            'tourism_license_number' => 'required|string',
        ]);

        // Find hotel by matching email and tourism license
        // The mobile field can match either the mobile or phone column
        $hotel = Hotel::where('email', $validated['email'])
            ->where('tourism_license_number', $validated['tourism_license_number'])
            ->where(function($query) use ($validated) {
                $query->where('mobile', $validated['mobile'])
                      ->orWhere('phone', $validated['mobile']);
            })
            ->first();

        if (!$hotel) {
            return back()->withErrors(['error' => 'No hotel found with the provided details. Please check your information and try again.'])
                        ->withInput();
        }

        // Find the owner/user associated with this hotel
        $user = User::where('hotel_id', $hotel->id)->first();

        return view('hotel.status-result', compact('hotel', 'user'));
    }
}
