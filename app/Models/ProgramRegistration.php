<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'amount_paid',
        'payment_status',
        'mpesa_receipt_number',
        'merchant_request_id',
        'checkout_request_id',
        'registration_date',
        'status',
        'is_guest',
        'confirmation_email_sent',
        'invitation_code'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'registration_date' => 'datetime',
        'is_guest' => 'boolean',
        'confirmation_email_sent' => 'boolean'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ATTENDED = 'attended';

    public static function getAllowedStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_ATTENDED,
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(ProgramPayment::class, 'program_registration_id');
    }

    public function getRegistrantNameAttribute()
    {
        return $this->is_guest ? $this->guest_name : ($this->user ? $this->user->name : 'N/A');
    }

    public function getRegistrantEmailAttribute()
    {
        return $this->is_guest ? $this->guest_email : ($this->user ? $this->user->email : 'N/A');
    }

    public function getRegistrantPhoneAttribute()
    {
        return $this->is_guest ? $this->guest_phone : ($this->user ? $this->user->phone_number : 'N/A');
    }

    public static function generateInvitationCode()
    {
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(8));
        } while (self::where('invitation_code', $code)->exists());

        return $code;
    }

    public function scopeWithInvitations($query)
    {
        return $query->whereNotNull('invitation_code');
    }

    public function scopeAttended($query)
    {
        return $query->where('status', self::STATUS_ATTENDED);
    }
}