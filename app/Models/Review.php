<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'booking_id',
        'guest_name',
        'guest_email',
        'rating',
        'comment',
        'reply',
        'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
