<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stripe_account_id',
        'stripe_onboarding',
        'payouts_enabled',
        'google_uid',
        'facebook_uid',
        'apple_uid',
        'first_name',
        'last_name',
        'email',
        'password',
        'otp',
        'otp_expires_at',
        'verify_key',
        'key_expires_at',
        'picture',
        'active',
        'phone',
        'company',
        'category',
        'description',
        'phone_notification',
        'sms_notification',
        'email_notification',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verify_key',
        'key_expires_at',
        'otp',
        'otp_expires_at',
        'stripe_account_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class,'user_role');
    }
    public function banks()
    {
        return $this->hasMany(UserBankDetail::class);
    }
    public function locations()
    {
        return $this->hasMany(Location::class);
    }
    public function bookings()
    {
        return $this->hasMany(Order::class,'renter_id');
    }
    public function picture()
    {
        return $this->belongsTo(Image::class,'picture');
    }
}
