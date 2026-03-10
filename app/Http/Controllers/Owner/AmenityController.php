<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmenityController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        $amenities = Amenity::where('hotel_id', $hotel->id)
            ->orderBy('name')
            ->paginate(15);

        return view('owner.amenities.index', compact('hotel', 'amenities'));
    }

    public function create()
    {
        $hotel = $this->getOwnerHotel();
        return view('owner.amenities.create', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel = $this->getOwnerHotel();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        Amenity::create([
            'hotel_id' => $hotel->id,
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? 'fa-check',
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('owner.amenities.index')
            ->with('success', 'Amenity added successfully!');
    }

    public function edit($id)
    {
        $hotel = $this->getOwnerHotel();
        $amenity = Amenity::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        return view('owner.amenities.edit', compact('hotel', 'amenity'));
    }

    public function update(Request $request, $id)
    {
        $hotel = $this->getOwnerHotel();
        $amenity = Amenity::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $amenity->update($validated);

        return redirect()->route('owner.amenities.index')
            ->with('success', 'Amenity updated successfully!');
    }

    public function destroy($id)
    {
        $hotel = $this->getOwnerHotel();
        $amenity = Amenity::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $amenity->delete();

        return redirect()->route('owner.amenities.index')
            ->with('success', 'Amenity deleted successfully!');
    }
}
