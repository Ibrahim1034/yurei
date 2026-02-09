<?php
namespace App\Http\Controllers;

use App\Mpesa\STKPush;
use App\Models\MpesaSTK;
use App\Models\User;
use App\Models\Payment;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRegistrationConfirmation;

class MpesaSTKPUSHController extends Controller
{
    public $result_code = 1;
    public $result_desc = 'An error occured';

    public function STKPush(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'phonenumber' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Check if user is already active
        if ($user->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'User account is already active'
            ], 400);
        }

        $amount = $request->input('amount');
        $phoneno = $request->input('phonenumber');
        $account_number = $user->membership_number;

        // Use the web route for callback
        $callbackUrl = route('mpesa.confirm');

        Log::info('=== INITIATING STK PUSH ===', [
            'user_id' => $user->id,
            'phone' => $phoneno,
            'amount' => $amount,
            'account_number' => $account_number,
            'callback_url' => $callbackUrl,
            'timestamp' => now()
        ]);

        try {
            Log::info('Calling Mpesa::stkpush()...');
            
            $response = Mpesa::stkpush(
                phonenumber: $phoneno, 
                amount: $amount,
                account_number: $account_number, 
                callbackurl: $callbackUrl,
                transactionType: Mpesa::PAYBILL
            );

            Log::info('Mpesa::stkpush() response received', [
                'status' => $response->status(),
            ]);

            /** @var \Illuminate\Http\Client\Response $response */
            $result = $response->json(); 

            Log::info('STK Push Response JSON:', $result);
            
            if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
                // Create STK record with user relationship
                $stkRecord = MpesaSTK::create([
                    'merchant_request_id' => $result['MerchantRequestID'],
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'amount' => $amount,
                    'phonenumber' => $phoneno,
                    'user_id' => $user->id,
                    'status' => 'pending'
                ]);

                Log::info('STK record created successfully', [
                    'stk_id' => $stkRecord->id,
                    'checkout_request_id' => $stkRecord->checkout_request_id
                ]);

                return response()->json([
                    'success' => true,
                    'merchant_request_id' => $result['MerchantRequestID'],
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'customer_message' => $result['CustomerMessage'] ?? 'STK push initiated successfully',
                    'stk_record' => $stkRecord
                ]);
            } else {
                $errorMessage = $result['errorMessage'] ?? 'Failed to initiate STK push';
                Log::error('STK Push failed - API response error', [
                    'error' => $errorMessage, 
                    'response' => $result,
                    'response_code' => $result['ResponseCode'] ?? 'N/A'
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $errorMessage
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('STK Push exception - Detailed error', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to initiate payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function STKConfirm(Request $request)
    {
        Log::info('=== MPESA CALLBACK RECEIVED ===', [
            'headers' => $request->headers->all(),
            'raw_body' => $request->getContent(),
            'parsed_payload' => $request->all(),
        ]);

        $stk_push_confirm = (new STKPush())->confirm($request);
        
        if ($stk_push_confirm && !$stk_push_confirm->failed) {
            $this->result_code = 0;
            $this->result_desc = 'Success';
        } else {
            $this->result_code = 1;
            $this->result_desc = $stk_push_confirm->response ?? 'Failed';
        }
        
        Log::info('Callback response', [
            'ResultCode' => $this->result_code,
            'ResultDesc' => $this->result_desc
        ]);

        return response()->json([
            'ResultCode' => $this->result_code,
            'ResultDesc' => $this->result_desc
        ]);
    }

// In MpesaSTKPUSHController - update stkQuery method to handle specific messages
public function stkQuery(Request $request)
{
    $checkoutRequestId = $request->input('checkout_request_id');
    
    if (!$checkoutRequestId) {
        return response()->json([
            'success' => false,
            'error' => 'checkout_request_id is required'
        ], 400);
    }

    Log::info('=== INITIATING STK QUERY ===', [
        'checkout_request_id' => $checkoutRequestId
    ]);

    try {
        $response = Mpesa::stkquery($checkoutRequestId);
        
        /** @var \Illuminate\Http\Client\Response $response */
        $result = $response->json();

        Log::info('STK Query Response:', $result);

        // Find the STK record
        $stkRecord = MpesaSTK::where('checkout_request_id', $checkoutRequestId)->first();
        
        if ($stkRecord) {
            // Update STK record with query result
            if (isset($result['ResultCode'])) {
                $status = $result['ResultCode'] == '0' ? 'completed' : 'failed';
                
                $stkRecord->update([
                    'result_code' => $result['ResultCode'],
                    'result_desc' => $result['ResultDesc'],
                    'status' => $status
                ]);

                // If payment is successful, create payment record and activate user
                if ($result['ResultCode'] == '0') {
                    $this->createPaymentRecord($stkRecord);
                    
                    // Activate user
                    if ($stkRecord->user_id) {
                        $user = User::find($stkRecord->user_id);
                        if ($user && !$user->is_active) {
                            $user->update(['is_active' => true]);
                            Log::info('User activated after successful STK query', [
                                'user_id' => $user->id,
                                'stk_id' => $stkRecord->id
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'result' => $result,
            'status' => $stkRecord->status ?? 'unknown'
        ]);

    } catch (\Exception $e) {
        Log::error('STK Query exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Failed to query transaction: ' . $e->getMessage()
        ], 500);
    }
}

    // Create payment record from STK record
    private function createPaymentRecord($stkRecord)
    {
        try {
            $payment = Payment::create([
                'user_id' => $stkRecord->user_id,
                'merchant_request_id' => $stkRecord->merchant_request_id,
                'checkout_request_id' => $stkRecord->checkout_request_id,
                'amount' => $stkRecord->amount,
                'phone_number' => $stkRecord->phonenumber,
                'mpesa_receipt_number' => $stkRecord->mpesa_receipt_number,
                'transaction_date' => $stkRecord->transaction_date ? \Carbon\Carbon::createFromFormat('YmdHis', $stkRecord->transaction_date) : now(),
                'status' => 'completed'
            ]);

            Log::info('Payment record created from STK query', [
                'payment_id' => $payment->id,
                'stk_id' => $stkRecord->id
            ]);

            return $payment;
        } catch (\Exception $e) {
            Log::error('Failed to create payment record', [
                'error' => $e->getMessage(),
                'stk_record_id' => $stkRecord->id
            ]);
            return null;
        }
    }


public function checkStatus(Request $request)
{
    $checkoutRequestId = $request->query('checkout_request_id');
    
    if (!$checkoutRequestId) {
        return response()->json([
            'success' => false,
            'error' => 'checkout_request_id is required'
        ], 400);
    }
    
    Log::info('Checking payment status', ['checkout_request_id' => $checkoutRequestId]);
    
    $stkRecord = MpesaSTK::where('checkout_request_id', $checkoutRequestId)->first();
    
    if (!$stkRecord) {
        return response()->json([
            'success' => false,
            'error' => 'Transaction not found'
        ], 404);
    }

    Log::info('Payment status found', [
        'status' => $stkRecord->status,
        'result_code' => $stkRecord->result_code,
        'result_desc' => $stkRecord->result_desc
    ]);

    return response()->json([
        'success' => true,
        'status' => $stkRecord->status,
        'result_code' => $stkRecord->result_code,
        'result_desc' => $stkRecord->result_desc,
        'active' => $stkRecord->user->is_active ?? false,
        'user_id' => $stkRecord->user_id
    ]);
}


// Add to MpesaSTKPUSHController
public function handleEventPaymentCallback(Request $request)
{
    Log::info('=== EVENT PAYMENT CALLBACK RECEIVED ===', [
        'headers' => $request->headers->all(),
        'raw_body' => $request->getContent(),
        'parsed_payload' => $request->all(),
    ]);

    $callbackData = $request->all();

    if (isset($callbackData['Body']['stkCallback'])) {
        $stkCallback = $callbackData['Body']['stkCallback'];
        $merchantRequestId = $stkCallback['MerchantRequestID'];
        $checkoutRequestId = $stkCallback['CheckoutRequestID'];
        $resultCode = $stkCallback['ResultCode'];
        $resultDesc = $stkCallback['ResultDesc'];

        // Check if this is an event payment (account number starts with EVENT-)
        $isEventPayment = false;
        if (isset($stkCallback['CallbackMetadata']['Item'])) {
            foreach ($stkCallback['CallbackMetadata']['Item'] as $item) {
                if ($item['Name'] == 'AccountNumber' && strpos($item['Value'] ?? '', 'EVENT-') === 0) {
                    $isEventPayment = true;
                    break;
                }
            }
        }

        if ($isEventPayment) {
            // Handle event payment
            $payment = EventPayment::where('checkout_request_id', $checkoutRequestId)->first();

            if ($payment) {
                $status = $resultCode == 0 ? 'completed' : 'failed';
                
                $payment->update([
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'status' => $status
                ]);

                // If payment successful, update registration and send email
                if ($resultCode == 0 && isset($stkCallback['CallbackMetadata']['Item'])) {
                    $items = $stkCallback['CallbackMetadata']['Item'];
                    
                    $mpesaReceiptNumber = null;
                    $transactionDate = null;

                    foreach ($items as $item) {
                        if ($item['Name'] == 'MpesaReceiptNumber') {
                            $mpesaReceiptNumber = $item['Value'];
                        } elseif ($item['Name'] == 'TransactionDate') {
                            $transactionDate = $item['Value'];
                        }
                    }

                    $payment->update([
                        'mpesa_receipt_number' => $mpesaReceiptNumber,
                        'transaction_date' => $transactionDate ? \Carbon\Carbon::createFromFormat('YmdHis', $transactionDate) : null
                    ]);

                    // Find and update the registration
                    $registration = EventRegistration::find($payment->event_registration_id);
                    
                    if ($registration) {
                        $registration->update([
                            'payment_status' => 'paid',
                            'mpesa_receipt_number' => $mpesaReceiptNumber,
                            'status' => 'confirmed'
                        ]);

                        // Increment event participants
                        $registration->event->increment('current_participants');

                        // Send confirmation email
                        try {
                            Mail::to($registration->user->email)
                                ->send(new EventRegistrationConfirmation($registration));
                            
                            $registration->update(['confirmation_email_sent' => true]);
                            
                            Log::info('Event registration confirmation email sent via callback', [
                                'registration_id' => $registration->id,
                                'user_email' => $registration->user->email
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send event registration email via callback', [
                                'error' => $e->getMessage(),
                                'registration_id' => $registration->id
                            ]);
                        }
                    }
                }
            }
        } else {
            // Handle regular membership payment (existing logic)
            // ... your existing callback logic for membership payments
        }
    }

    return response()->json([
        'ResultCode' => 0,
        'ResultDesc' => 'Success'
    ]);
}
}