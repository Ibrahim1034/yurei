<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaSTK extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $table = 'mpesa_s_t_k_s';

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}




// <?php
// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class MpesaSTK extends Model
// {
//     use HasFactory;
    
//     protected $guarded = [];
//     protected $table = 'mpesa_s_t_k_s';

//     protected $fillable = [
//         'merchant_request_id',
//         'checkout_request_id',
//         'result_code',
//         'result_desc',
//         'amount',
//         'mpesa_receipt_number',
//         'transaction_date',
//         'phonenumber',
//         'user_id',
//         'payment_id'
//     ];

//     public function user()
//     {
//         return $this->belongsTo(User::class);
//     }

//     public function payment()
//     {
//         return $this->belongsTo(Payment::class);
//     }
// }