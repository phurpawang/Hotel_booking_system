<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Dzongkhag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PropertySettingsController extends Controller
{
    /**
     * Display the property settings form
     */
    public function edit()
    {
        $user = Auth::user();
        $hotel = Hotel::with('dzongkhag')->where('id', $user->hotel_id)->firstOrFail();
        $dzongkhags = Dzongkhag::orderBy('name')->get();
        
        return view('manager.property.edit', compact('hotel', 'dzongkhags'));
    }

    /**
     * Update the property settings
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $hotel = Hotel::where('id', $user->hotel_id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'dzongkhag_id' => 'required|exists:dzongkhags,id',
            'map_latitude' => 'nullable|numeric|between:-90,90',
            'map_longitude' => 'nullable|numeric|between:-180,180',
            'pin_location' => 'nullable|string',
            'description' => 'nullable|string|max:2000',
            'property_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        // Parse pin_location if provided to extract coordinates
        if (!empty($validated['pin_location'])) {
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

        // Handle property image upload
        if ($request->hasFile('property_image')) {
            // Delete old image if exists
            if ($hotel->property_image && Storage::disk('public')->exists($hotel->property_image)) {
                Storage::disk('public')->delete($hotel->property_image);
            }

            // Store new image
            $path = $request->file('property_image')->store('uploads/property', 'public');
            $validated['property_image'] = $path;
        }

        // Update hotel
        $hotel->update($validated);

        return redirect()->route('manager.property.edit')
            ->with('success', 'Property settings updated successfully!');
    }
}
