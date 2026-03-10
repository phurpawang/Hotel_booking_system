<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'title',
        'description',
        'discount_percentage',
        'start_date',
        'end_date',
        'applicable_room_types',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'applicable_room_types' => 'array',
        'is_active' => 'boolean',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function isActive()
    {
        $now = now();
        return $this->is_active && 
               $this->start_date <= $now && 
               $this->end_date >= $now;
    }
}
