<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'owner_id',
        'name',
        'hotel_name',
        'property_type',
        'address',
        'dzongkhag',
        'dzongkhag_id',
        'phone',
        'mobile',
        'email',
        'description',
        'star_rating',
        'map_latitude',
        'map_longitude',
        'tourism_license_number',
        'issuing_authority',
        'license_issue_date',
        'license_expiry_date',
        'property_image',
        'pin_location',
        'license_document',
        'ownership_document',
        'status',
        'rejection_reason'
    ];

    /**
     * Get the users (staff) for the hotel
     */
    public function users()
    {
        return $this->hasMany(User::class, 'hotel_id', 'id');
    }

    /**
     * Get the owner of the hotel
     */
    public function owner()
    {
        return $this->hasOne(User::class, 'hotel_id', 'id')
                    ->where('role', 'owner');
    }

    /**
     * Get the managers of the hotel
     */
    public function managers()
    {
        return $this->hasMany(User::class, 'hotel_id', 'id')
                    ->where('role', 'manager');
    }

    /**
     * Get the receptionists of the hotel
     */
    public function receptionists()
    {
        return $this->hasMany(User::class, 'hotel_id', 'id')
                    ->where('role', 'RECEPTION');
    }

    /**
     * Get the rooms for the hotel
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id', 'id');
    }

    /**
     * Calculate total number of rooms (count of room records)
     */
    public function totalRooms()
    {
        return $this->rooms()->count();
    }

    /**
     * Get direct bookings for the hotel
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'hotel_id', 'id');
    }

    /**
     * Get messages for the hotel
     */
    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class, 'hotel_id', 'id');
    }

    /**
     * Get commission records for the hotel
     */
    public function commissions()
    {
        return $this->hasMany(BookingCommission::class, 'hotel_id', 'id');
    }

    /**
     * Get payout records for the hotel
     */
    public function payouts()
    {
        return $this->hasMany(HotelPayout::class, 'hotel_id', 'id');
    }

    /**
     * Get deregistration requests for the hotel
     */
    public function deregistrationRequests()
    {
        return $this->hasMany(HotelDeregistrationRequest::class, 'hotel_id', 'id');
    }

    /**
     * Get inquiries/questions from guests
     */
    public function inquiries()
    {
        return $this->hasMany(HotelInquiry::class, 'hotel_id', 'id');
    }

    /**
     * Get reviews for the hotel
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'hotel_id', 'id');
    }

    /**
     * Get promotions for the hotel
     */
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'hotel_id', 'id');
    }

    /**
     * Get the dzongkhag (location) of the hotel
     */
    public function dzongkhag()
    {
        return $this->belongsTo(Dzongkhag::class, 'dzongkhag_id');
    }

    /**
     * Backward-compatible alias for the dzongkhag relationship
     */
    public function dzongkhagRelation()
    {
        return $this->dzongkhag();
    }

    /**
     * Scope for approved hotels
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending hotels
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for rejected hotels
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if hotel is approved
     */
    public function isApproved()
    {
        return strtoupper($this->status) === 'APPROVED';
    }

    /**
     * Check if hotel is pending
     */
    public function isPending()
    {
        return strtoupper($this->status) === 'PENDING';
    }

    /**
     * Generate next hotel ID
     */
    public static function generateHotelId()
    {
        $lastHotel = self::orderBy('id', 'desc')->first();
        
        if (!$lastHotel) {
            return 'HTL000001';
        }
        
        $lastNumber = (int) substr($lastHotel->hotel_id, 3);
        $newNumber = $lastNumber + 1;
        
        return 'HTL' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}
