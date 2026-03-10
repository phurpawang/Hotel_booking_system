<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'guest_name',
        'guest_email',
        'message',
        'status'
    ];

    /**
     * Get the hotel that owns the message
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
