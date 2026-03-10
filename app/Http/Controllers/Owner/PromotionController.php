<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        $promotions = Promotion::where('hotel_id', $hotel->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('owner.promotions.index', compact('hotel', 'promotions'));
    }

    public function create()
    {
        $hotel = $this->getOwnerHotel();
        return view('owner.promotions.create', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel = $this->getOwnerHotel();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'applicable_room_types' => 'nullable|array',
        ]);

        Promotion::create([
            'hotel_id' => $hotel->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'discount_percentage' => $validated['discount_percentage'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'applicable_room_types' => $validated['applicable_room_types'] ?? [],
            'is_active' => true,
        ]);

        return redirect()->route('owner.promotions.index')
            ->with('success', 'Promotion created successfully!');
    }

    public function edit($id)
    {
        $hotel = $this->getOwnerHotel();
        $promotion = Promotion::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        return view('owner.promotions.edit', compact('hotel', 'promotion'));
    }

    public function update(Request $request, $id)
    {
        $hotel = $this->getOwnerHotel();
        $promotion = Promotion::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'applicable_room_types' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $promotion->update($validated);

        return redirect()->route('owner.promotions.index')
            ->with('success', 'Promotion updated successfully!');
    }

    public function destroy($id)
    {
        $hotel = $this->getOwnerHotel();
        $promotion = Promotion::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $promotion->delete();

        return redirect()->route('owner.promotions.index')
            ->with('success', 'Promotion deleted successfully!');
    }
}
