<?php

namespace App\Traits;

use App\Services\NotificationService;

trait ManagesNotifications
{
    /**
     * Boot the ManagesNotifications trait
     */
    public static function bootManagesNotifications()
    {
        // Create notification when booking is created
        static::created(function ($booking) {
            if ($booking instanceof \App\Models\Booking) {
                NotificationService::notifyNewBooking($booking);
            }
        });

        // Handle status changes  
        static::updated(function ($booking) {
            if ($booking instanceof \App\Models\Booking) {
                // Check if status changed to pending_checkin and create upcoming checkin notification
                if ($booking->isDirty('status') && $booking->status === 'pending_checkin') {
                    NotificationService::notifyUpcomingCheckIn($booking);
                }
                
                // Check if status changed to checked_in
                if ($booking->isDirty('status') && $booking->status === 'checked_in') {
                    NotificationService::notifyCheckIn($booking);
                }
                
                // Check if status changed to checked_out
                if ($booking->isDirty('status') && $booking->status === 'checked_out') {
                    NotificationService::notifyCheckOut($booking);
                }
                
                // Check if payment status changed to paid
                if ($booking->isDirty('payment_status') && $booking->payment_status === 'paid') {
                    NotificationService::notifyPaymentReceived($booking);
                }
            }
        });

        // Handle booking cancellation
        static::deleting(function ($booking) {
            if ($booking instanceof \App\Models\Booking && !$booking->forceDeleting) {
                // Soft delete, so we should record the cancellation
                if ($booking->cancelled_at) {
                    NotificationService::notifyBookingCancellation($booking);
                }
            }
        });
    }
}
