<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'user_id',
        'booking_id',
        'review_id',
        'type',
        'target_role',
        'title',
        'message',
        'link',
        'data',
        'is_read',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hotel that owns the notification
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the booking associated with the notification
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the review associated with the notification
     */
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true, 'status' => 'read']);
        return $this;
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false, 'status' => 'unread']);
        return $this;
    }

    /**
     * Check if notification is unread
     */
    public function isUnread()
    {
        return !$this->is_read;
    }

    /**
     * Scope: Get notifications for a specific role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('target_role', $role);
    }

    /**
     * Scope: Get notifications for a specific hotel
     */
    public function scopeByHotel($query, $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    /**
     * Scope: Get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
