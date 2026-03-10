<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'mobile',
        'address',
        'profile_photo',
        'id_type',
        'id_number',
        'nationality',
        'date_of_birth',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get all bookings for this guest
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the last completed booking
     */
    public function lastCompletedBooking()
    {
        return $this->hasOne(Booking::class)
            ->where('status', 'CHECKED_OUT')
            ->latest('actual_check_out');
    }

    /**
     * Get total bookings count
     */
    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }

    /**
     * Get last visit date
     */
    public function getLastVisitDateAttribute()
    {
        $lastBooking = $this->lastCompletedBooking;
        return $lastBooking ? $lastBooking->actual_check_out : null;
    }

    /**
     * Scope to search guests
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('mobile', 'LIKE', "%{$search}%");
        });
    }
}
