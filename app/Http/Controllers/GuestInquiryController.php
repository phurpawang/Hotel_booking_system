<?php

namespace App\Http\Controllers;

use App\Models\HotelInquiry;
use App\Models\Hotel;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class GuestInquiryController extends Controller
{
    /**
     * Store a new inquiry from a guest about a hotel
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'question' => 'required|string|min:10|max:500'
        ]);

        $hotel = Hotel::findOrFail($id);

        $inquiry = HotelInquiry::create([
            'hotel_id' => $id,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'question' => $validated['question'],
            'status' => 'PENDING'
        ]);

        // Send notification to owner and manager about new guest question
        NotificationService::notifyGuestQuestion(
            $hotel->id,
            $validated['guest_name'],
            $validated['guest_email'],
            $inquiry->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Your question has been submitted successfully. The hotel will respond shortly.'
        ]);
    }
}

