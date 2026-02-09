<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'merchant_request_id',
        'invoice_id', // ADD THIS
        'checkout_request_id',
        'mpesa_receipt_number',
        'amount',
        'phone_number',
        'transaction_date',
        'status',
         'failure_reason' // ADD THIS IF YOU WANT TO STORE FAILURE REASONS
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'double',
    ];

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

      public function events()
    {
        // Specifying the foreign key column name because it's 'created_by', not 'user_id'
        return $this->hasMany(Event::class, 'created_by');
    }

    public function isMember()
    {
        return $this->user_type === 'member';
    }

    public function isFriend()
    {
        return $this->user_type === 'friend';
    }
}