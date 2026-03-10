<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HotelPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'year',
        'month',
        'total_bookings',
        'total_guest_payments',
        'total_commission',
        'hotel_payout_amount',
        'pay_online_amount',
        'pay_at_hotel_amount',
        'payout_status',
        'payout_date',
        'payout_notes',
        'payout_reference',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'total_guest_payments' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'hotel_payout_amount' => 'decimal:2',
        'pay_online_amount' => 'decimal:2',
        'pay_at_hotel_amount' => 'decimal:2',
        'payout_date' => 'date',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the hotel that owns the payout
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the user who processed the payout
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the month name
     */
    public function getMonthNameAttribute()
    {
        return Carbon::create($this->year, $this->month, 1)->format('F');
    }

    /**
     * Get the period (e.g., "March 2024")
     */
    public function getPeriodAttribute()
    {
        return "{$this->month_name} {$this->year}";
    }

    /**
     * Scope for pending payouts
     */
    public function scopePending($query)
    {
        return $query->where('payout_status', 'pending');
    }

    /**
     * Scope for paid payouts
     */
    public function scopePaid($query)
    {
        return $query->where('payout_status', 'paid');
    }

    /**
     * Scope for a specific hotel
     */
    public function scopeForHotel($query, $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    /**
     * Scope for a specific year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Mark payout as paid
     */
    public function markAsPaid($userId, $reference = null, $notes = null)
    {
        $this->update([
            'payout_status' => 'paid',
            'payout_date' => now(),
            'payout_reference' => $reference,
            'payout_notes' => $notes,
            'processed_by' => $userId,
            'processed_at' => now(),
        ]);
    }

    /**
     * Get related commission records
     */
    public function commissions()
    {
        return BookingCommission::forHotel($this->hotel_id)
                                ->forMonth($this->year, $this->month);
    }
}
