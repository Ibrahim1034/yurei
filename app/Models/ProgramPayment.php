<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_registration_id',
        'amount',
        'phone_number',
        'merchant_request_id',
        'checkout_request_id',
        'mpesa_receipt_number',
        'result_code',
        'result_desc',
        'transaction_date',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime'
    ];

    public function registration()
    {
        return $this->belongsTo(ProgramRegistration::class, 'program_registration_id');
    }
}