<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hotel_id',
        'name',
        'email',
        'mobile',
        'password',
        'role',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the hotel that the user belongs to
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    /**
     * Get the hotel owned by this user (for owners)
     */
    public function ownedHotel()
    {
        return $this->hasOne(Hotel::class, 'owner_id', 'id');
    }

    /**
     * Get the user who created this user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get users created by this user
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Get all bookings made by this user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Check if user is an owner
     */
    public function isOwner()
    {
        return strtoupper($this->role) === 'OWNER';
    }

    /**
     * Check if user is a manager
     */
    public function isManager()
    {
        return strtoupper($this->role) === 'MANAGER';
    }

    /**
     * Check if user is a receptionist
     */
    public function isReceptionist()
    {
        return strtoupper($this->role) === 'RECEPTION';
    }

    /**
     * Check if user's hotel is approved
     */
    public function hasApprovedHotel()
    {
        return $this->hotel && $this->hotel->isApproved();
    }
}
