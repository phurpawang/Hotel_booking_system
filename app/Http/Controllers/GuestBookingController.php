<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Dzongkhag;
use App\Models\Guest;
use App\Models\Promotion;
use App\Services\PromotionService;
use App\Services\RoomInventoryService;
use App\Rules\BhutanPhoneNumber;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GuestBookingController extends Controller
{
    /**
     * Show home page with search form
     */
    public function index()
    {
        $dzongkhags = Dzongkhag::all();
        $featuredHotels = Hotel::where('status', 'approved')
            ->with([
                'promotions' => function($q) {
                    $q->where('is_active', true)
                      ->whereDate('start_date', '<=', now())
                      ->whereDate('end_date', '>=', now())
                      ->orderBy('created_at', 'desc');
                }
            ])
            ->latest()
            ->take(6)
            ->get();

        return view('guest.home', compact('dzongkhags', 'featuredHotels'));
    }

    /**
     * Search for available hotels with aggregated room inventory
     */
    public function search(Request $request)
    {
        $inventoryService = new RoomInventoryService();

        $validated = $request->validate([
            'dzongkhag_id' => 'nullable|exists:dzongkhags,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'rooms' => 'required|integer|min:1',
            'sort' => 'nullable|in:rating,price_low,price_high,name',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric',
            'amenities' => 'nullable|string',
            'reviews' => 'nullable|string',
            'types' => 'nullable|string',
        ]);

        // Calculate total guests
        $validated['guests'] = $validated['adults'] + ($validated['children'] ?? 0);

        $query = Hotel::where('status', 'approved')
            ->select(['id','name','status','dzongkhag_id','star_rating','amenities','property_type','description','address','phone','email','map_latitude','map_longitude','photos','created_at'])
            ->with([
                'promotions' => function($q) {
                    $q->where('is_active', true)
                      ->whereDate('start_date', '<=', now())
                      ->whereDate('end_date', '>=', now())
                      ->orderBy('created_at', 'desc');
                },
                'reviews' => function($q) {
                    $q->where('status', 'APPROVED');
                },
                'dzongkhag'
            ]);

        // Filter by dzongkhag if specified
        if ($request->filled('dzongkhag_id') && $request->dzongkhag_id > 0) {
            $query->where('dzongkhag_id', $request->dzongkhag_id);
        }

        $hotels = $query->get();

        // Filter hotels with available rooms and get aggregated inventory for each
        $availableHotels = $hotels->filter(function($hotel) use ($validated, $inventoryService) {
            // Check if hotel has any available rooms
            $totalAvailable = $inventoryService->getTotalAvailableCount($hotel->id);
            
            if ($totalAvailable < $validated['rooms']) {
                return false;
            }

            // Add aggregated inventory to hotel object
            $hotel->inventory = $inventoryService->getAvailableInventory($hotel->id);
            
            return true;
        });

        // Apply filters
        $priceMin = $request->filled('price_min') ? (float)$request->price_min : 0;
        $priceMax = $request->filled('price_max') ? (float)$request->price_max : 999999;
        $selectedAmenities = $request->filled('amenities') ? explode(',', $request->amenities) : [];
        $selectedReviews = $request->filled('reviews') ? explode(',', $request->reviews) : [];
        $selectedTypes = $request->filled('types') ? explode(',', $request->types) : [];

        $availableHotels = $availableHotels->filter(function($hotel) use (
            $priceMin, 
            $priceMax, 
            $selectedAmenities, 
            $selectedReviews, 
            $selectedTypes
        ) {
            // Price filter
            $minPrice = null;
            if ($hotel->inventory) {
                foreach ($hotel->inventory as $variants) {
                    foreach ($variants as $variant) {
                        if ($minPrice === null || $variant['price'] < $minPrice) {
                            $minPrice = $variant['price'];
                        }
                    }
                }
            }
            
            if ($minPrice !== null && ($minPrice < $priceMin || $minPrice > $priceMax)) {
                return false;
            }

            // Review filter
            if (!empty($selectedReviews)) {
                $avgRating = $hotel->reviews->isNotEmpty() 
                    ? $hotel->reviews->avg('overall_rating')
                    : $hotel->star_rating ?? 0;
                
                $passedReviewFilter = false;
                foreach ($selectedReviews as $score) {
                    if ($avgRating >= (float)$score) {
                        $passedReviewFilter = true;
                        break;
                    }
                }
                if (!$passedReviewFilter) {
                    return false;
                }
            }

            // Property type filter (based on property_type field if exists, or default to 'hotel')
            if (!empty($selectedTypes)) {
                $propertyType = $hotel->property_type ?? 'hotel';
                if (!in_array($propertyType, $selectedTypes)) {
                    return false;
                }
            }

            // Amenities filter
            if (!empty($selectedAmenities)) {
                $hotelAmenities = json_decode($hotel->amenities, true) ?? [];
                $hasRequiredAmenities = false;
                
                foreach ($selectedAmenities as $amenity) {
                    if (in_array($amenity, $hotelAmenities)) {
                        $hasRequiredAmenities = true;
                        break;
                    }
                }
                
                if (!$hasRequiredAmenities) {
                    return false;
                }
            }

            return true;
        });

        // Apply sorting based on sort parameter
        $sort = $request->get('sort', 'rating');
        
        if ($sort === 'price_low') {
            // Sort by minimum room price from inventory (ascending)
            $availableHotels = $availableHotels->sortBy(function($hotel) {
                $prices = [];
                if ($hotel->inventory) {
                    foreach ($hotel->inventory as $variants) {
                        foreach ($variants as $variant) {
                            $prices[] = $variant['price'];
                        }
                    }
                }
                return empty($prices) ? 999999 : min($prices);
            });
        } elseif ($sort === 'price_high') {
            // Sort by maximum room price from inventory (descending)
            $availableHotels = $availableHotels->sortByDesc(function($hotel) {
                $prices = [];
                if ($hotel->inventory) {
                    foreach ($hotel->inventory as $variants) {
                        foreach ($variants as $variant) {
                            $prices[] = $variant['price'];
                        }
                    }
                }
                return empty($prices) ? 0 : max($prices);
            });
        } elseif ($sort === 'name') {
            // Sort by hotel name (A-Z)
            $availableHotels = $availableHotels->sortBy('name');
        } else {
            // Default: Sort by rating (descending)
            $availableHotels = $availableHotels->sortByDesc(function($hotel) {
                if ($hotel->reviews->isEmpty()) {
                    return $hotel->star_rating ?? 0;
                }
                return $hotel->reviews->avg('overall_rating') ?? $hotel->star_rating ?? 0;
            });
        }

        $availableHotels = $availableHotels->values();

        // Get dzongkhag name for display
        $dzongkhagName = null;
        if (!empty($validated['dzongkhag_id'])) {
            $dzongkhag = Dzongkhag::find($validated['dzongkhag_id']);
            $dzongkhagName = $dzongkhag ? $dzongkhag->name : null;
        }

        // Calculate number of nights
        $nights = Carbon::parse($validated['check_out'])->diffInDays(Carbon::parse($validated['check_in']));

        return view('guest.search-results-enhanced', compact('availableHotels', 'validated', 'dzongkhagName', 'nights'));
    }

    /**
     * Show hotel details with aggregated room inventory
     */
    public function showHotel($id, Request $request)
    {
        $inventoryService = new RoomInventoryService();
        $promotionService = new PromotionService();

        $hotel = Hotel::where('status', 'approved')
            ->with([
                'reviews' => function($q) {
                    $q->approved()->orderBy('review_date', 'desc');
                },
                'promotions' => function($q) {
                    $q->where('is_active', true)
                      ->whereDate('start_date', '<=', now())
                      ->whereDate('end_date', '>=', now())
                      ->orderBy('created_at', 'desc');
                }
            ])
            ->findOrFail($id);

        $checkIn = $request->query('check_in');
        $checkOut = $request->query('check_out');
        $adults = $request->query('adults', 1);
        $children = $request->query('children', 0);
        $guests = $adults + $children;
        $numRooms = $request->query('rooms', 1);

        // Get inventory grouped by (room_type + price) - NO individual room numbers shown
        $inventory = $inventoryService->getAvailableInventory($hotel->id);

        return view('guest.hotel-details', compact(
            'hotel', 
            'inventory',
            'checkIn', 
            'checkOut', 
            'adults', 
            'children', 
            'guests', 
            'numRooms'
        ));
    }

    /**
     * Show booking form
     * Now accepts room_type and price instead of room_id
     * The actual room will be assigned during booking confirmation
     */
    public function showBookingForm(Request $request)
    {
        $inventoryService = new RoomInventoryService();
        $promotionService = new PromotionService();

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after:check_in',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'guests' => 'nullable|integer|min:1',
            'num_rooms' => 'required|integer|min:1',
        ]);

        // Calculate total guests
        if (isset($validated['adults'])) {
            $validated['guests'] = $validated['adults'] + ($validated['children'] ?? 0);
        } elseif (!isset($validated['guests'])) {
            $validated['guests'] = 1;
        }

        $hotel = Hotel::findOrFail($validated['hotel_id']);

        // Check availability for this room_type + price combination
        $available = $inventoryService->getAvailabilityCount(
            $hotel->id,
            $validated['room_type'],
            $validated['price']
        );

        if ($available <= 0) {
            return redirect()->back()->with('error', 'This room is no longer available. Please select another option.');
        }

        // Calculate total price
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $originalPrice = floatval($validated['price']) * $nights * $validated['num_rooms'];

        // Apply active promotion if available (by room_type)
        // For now, find highest promotion by room_type
        $promotion = Promotion::where('hotel_id', $hotel->id)
            ->where(function($q) use ($validated) {
                // Either room_type specific or hotel-wide
                $q->where('room_type', $validated['room_type'])
                  ->orWhereNull('room_type');
            })
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('updated_at', 'desc')
            ->first();

        $pricingInfo = $promotionService->applyPromotion($promotion, $originalPrice);
        $totalPrice = $pricingInfo['final_price'];

        return view('guest.booking-form', compact(
            'hotel',
            'validated',
            'nights',
            'originalPrice',
            'totalPrice',
            'pricingInfo',
            'available'
        ));
    }

    /**
     * Confirm booking
     * NEW: Accepts room_type + price, automatically finds and assigns actual room
     */
    public function confirmBooking(Request $request)
    {
        $inventoryService = new RoomInventoryService();
        $promotionService = new PromotionService();

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'num_rooms' => 'required|integer|min:1',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_mobile' => ['required', new BhutanPhoneNumber()],
            'payment_method' => 'required|in:pay_now,pay_at_hotel',
            'payment_screenshot' => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $hotel = Hotel::findOrFail($validated['hotel_id']);

        // CRITICAL: Find an actual available room matching the selected (room_type + price)
        // This is where aggregated inventory converts to actual room assignment
        $assignedRoom = $inventoryService->findAvailableRoom(
            $hotel->id,
            $validated['room_type'],
            $validated['price']
        );

        if (!$assignedRoom) {
            return redirect()->back()->with('error', 'Sorry, this room is no longer available. Please select another option.');
        }

        // Calculate original price
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $originalPrice = floatval($validated['price']) * $nights * $validated['num_rooms'];

        // Apply active promotion if available (by room_type)
        $promotion = Promotion::where('hotel_id', $hotel->id)
            ->where(function($q) use ($validated) {
                // Either room_type specific or hotel-wide
                $q->where('room_type', $validated['room_type'])
                  ->orWhereNull('room_type');
            })
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('updated_at', 'desc')
            ->first();

        $pricingInfo = $promotionService->applyPromotion($promotion, $originalPrice);

        // Generate unique booking ID
        $bookingId = Booking::generateBookingId();

        // Handle payment screenshot upload
        $paymentScreenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $filename = 'payment_' . $bookingId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $paymentScreenshotPath = $file->storeAs('payment_screenshots', $filename, 'public');
        }

        // Map payment method to database format
        $paymentMethodDb = $validated['payment_method'] === 'pay_now' ? 'ONLINE' : 'PAY_AT_HOTEL';

        // Create or find guest
        $guest = Guest::updateOrCreate(
            ['email' => $validated['guest_email']],
            [
                'name' => $validated['guest_name'],
                'mobile' => $validated['guest_mobile'],
                'status' => 'active',
            ]
        );

        // Create booking with the assigned room
        $booking = Booking::create([
            'booking_id' => $bookingId,
            'hotel_id' => $validated['hotel_id'],
            'room_id' => $assignedRoom->id,  // CRITICAL: Assign the actual room
            'guest_id' => $guest->id,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_mobile'],
            'check_in_date' => $validated['check_in'],
            'check_out_date' => $validated['check_out'],
            'num_guests' => 1,
            'num_rooms' => $validated['num_rooms'],
            'original_price' => $pricingInfo['original_price'],
            'discount_applied' => $pricingInfo['discount_applied'],
            'total_price' => $pricingInfo['final_price'],
            'promotion_id' => $pricingInfo['promotion_id'],
            'payment_method' => $paymentMethodDb,
            'payment_screenshot' => $paymentScreenshotPath,
            'payment_status' => $paymentScreenshotPath ? 'PENDING' : 'PENDING',
            'status' => 'CONFIRMED',
            'special_requests' => $validated['special_requests'] ?? null,
        ]);

        // IMPORTANT: Room status remains AVAILABLE until check-in
        // Status update only happens when reception staff checks in the guest
        // This ensures single source of truth: room.status='AVAILABLE' = available for booking

        Log::info('Booking confirmed - room reserved but not checked in yet', [
            'booking_id' => $bookingId,
            'room_id' => $assignedRoom->id,
            'room_number' => $assignedRoom->room_number,
            'room_type' => $assignedRoom->room_type,
            'booking_status' => 'CONFIRMED',
            'room_status' => 'AVAILABLE (until check-in)',
        ]);

        // Load relationships for email
        $booking->load(['hotel.owner', 'room', 'promotion']);

        // Send confirmation email to guest
        $emailSent = false;
        try {
            Mail::to($booking->guest_email)->send(new BookingConfirmation($booking));
            $emailSent = true;
            Log::info('Booking confirmation email sent successfully to guest', [
                'booking_id' => $bookingId,
                'guest_email' => $booking->guest_email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email to guest', [
                'booking_id' => $bookingId,
                'guest_email' => $booking->guest_email,
                'error' => $e->getMessage()
            ]);
        }

        // Send notification email to hotel owner
        try {
            if ($booking->hotel && $booking->hotel->owner && $booking->hotel->owner->email) {
                Mail::to($booking->hotel->owner->email)->send(new NewBookingNotification($booking));
                Log::info('New booking notification email sent to hotel owner', [
                    'booking_id' => $bookingId,
                    'hotel_id' => $booking->hotel_id,
                    'owner_email' => $booking->hotel->owner->email
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send new booking notification to hotel owner', [
                'booking_id' => $bookingId,
                'hotel_id' => $booking->hotel_id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }

        // Show confirmation page with email status
        return redirect()->route('guest.booking.confirmation', ['booking_id' => $bookingId])
            ->with('email_sent', $emailSent);
    }

    /**
     * Show booking confirmation
     */
    public function showConfirmation($bookingId)
    {
        $booking = Booking::where('booking_id', $bookingId)
            ->with(['hotel', 'room'])
            ->firstOrFail();

        return view('guest.booking-confirmation', compact('booking'));
    }

    /**
     * Show manage booking form
     */
    public function showManageBookingForm()
    {
        return view('guest.manage-booking-form');
    }

    /**
     * View booking details
     */
    public function viewBooking(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string',
            'identifier' => 'required|string', // Email or Phone
        ]);

        $booking = Booking::where('booking_id', $validated['booking_id'])
            ->where(function($query) use ($validated) {
                $query->where('guest_email', $validated['identifier'])
                      ->orWhere('guest_phone', $validated['identifier']);
            })
            ->with(['hotel', 'room'])
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found. Please check your Booking ID and Email/Phone.');
        }

        return view('guest.view-booking', compact('booking', 'validated'));
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'identifier' => 'required|string', // Email or Phone
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('booking_id', $bookingId)
            ->where(function($query) use ($validated) {
                $query->where('guest_email', $validated['identifier'])
                      ->orWhere('guest_phone', $validated['identifier']);
            })
            ->firstOrFail();

        // Check if booking can be cancelled
        if (in_array($booking->status, ['CANCELLED', 'CHECKED_OUT'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        // Update booking status
        $booking->update([
            'status' => 'CANCELLED',
            'cancelled_at' => now(),
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        // Calculate refund (simple logic - can be enhanced)
        if ($booking->payment_status === 'PAID') {
            $daysUntilCheckIn = Carbon::parse($booking->check_in_date)->diffInDays(now(), false);
            
            if ($daysUntilCheckIn > 7) {
                $refundAmount = $booking->total_price; // Full refund
            } elseif ($daysUntilCheckIn > 3) {
                $refundAmount = $booking->total_price * 0.5; // 50% refund
            } else {
                $refundAmount = 0; // No refund
            }

            $booking->update([
                'refund_amount' => $refundAmount,
                'payment_status' => 'REFUNDED',
            ]);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Show review form for a booking (unauthenticated)
     */
    public function showReviewForm($bookingId, Request $request)
    {
        // Verify booking_id AND email/phone in query params
        $identifier = $request->query('identifier');
        
        if (!$bookingId || !$identifier) {
            return redirect()->route('guest.manage-booking')
                ->with('error', 'Missing booking information. Please search again.');
        }

        // Find booking
        $booking = Booking::where('booking_id', $bookingId)
            ->where(function($query) use ($identifier) {
                $query->where('guest_email', $identifier)
                      ->orWhere('guest_phone', $identifier);
            })
            ->with(['hotel', 'room'])
            ->firstOrFail();

        // Check if booking is eligible for review
        $checkOutDate = Carbon::parse($booking->check_out_date);
        $isAfterCheckOut = Carbon::now()->greaterThanOrEqualTo($checkOutDate);
        $isCompleted = $booking->status === 'COMPLETED' || $booking->status === 'CHECKED_OUT';

        if (!($isCompleted || $isAfterCheckOut)) {
            return redirect()->back()
                ->with('error', 'You can only review after your check-out date.');
        }

        // Check if review already exists
        $existingReview = $booking->review()->first();
        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Review already submitted for this booking.');
        }

        return view('guest.review-form', compact('booking', 'identifier'));
    }

    /**
     * Submit review for a booking (unauthenticated)
     */
    public function submitReview(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|string',
            'identifier' => 'required|string', // Email or Phone
            'overall_rating' => 'required|integer|between:1,10',
            'cleanliness_rating' => 'required|integer|between:1,10',
            'staff_rating' => 'required|integer|between:1,10',
            'comfort_rating' => 'required|integer|between:1,10',
            'facilities_rating' => 'required|integer|between:1,10',
            'value_for_money_rating' => 'required|integer|between:1,10',
            'location_rating' => 'required|integer|between:1,10',
            'comment' => 'nullable|string|max:2000',
        ]);

        // Step 1: Verify booking exists and email/phone matches
        $booking = Booking::where('booking_id', $validated['booking_id'])
            ->where(function($query) use ($validated) {
                $query->where('guest_email', $validated['identifier'])
                      ->orWhere('guest_phone', $validated['identifier']);
            })
            ->firstOrFail();

        // Step 2: Verify booking is completed or check-out date has passed
        $checkOutDate = Carbon::parse($booking->check_out_date);
        $isAfterCheckOut = Carbon::now()->greaterThanOrEqualTo($checkOutDate);
        $isCompleted = $booking->status === 'COMPLETED' || $booking->status === 'CHECKED_OUT';

        if (!($isCompleted || $isAfterCheckOut)) {
            return back()->with('error', 'You can only submit a review after your check-out date.');
        }

        // Step 3: Verify no existing review for this booking
        if ($booking->review()->exists()) {
            return back()->with('error', 'Review already submitted for this booking.');
        }

        // Step 4: Sanitize comment
        $comment = $validated['comment'] ? strip_tags(trim($validated['comment'])) : null;

        // Step 5: Create review
        $review = Review::create([
            'booking_id' => $booking->id,
            'hotel_id' => $booking->hotel_id,
            'guest_id' => $booking->user_id, // May be null if no user account
            'guest_name' => $booking->guest_name,
            'guest_email' => $booking->guest_email,
            'overall_rating' => (int)$validated['overall_rating'],
            'cleanliness_rating' => (int)$validated['cleanliness_rating'],
            'staff_rating' => (int)$validated['staff_rating'],
            'comfort_rating' => (int)$validated['comfort_rating'],
            'facilities_rating' => (int)$validated['facilities_rating'],
            'value_for_money_rating' => (int)$validated['value_for_money_rating'],
            'location_rating' => (int)$validated['location_rating'],
            'comment' => $comment,
            'review_date' => Carbon::now()->toDateString(),
            'status' => 'APPROVED', // Auto-approve for now
        ]);

        return redirect()->route('guest.booking.view', [
            'booking_id' => $validated['booking_id'],
            'identifier' => $validated['identifier'],
        ])->with('success', 'Thank you for your review! Your feedback helps us improve.');
    }
}
