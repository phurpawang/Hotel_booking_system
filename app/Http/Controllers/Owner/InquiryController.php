<?php

namespace App\Http\Controllers\Owner;

use App\Models\HotelInquiry;
use App\Mail\InquiryReply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    /**
     * Display a listing of the inquiries for the authenticated owner's hotel
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

        return view('owner.inquiries.index', compact('hotel', 'inquiries', 'totalInquiries', 'pendingCount', 'answeredCount'));
    }

    /**
     * Display the specified inquiry
     */
    public function show($id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated owner's hotel
        if ($inquiry->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $hotel = auth()->user()->hotel;
        return view('owner.inquiries.show', compact('hotel', 'inquiry'));
    }

    /**
     * Submit an answer to an inquiry
     */
    public function answer(Request $request, $id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated owner's hotel
        if ($inquiry->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'answer' => 'required|string|min:5|max:5000',
        ]);

        // Update the inquiry with the answer and mark as answered
        $inquiry->update([
            'answer' => $validated['answer'],
            'answered_at' => now(),
            'status' => 'ANSWERED'
        ]);

        // Send email to guest
        try {
            $hotel = auth()->user()->hotel;
            Mail::to($inquiry->guest_email)->send(new InquiryReply($inquiry, $hotel->name));
        } catch (\Exception $e) {
            Log::error('Failed to send inquiry reply email: ' . $e->getMessage());
        }

        return redirect()->route('owner.inquiries.show', $inquiry->id)
                        ->with('success', 'Your answer has been sent successfully!');
    }

    /**
     * Update the inquiry with a reply
     */
    public function update(Request $request, $id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated owner's hotel
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

        return redirect()->route('owner.inquiries.show', $inquiry->id)
                        ->with('success', 'Your reply has been sent successfully!');
    }

    /**
     * Close an inquiry
     */
    public function close($id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated owner's hotel
        if ($inquiry->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $inquiry->update([
            'status' => 'CLOSED'
        ]);

        return redirect()->route('owner.inquiries.show', $inquiry->id)
                        ->with('success', 'Inquiry has been closed!');
    }

    /**
     * Delete an inquiry
     */
    public function destroy($id)
    {
        $inquiry = HotelInquiry::findOrFail($id);
        
        // Check if the inquiry belongs to the authenticated owner's hotel
        if ($inquiry->hotel_id !== auth()->user()->hotel_id) {
            abort(403, 'Unauthorized');
        }

        $inquiry->delete();

        return redirect()->route('owner.inquiries.index')
                        ->with('success', 'Inquiry deleted successfully!');
    }
}
