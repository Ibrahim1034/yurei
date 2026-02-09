<?php
namespace App\Mpesa;

use App\Models\MpesaSTK;
use App\Models\User;
use Illuminate\Http\Request;

class STKPush
{
    public $failed = false;
    public $response = 'An Unknown Error Occurred';
    
    public function confirm(Request $request)
    {
        $payload = json_decode($request->getContent());
        
        if (property_exists($payload, 'Body') && $payload->Body->stkCallback->ResultCode == '0') {
            $merchant_request_id = $payload->Body->stkCallback->MerchantRequestID;
            $checkout_request_id = $payload->Body->stkCallback->CheckoutRequestID;
            $result_desc = $payload->Body->stkCallback->ResultDesc;
            $result_code = $payload->Body->stkCallback->ResultCode;
            $amount = $payload->Body->stkCallback->CallbackMetadata->Item[0]->Value;
            $mpesa_receipt_number = $payload->Body->stkCallback->CallbackMetadata->Item[1]->Value;
            $transaction_date = $payload->Body->stkCallback->CallbackMetadata->Item[3]->Value;
            $phonenumber = $payload->Body->stkCallback->CallbackMetadata->Item[4]->Value;
            
            $stkPush = MpesaSTK::where('merchant_request_id', $merchant_request_id)
                ->where('checkout_request_id', $checkout_request_id)->first();

            $data = [
                'result_desc' => $result_desc,
                'result_code' => $result_code,
                'merchant_request_id' => $merchant_request_id,
                'checkout_request_id' => $checkout_request_id,
                'amount' => $amount,
                'mpesa_receipt_number' => $mpesa_receipt_number,
                'transaction_date' => $transaction_date,
                'phonenumber' => $phonenumber,
                'status' => 'completed',
            ];

            if ($stkPush) {
                $stkPush->fill($data)->save();
                
                // Activate the user
                if ($stkPush->user_id) {
                    $user = User::find($stkPush->user_id);
                    if ($user && !$user->is_active) {
                        $user->update(['is_active' => true]);
                    }
                }
            } else {
                MpesaSTK::create($data);
            }
            
            $this->response = 'Payment confirmed successfully';
        } else {
            $this->failed = true;
            $this->response = $payload->Body->stkCallback->ResultDesc ?? 'Payment failed';
            
            // Update failed transaction
            if (property_exists($payload, 'Body')) {
                $merchant_request_id = $payload->Body->stkCallback->MerchantRequestID;
                $checkout_request_id = $payload->Body->stkCallback->CheckoutRequestID;
                $result_desc = $payload->Body->stkCallback->ResultDesc;
                $result_code = $payload->Body->stkCallback->ResultCode;
                
                $stkPush = MpesaSTK::where('merchant_request_id', $merchant_request_id)
                    ->where('checkout_request_id', $checkout_request_id)->first();
                    
                if ($stkPush) {
                    $stkPush->update([
                        'result_code' => $result_code,
                        'result_desc' => $result_desc,
                        'status' => 'failed',
                        'failure_reason' => $result_desc
                    ]);
                }
            }
        }
        return $this;
    }
}