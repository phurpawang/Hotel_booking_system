<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'guest_name',
        'guest_email',
        'question',
        'reply',
        'answer',
        'answered_at',
        'status'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    /**
     * Get the hotel that owns the inquiry
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
