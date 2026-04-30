<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'hotel_id',
        'room_id',
        'base_amount',
        'commission_rate',
        'commission_amount',
        'final_amount',
        'payment_method',
        'commission_status',
        'booking_date',
        'check_in_date',
        'check_out_date',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'booking_date' => 'date',
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    /**
     * Get the booking that owns the commission
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the hotel that owns the commission
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the room for the commission
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope for pending commissions
     */
    public function scopePending($query)
    {
        return $query->where('commission_status', 'pending');
    }

    /**
     * Scope for paid commissions
     */
    public function scopePaid($query)
    {
        return $query->where('commission_status', 'paid');
    }

    /**
     * Scope for a specific hotel
     */
    public function scopeForHotel($query, $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    /**
     * Scope for a specific month
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('booking_date', $year)
                     ->whereMonth('booking_date', $month);
    }

    /**
     * Scope for online payments
     */
    public function scopePayOnline($query)
    {
        return $query->where('payment_method', 'pay_online');
    }

    /**
     * Scope for pay at hotel (offline payments)
     */
    public function scopePayAtHotel($query)
    {
        return $query->where('payment_method', 'pay_at_hotel');
    }

    /**
     * Scope for pending payment method (offline/cash)
     */
    public function scopeOfflinePayments($query)
    {
        return $query->where('payment_method', 'pay_at_hotel');
    }

    /**
     * Scope for online payments
     */
    public function scopeOnlinePayments($query)
    {
        return $query->where('payment_method', 'pay_online');
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayAttribute()
    {
        $methods = [
            'pay_online' => 'Online Payment',
            'pay_at_hotel' => 'Pay at Hotel',
        ];
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get commission status badge
     */
    public function getCommissionStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'warning', 'text' => 'Pending'],
            'paid' => ['class' => 'success', 'text' => 'Paid'],
            'cancelled' => ['class' => 'danger', 'text' => 'Cancelled'],
        ];
        return $badges[$this->commission_status] ?? ['class' => 'secondary', 'text' => $this->commission_status];
    }

    /**
     * Check if this is an online payment
     */
    public function isOnlinePayment()
    {
        return $this->payment_method === 'pay_online';
    }

    /**
     * Check if this is an offline payment
     */
    public function isOfflinePayment()
    {
        return $this->payment_method === 'pay_at_hotel';
    }
}
