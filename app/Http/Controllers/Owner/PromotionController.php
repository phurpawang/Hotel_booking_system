<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PromotionController extends Controller
{
    private function getOwnerHotel()
    {
        return Auth::user()->hotel;
    }

    /**
     * Get available discount types
     */
    private function getDiscountTypes()
    {
        return ['percentage' => 'Percentage (%)', 'fixed' => 'Fixed Amount (Nu.)'];
    }

    /**
     * Get available room types for the hotel
     */
    private function getAvailableRoomTypes($hotelId)
    {
        $roomTypes = Room::where('hotel_id', $hotelId)
            ->selectRaw('DISTINCT room_type')
            ->pluck('room_type')
            ->sort()
            ->values()
            ->toArray();
        
        return $roomTypes;
    }

    public function index()
    {
        $hotel = $this->getOwnerHotel();
        $promotions = Promotion::where('hotel_id', $hotel->id)
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('owner.promotions.index', compact('hotel', 'promotions'));
    }

    /**
     * Get all rooms grouped by type for the hotel
     */
    private function getRoomsGroupedByType($hotelId)
    {
        $rooms = Room::where('hotel_id', $hotelId)
            ->orderBy('room_type')
            ->orderBy('id')
            ->get()
            ->groupBy('room_type');
        
        return $rooms;
    }

    public function create()
    {
        $hotel = $this->getOwnerHotel();
        $roomsGroupedByType = $this->getRoomsGroupedByType($hotel->id);
        $discountTypes = $this->getDiscountTypes();
        
        return view('owner.promotions.create', compact('hotel', 'roomsGroupedByType', 'discountTypes'));
    }

    public function store(Request $request)
    {
        // Log the submission for debugging
        $submissionId = $request->input('submit_token', 'unknown');
        Log::info('Promotion store() called', [
            'timestamp' => now()->toDateTimeString(),
            'method' => strtoupper($request->getMethod()),
            'submit_token' => $submissionId,
            'user_id' => auth()->user()->id ?? 'unknown',
        ]);

        $hotel = $this->getOwnerHotel();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'room_type' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // ========== DUPLICATE DETECTION SAFEGUARD ==========
        // Check if an identical promotion was created in the last 5 seconds
        $recentDuplicate = Promotion::where('hotel_id', $hotel->id)
            ->where('title', $validated['title'])
            ->where('discount_type', $validated['discount_type'])
            ->where('discount_value', $validated['discount_value'])
            ->where('created_at', '>=', now()->subSeconds(5))
            ->first();

        if ($recentDuplicate) {
            Log::warning('Duplicate promotion submission detected and prevented', [
                'submit_token' => $submissionId,
                'existing_promotion_id' => $recentDuplicate->id,
                'created_seconds_ago' => now()->diffInSeconds($recentDuplicate->created_at),
            ]);

            // Redirect to index with success message (prevent user confusion)
            return redirect()->route('owner.promotions.index')
                ->with('success', 'Promotion created successfully!');
        }

        $promotionData = [
            'hotel_id' => $hotel->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $validated['is_active'] ?? true,
        ];

        // If no room type selected, create a single hotel-wide promotion (room_id = NULL)
        if (empty($validated['room_type'])) {
            $promotionData['room_id'] = null;
            $createdPromotion = Promotion::create($promotionData);
            
            Log::info('Hotel-wide promotion created', [
                'promotion_id' => $createdPromotion->id,
                'submit_token' => $submissionId,
            ]);
        } else {
            // Create a promotion for the selected room type
            $promotionData['room_type'] = $validated['room_type'];
            $promotionData['room_id'] = null; // IMPORTANT: no per-room linking

            $createdPromotion = Promotion::create($promotionData);

            Log::info('Room-type promotion created', [
                'promotion_id' => $createdPromotion->id,
                'room_type' => $validated['room_type'],
                'submit_token' => $submissionId,
            ]);
        }

        return redirect()->route('owner.promotions.index')
            ->with('success', 'Promotion created successfully!');
    }

    public function edit($id)
    {
        $hotel = $this->getOwnerHotel();
        $promotion = Promotion::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $roomsGroupedByType = $this->getRoomsGroupedByType($hotel->id);
        $discountTypes = $this->getDiscountTypes();

        return view('owner.promotions.edit', compact('hotel', 'promotion', 'roomsGroupedByType', 'discountTypes'));
    }

    public function update(Request $request, $id)
    {
        // Log the update submission for debugging
        $submissionId = $request->input('submit_token', 'unknown');
        Log::info('Promotion update() called', [
            'timestamp' => now()->toDateTimeString(),
            'method' => strtoupper($request->getMethod()),
            'promotion_id' => $id,
            'submit_token' => $submissionId,
            'user_id' => auth()->user()->id ?? 'unknown',
        ]);

        $hotel = $this->getOwnerHotel();
        $promotion = Promotion::where('id', $id)
            ->where('hotel_id', $hotel->id)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'room_type' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // ========== DUPLICATE DETECTION SAFEGUARD ==========
        // Check if an identical update was processed in the last 5 seconds
        $recentUpdate = Promotion::where('id', $id)
            ->where('title', $validated['title'])
            ->where('discount_type', $validated['discount_type'])
            ->where('discount_value', $validated['discount_value'])
            ->where('updated_at', '>=', now()->subSeconds(5))
            ->first();

        if ($recentUpdate && $recentUpdate->updated_at->diffInSeconds(now()) < 5) {
            Log::warning('Duplicate promotion update detected and prevented', [
                'submit_token' => $submissionId,
                'promotion_id' => $id,
                'updated_seconds_ago' => now()->diffInSeconds($recentUpdate->updated_at),
            ]);

            // Redirect to index with success message (prevent user confusion)
            return redirect()->route('owner.promotions.index')
                ->with('success', 'Promotion updated successfully!');
        }

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $validated['is_active'] ?? true,
        ];

        // If no room type, this is a hotel-wide promotion
        if (empty($validated['room_type'])) {
            $updateData['room_id'] = null;
            $promotion->update($updateData);
            
            Log::info('Promotion updated', [
                'promotion_id' => $id,
                'submit_token' => $submissionId,
            ]);
        } else {
            // If room type changed, update the promotion with new room type
            if ($promotion->room_type !== $validated['room_type']) {
                $updateData['room_type'] = $validated['room_type'];
                $updateData['room_id'] = null;
                $promotion->update($updateData);
                
                Log::info('Promotion room type changed', [
                    'promotion_id' => $id,
                    'old_room_type' => $promotion->room_type,
                    'new_room_type' => $validated['room_type'],
                    'submit_token' => $submissionId,
                ]);
            } else {
                // Room type hasn't changed, just update
                $promotion->update($updateData);
                
                Log::info('Promotion updated (same room type)', [
                    'promotion_id' => $id,
                    'submit_token' => $submissionId,
                ]);
            }
        }

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
