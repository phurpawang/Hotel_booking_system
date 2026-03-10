<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingCommission;
use App\Models\HotelPayout;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CommissionService
{
    /**
     * Create commission record for a booking
     */
    public function createBookingCommission(Booking $booking, $paymentMethodType = null)
    {
        $room = $booking->room;
        
        // Calculate nights
        $nights = $booking->check_in_date->diffInDays($booking->check_out_date);
        
        // Get base price and commission rate from room
        $basePricePerNight = $room->base_price ?? $room->price_per_night;
        $commissionRate = $room->commission_rate ?? 10.00;
        
        // Calculate total amounts
        $baseAmount = $basePricePerNight * $nights * ($booking->num_rooms ?? 1);
        $commissionAmount = round(($baseAmount * $commissionRate) / 100, 2);
        $finalAmount = round($baseAmount + $commissionAmount, 2);

        // Determine payment method type based on actual payment method
        // ONLINE = Platform collects payment (pay_online)
        // CASH/CARD/BANK_TRANSFER = Hotel collects payment (pay_at_hotel)
        if ($paymentMethodType === null) {
            $paymentMethodType = ($booking->payment_method === 'ONLINE') ? 'pay_online' : 'pay_at_hotel';
        }

        // Create commission record
        $commission = BookingCommission::create([
            'booking_id' => $booking->id,
            'hotel_id' => $booking->hotel_id,
            'room_id' => $booking->room_id,
            'base_amount' => $baseAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'final_amount' => $finalAmount,
            'payment_method' => $paymentMethodType,
            'commission_status' => 'pending',
            'booking_date' => $booking->created_at->toDateString(),
            'check_in_date' => $booking->check_in_date,
            'check_out_date' => $booking->check_out_date,
        ]);

        // Update booking with commission info
        $booking->update([
            'base_price' => $baseAmount,
            'commission_amount' => $commissionAmount,
            'total_price' => $finalAmount,
            'payment_method_type' => $paymentMethodType,
        ]);

        return $commission;
    }

    /**
     * Generate monthly payout report for a hotel
     */
    public function generateMonthlyPayout($hotelId, $year, $month)
    {
        // Get all commissions for the month
        $commissions = BookingCommission::forHotel($hotelId)
            ->forMonth($year, $month)
            ->get();

        if ($commissions->isEmpty()) {
            return null;
        }

        // Calculate totals
        $totalBookings = $commissions->count();
        $totalGuestPayments = $commissions->sum('final_amount');
        $totalCommission = $commissions->sum('commission_amount');
        $hotelPayoutAmount = $totalGuestPayments - $totalCommission;

        // Separate by payment method (use final_amount to show what was actually collected)
        $payOnlineAmount = $commissions->where('payment_method', 'pay_online')->sum('final_amount');
        $payAtHotelAmount = $commissions->where('payment_method', 'pay_at_hotel')->sum('final_amount');

        // Create or update payout record
        $payout = HotelPayout::updateOrCreate(
            [
                'hotel_id' => $hotelId,
                'year' => $year,
                'month' => $month,
            ],
            [
                'total_bookings' => $totalBookings,
                'total_guest_payments' => $totalGuestPayments,
                'total_commission' => $totalCommission,
                'hotel_payout_amount' => $hotelPayoutAmount,
                'pay_online_amount' => $payOnlineAmount,
                'pay_at_hotel_amount' => $payAtHotelAmount,
            ]
        );

        return $payout;
    }

    /**
     * Generate payouts for all hotels for a specific month
     */
    public function generateAllHotelPayouts($year, $month)
    {
        $hotelIds = BookingCommission::forMonth($year, $month)
            ->distinct()
            ->pluck('hotel_id');

        $payouts = [];
        foreach ($hotelIds as $hotelId) {
            $payout = $this->generateMonthlyPayout($hotelId, $year, $month);
            if ($payout) {
                $payouts[] = $payout;
            }
        }

        return $payouts;
    }

    /**
     * Mark payout as paid and update commission status
     */
    public function markPayoutAsPaid(HotelPayout $payout, $userId, $reference = null, $notes = null)
    {
        DB::beginTransaction();
        try {
            // Mark payout as paid
            $payout->markAsPaid($userId, $reference, $notes);

            // Mark all related commissions as paid
            BookingCommission::forHotel($payout->hotel_id)
                ->forMonth($payout->year, $payout->month)
                ->update(['commission_status' => 'paid']);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get platform statistics for a period
     */
    public function getPlatformStatistics($year, $month = null)
    {
        $query = BookingCommission::whereYear('booking_date', $year);
        
        if ($month) {
            $query->whereMonth('booking_date', $month);
        }

        $commissions = $query->get();

        // Hotel payouts = only for ONLINE payments (platform collected, owes hotel 90%)
        // For Cash/Card/Bank Transfer = hotel collected directly, owes platform 10%
        $onlinePayments = $commissions->where('payment_method', 'pay_online');
        $hotelPayments = $commissions->where('payment_method', 'pay_at_hotel');

        return [
            'total_bookings' => $commissions->count(),
            'total_guest_payments' => $commissions->sum('final_amount'),
            'total_revenue' => $commissions->sum('final_amount'), // Alias for backward compatibility
            'total_commission' => $commissions->sum('commission_amount'),
            'total_hotel_payout' => $onlinePayments->sum('base_amount'), // Only online payments
            'hotel_owes_commission' => $hotelPayments->sum('commission_amount'), // Hotels owe this to platform
            'pay_online_bookings' => $onlinePayments->count(),
            'pay_at_hotel_bookings' => $hotelPayments->count(),
            'pending_commission' => $commissions->where('commission_status', 'pending')->sum('commission_amount'),
            'paid_commission' => $commissions->where('commission_status', 'paid')->sum('commission_amount'),
        ];
    }

    /**
     * Get hotel revenue summary
     */
    public function getHotelRevenueSummary($hotelId, $year = null, $month = null)
    {
        $query = BookingCommission::forHotel($hotelId);
        
        if ($year) {
            $query->whereYear('booking_date', $year);
            if ($month) {
                $query->whereMonth('booking_date', $month);
            }
        }

        $commissions = $query->get();

        // Separate online vs hotel payments
        $onlinePayments = $commissions->where('payment_method', 'pay_online');
        $hotelPayments = $commissions->where('payment_method', 'pay_at_hotel');

        return [
            'total_bookings' => $commissions->count(),
            'gross_revenue' => $commissions->sum('final_amount'),
            'total_commission' => $commissions->sum('commission_amount'),
            'net_revenue' => $commissions->sum('base_amount'),
            'pending_payout' => $commissions->where('commission_status', 'pending')->sum('base_amount'),
            'received_payout' => $commissions->where('commission_status', 'paid')->sum('base_amount'),
            
            // Payment method breakdown
            'pay_online_amount' => $onlinePayments->sum('final_amount'), // Platform collected
            'pay_online_payout' => $onlinePayments->sum('base_amount'), // Hotel gets 90%
            'pay_at_hotel_amount' => $hotelPayments->sum('final_amount'), // Hotel collected
            'pay_at_hotel_commission' => $hotelPayments->sum('commission_amount'), // Hotel owes 10%
            
            'online_bookings' => $onlinePayments->count(),
            'hotel_bookings' => $hotelPayments->count(),
        ];
    }
}
