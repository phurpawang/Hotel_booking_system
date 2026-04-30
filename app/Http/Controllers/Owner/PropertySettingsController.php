<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertySettingsController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    public function edit()
    {
        $hotel = $this->getOwnerHotel();
        
        return view('owner.property.edit', compact('hotel'));
    }

    public function update(Request $request)
    {
        $hotel = $this->getOwnerHotel();
        
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'map_latitude' => 'nullable|numeric|between:-90,90',
            'map_longitude' => 'nullable|numeric|between:-180,180',
            'pin_location' => 'nullable|string',
            'description' => 'nullable|string|max:1000',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'cancellation_policy' => 'nullable|string|max:1000',
            'property_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Parse pin_location if provided to extract coordinates
        if (!empty($validated['pin_location'])) {
            // Try to extract coordinates from pin_location (either "lat,long" format or Google Maps URL)
            $pinLoc = $validated['pin_location'];
            
            // Try coordinate format first
            $coordMatch = preg_match('/(-?\d+\.?\d*)\s*[,]\s*(-?\d+\.?\d*)/', $pinLoc, $matches);
            if ($coordMatch) {
                $validated['map_latitude'] = (float)$matches[1];
                $validated['map_longitude'] = (float)$matches[2];
            } else {
                // Try Google Maps URL format
                $urlMatch = preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $pinLoc, $matches);
                if ($urlMatch) {
                    $validated['map_latitude'] = (float)$matches[1];
                    $validated['map_longitude'] = (float)$matches[2];
                }
            }
        }

        // Handle image upload
        if ($request->hasFile('property_image')) {
            // Delete old image if exists
            if ($hotel->image_path && Storage::exists($hotel->image_path)) {
                Storage::delete($hotel->image_path);
            }

            $imagePath = $request->file('property_image')->store('uploads/property', 'public');
            $validated['image_path'] = $imagePath;
        }

        $hotel->update($validated);

        return redirect()->back()->with('success', 'Property settings updated successfully!');
    }
}
