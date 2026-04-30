<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification
     */
    public static function create(array $data): ?Notification
    {
        try {
            return Notification::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new booking notification
     */
    public static function notifyNewBooking(Booking $booking): void
    {
        $hotel = $booking->hotel;
        
        // Notify owner about new booking
        if ($hotel) {
            $owner = $hotel->users()->where('role', 'OWNER')->first();
            if ($owner) {
                static::create([
                    'user_id' => $owner->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'new_booking',
                    'target_role' => 'owner',
                    'title' => 'New Booking Received',
                    'message' => "New booking from {$booking->guest_name} (Room {$booking->room_id}) - Check-in: {$booking->check_in_date}",
                    'link' => route('owner.reservations.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'check_in' => $booking->check_in_date,
                        'check_out' => $booking->check_out_date,
                    ],
                ]);
            }

            // Notify manager about new booking
            $manager = $hotel->users()->where('role', 'MANAGER')->first();
            if ($manager) {
                static::create([
                    'user_id' => $manager->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'new_booking',
                    'target_role' => 'manager',
                    'title' => 'New Booking Alert',
                    'message' => "New booking from {$booking->guest_name} - Check-in: {$booking->check_in_date}",
                    'link' => route('manager.reservations.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'check_in' => $booking->check_in_date,
                    ],
                ]);
            }

            // Notify reception about new booking
            $reception = $hotel->users()->where('role', 'RECEPTION')->first();
            if ($reception) {
                static::create([
                    'user_id' => $reception->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'new_booking',
                    'target_role' => 'reception',
                    'title' => 'New Booking Notification',
                    'message' => "New booking for {$booking->guest_name} - Check-in: {$booking->check_in_date}",
                    'link' => route('reception.reservations.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'check_in' => $booking->check_in_date,
                    ],
                ]);
            }
        }
    }

    /**
     * Create a booking cancellation notification
     */
    public static function notifyBookingCancellation(Booking $booking): void
    {
        $hotel = $booking->hotel;
        
        if ($hotel) {
            // Notify owner
            $owner = $hotel->users()->where('role', 'OWNER')->first();
            if ($owner) {
                static::create([
                    'user_id' => $owner->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'booking_cancelled',
                    'target_role' => 'owner',
                    'title' => 'Booking Cancelled',
                    'message' => "Booking from {$booking->guest_name} has been cancelled (Check-in was: {$booking->check_in_date})",
                    'link' => route('owner.reservations.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'reason' => $booking->cancellation_reason,
                    ],
                ]);
            }

            // Notify manager
            $manager = $hotel->users()->where('role', 'MANAGER')->first();
            if ($manager) {
                static::create([
                    'user_id' => $manager->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'booking_cancelled',
                    'target_role' => 'manager',
                    'title' => 'Booking Cancelled',
                    'message' => "Booking from {$booking->guest_name} has been cancelled",
                    'link' => route('manager.reservations.show', $booking->id),
                ]);
            }
        }
    }

    /**
     * Create a check-in notification
     */
    public static function notifyCheckIn(Booking $booking): void
    {
        $hotel = $booking->hotel;
        
        if ($hotel) {
            $reception = $hotel->users()->where('role', 'RECEPTION')->first();
            if ($reception) {
                static::create([
                    'user_id' => $reception->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'check_in',
                    'target_role' => 'reception',
                    'title' => 'Guest Check-In',
                    'message' => "{$booking->guest_name} has checked in to Room {$booking->room_id}",
                    'link' => route('reception.reservations.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'room_id' => $booking->room_id,
                    ],
                ]);
            }
        }
    }

    /**
     * Create a check-out notification
     */
    public static function notifyCheckOut(Booking $booking): void
    {
        $hotel = $booking->hotel;
        
        if ($hotel) {
            $reception = $hotel->users()->where('role', 'RECEPTION')->first();
            if ($reception) {
                static::create([
                    'user_id' => $reception->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'check_out',
                    'target_role' => 'reception',
                    'title' => 'Guest Check-Out',
                    'message' => "{$booking->guest_name} has checked out from Room {$booking->room_id}",
                    'link' => route('reception.reservations.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'room_id' => $booking->room_id,
                    ],
                ]);
            }
        }
    }

    /**
     * Create a new review notification
     */
    public static function notifyNewReview(Review $review): void
    {
        $hotel = $review->hotel;
        
        if ($hotel) {
            // Notify owner
            $owner = $hotel->users()->where('role', 'OWNER')->first();
            if ($owner) {
                static::create([
                    'user_id' => $owner->id,
                    'hotel_id' => $hotel->id,
                    'review_id' => $review->id,
                    'type' => 'new_review',
                    'target_role' => 'owner',
                    'title' => 'New Review Received',
                    'message' => "{$review->guest_name} left a {$review->rating}-star review: \"{$review->comment}\"",
                    'link' => route('owner.reviews.show', $review->id),
                    'data' => [
                        'review_id' => $review->id,
                        'rating' => $review->rating,
                        'guest_name' => $review->guest_name,
                    ],
                ]);
            }

            // Notify manager
            $manager = $hotel->users()->where('role', 'MANAGER')->first();
            if ($manager) {
                static::create([
                    'user_id' => $manager->id,
                    'hotel_id' => $hotel->id,
                    'review_id' => $review->id,
                    'type' => 'new_review',
                    'target_role' => 'manager',
                    'title' => 'New Review Submitted',
                    'message' => "{$review->guest_name} left a {$review->rating}-star review",
                    'link' => route('owner.reviews.show', $review->id),
                ]);
            }
        }
    }

    /**
     * Create a payment received notification
     */
    public static function notifyPaymentReceived(Booking $booking): void
    {
        $hotel = $booking->hotel;
        
        if ($hotel) {
            $owner = $hotel->users()->where('role', 'OWNER')->first();
            if ($owner) {
                static::create([
                    'user_id' => $owner->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'payment_received',
                    'target_role' => 'owner',
                    'title' => 'Payment Received',
                    'message' => "Payment of Nu. " . number_format($booking->total_price, 2) . " received from {$booking->guest_name}",
                    'link' => route('owner.payments.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'amount' => $booking->total_price,
                        'guest_name' => $booking->guest_name,
                    ],
                ]);
            }
        }
    }

    /**
     * Create a payment issue notification
     */
    public static function notifyPaymentIssue(Booking $booking, $issue): void
    {
        $hotel = $booking->hotel;
        
        if ($hotel) {
            $owner = $hotel->users()->where('role', 'OWNER')->first();
            if ($owner) {
                static::create([
                    'user_id' => $owner->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'payment_issue',
                    'target_role' => 'owner',
                    'title' => 'Payment Issue Alert',
                    'message' => "Payment issue for booking from {$booking->guest_name}: {$issue}",
                    'link' => route('owner.payments.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'issue' => $issue,
                    ],
                ]);
            }
        }
    }

    /**
     * Create a guest question notification
     */
    public static function notifyGuestQuestion($hotelId, $guestName, $guestEmail, $inquiryId = null): void
    {
        $hotel = Hotel::find($hotelId);
        
        if ($hotel) {
            // Notify owner
            $owner = $hotel->users()->where('role', 'OWNER')->first();
            if ($owner) {
                static::create([
                    'user_id' => $owner->id,
                    'hotel_id' => $hotel->id,
                    'type' => 'guest_question',
                    'target_role' => 'owner',
                    'title' => 'New Guest Question',
                    'message' => "New question from {$guestName} ({$guestEmail})",
                    'link' => $inquiryId ? route('owner.inquiries.show', $inquiryId) : null,
                    'data' => [
                        'guest_name' => $guestName,
                        'guest_email' => $guestEmail,
                    ],
                ]);
            }

            // Notify manager
            $manager = $hotel->users()->where('role', 'MANAGER')->first();
            if ($manager) {
                static::create([
                    'user_id' => $manager->id,
                    'hotel_id' => $hotel->id,
                    'type' => 'guest_question',
                    'target_role' => 'manager',
                    'title' => 'New Guest Question',
                    'message' => "New question from {$guestName}",
                    'link' => $inquiryId ? route('manager.inquiries.show', $inquiryId) : null,
                ]);
            }
        }
    }

    /**
     * Create upcoming check-in notification
     */
    public static function notifyUpcomingCheckIn(Booking $booking): void
    {
        $hotel = $booking->hotel;
        
        if ($hotel) {
            $reception = $hotel->users()->where('role', 'RECEPTION')->first();
            if ($reception) {
                static::create([
                    'user_id' => $reception->id,
                    'hotel_id' => $hotel->id,
                    'booking_id' => $booking->id,
                    'type' => 'upcoming_check_in',
                    'target_role' => 'reception',
                    'title' => 'Upcoming Check-In',
                    'message' => "{$booking->guest_name} is checking in today (Room {$booking->room_id})",
                    'link' => route('reception.reservations.show', $booking->id),
                    'data' => [
                        'booking_id' => $booking->id,
                        'guest_name' => $booking->guest_name,
                        'check_in' => $booking->check_in_date,
                    ],
                ]);
            }
        }
    }

    /**
     * Get unread notification count for a user and role
     */
    public static function getUnreadCount(User $user, string $role): int
    {
        return Notification::where('user_id', $user->id)
            ->where('target_role', $role)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get paginated notifications for a user
     */
    public static function getUserNotifications(User $user, string $role, int $perPage = 15)
    {
        return Notification::where('user_id', $user->id)
            ->where('target_role', $role)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get unread notifications for a user
     */
    public static function getUnreadNotifications(User $user, string $role, int $limit = 10)
    {
        return Notification::where('user_id', $user->id)
            ->where('target_role', $role)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(User $user, string $role): int
    {
        return Notification::where('user_id', $user->id)
            ->where('target_role', $role)
            ->where('is_read', false)
            ->update(['is_read' => true, 'status' => 'read']);
    }

    /**
     * Delete a notification
     */
    public static function delete(Notification $notification): void
    {
        $notification->delete();
    }

    /**
     * Cleanup old read notifications (older than 30 days)
     */
    public static function cleanupOldNotifications($days = 30): int
    {
        return Notification::where('is_read', true)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }
}
