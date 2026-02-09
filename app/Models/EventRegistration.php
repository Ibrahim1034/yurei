<?php
// app/Models/EventRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
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

    // Add allowed status values
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ATTENDED = 'attended';
    const STATUS_NO_SHOW = 'no_show';

    public static function getAllowedStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_ATTENDED,
            self::STATUS_NO_SHOW,
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(EventPayment::class, 'event_registration_id');
    }

    /**
     * Get the display name for the registrant
     */
    public function getRegistrantNameAttribute()
    {
        return $this->is_guest ? $this->guest_name : ($this->user ? $this->user->name : 'N/A');
    }

    /**
     * Get the display email for the registrant
     */
    public function getRegistrantEmailAttribute()
    {
        return $this->is_guest ? $this->guest_email : ($this->user ? $this->user->email : 'N/A');
    }

    /**
     * Get the display phone for the registrant
     */
    public function getRegistrantPhoneAttribute()
    {
        return $this->is_guest ? $this->guest_phone : ($this->user ? $this->user->phone_number : 'N/A');
    }

    /**
     * Generate a unique invitation code
     */
    public static function generateInvitationCode()
    {
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(8));
        } while (self::where('invitation_code', $code)->exists());

        return $code;
    }

    /**
     * Check if attendance can be marked for this registration
     */
    public function canMarkAttendance()
    {
        return $this->event->isWithinAttendanceWindow() && 
               $this->status !== self::STATUS_CANCELLED;
    }

    /**
     * Scope to get registrations with invitation codes
     */
    public function scopeWithInvitations($query)
    {
        return $query->whereNotNull('invitation_code');
    }

    /**
     * Scope to get attended registrations
     */
    public function scopeAttended($query)
    {
        return $query->where('status', self::STATUS_ATTENDED);
    }
}