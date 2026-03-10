<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'document_type',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
