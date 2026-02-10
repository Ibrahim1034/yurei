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
        $this->failed = false;
        $this->response = 'An Unknown Error Occurred';

        // Get raw content
        $rawContent = $request->getContent();

        // Log for debugging
        error_log("=== MPESA WEBHOOK START ===");
        error_log("Raw content length: " . strlen($rawContent));

        // Handle empty content
        if (empty($rawContent)) {
            error_log("ERROR: Empty webhook content");
            $this->failed = true;
            $this->response = 'Empty payload';
            return $this;
        }

        $payload = json_decode($rawContent);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            error_log("Raw content: " . $rawContent);
            $this->failed = true;
            $this->response = 'Invalid JSON: ' . json_last_error_msg();
            return $this;
        }

        // Check if payload is null after decoding
        if ($payload === null) {
            error_log("ERROR: Payload is null after decoding");
            $this->failed = true;
            $this->response = 'Payload is null';
            return $this;
        }

        // Now safely check for properties
        if (!is_object($payload)) {
            error_log("ERROR: Payload is not an object, type: " . gettype($payload));
            $this->failed = true;
            $this->response = 'Payload is not an object';
            return $this;
        }

        // Check if Body property exists
        if (!property_exists($payload, 'Body')) {
            error_log("ERROR: Body property not found in payload");
            $this->failed = true;
            $this->response = 'Body property not found';
            return $this;
        }

        // YOUR EXISTING CODE FROM LINE 17 ONWARD
        if ($payload->Body->stkCallback->ResultCode == '0') {
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

            error_log("SUCCESS: Payment confirmed - " . $mpesa_receipt_number);
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

            error_log("FAILED: " . $this->response);
        }

        error_log("=== MPESA WEBHOOK END ===");
        return $this;
    }
}
