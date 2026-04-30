<?php

namespace App\Http\Controllers\Manager;

use App\Models\HotelInquiry;
use App\Mail\InquiryReply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    /**
     * Display a listing of the inquiries for the authenticated manager's hotel
     */
    public function index()
    {
        $hotel = auth()->user()->hotel;
        
        if (!$hotel) {
            abort(403, 'No hotel associated with your account.');
        }
        
        // Get paginated inquiries
        $inquiries = $hotel->inquiries()->latest()->paginate(10);
        
        // Get statistics from all inquiries
        $totalInquiries = $hotel->inquiries()->count();
        $pendingCount = $hotel->inquiries()->where('status', 'PENDING')->count();
        $answeredCount = $hotel->inquiries()->where('status', 'ANSWERED')->count();

        return view('manager.inquiries.index', compact('hotel', 'inquiries', 'totalInquiries', 'pendingCount', 'answeredCount'));
    }

    /**
     * Display the specified inquiry
     */
    public function show($id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated manager's hotel
        if ($inquiry->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $hotel = auth()->user()->hotel;
        return view('manager.inquiries.show', compact('hotel', 'inquiry'));
    }

    /**
     * Update the inquiry with a reply
     */
    public function update(Request $request, $id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated manager's hotel
        if ($inquiry->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'reply' => 'required|string|min:5|max:1000',
            'status' => 'required|in:PENDING,ANSWERED,CLOSED'
        ]);

        $inquiry->update([
            'reply' => $validated['reply'],
            'status' => $validated['status']
        ]);

        // Send email to guest if reply was just added and status is ANSWERED
        if ($validated['status'] === 'ANSWERED') {
            try {
                $hotel = auth()->user()->hotel;
                Mail::to($inquiry->guest_email)->send(new InquiryReply($inquiry, $hotel->name));
            } catch (\Exception $e) {
                Log::error('Failed to send inquiry reply email: ' . $e->getMessage());
            }
        }

        return redirect()->route('manager.inquiries.show', $inquiry->id)
                        ->with('success', 'Your reply has been sent successfully!');
    }

    /**
     * Delete an inquiry
     */
    public function destroy($id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated manager's hotel
        if ($inquiry->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $inquiry->delete();

        return redirect()->route('manager.inquiries.index')
                        ->with('success', 'Inquiry deleted successfully!');
    }
}
