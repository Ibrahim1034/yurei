<?php

namespace App\Http\Controllers;

use App\Models\MpesaDonation;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function showDonationForm()
    {
        return view('donations.form');
    }

     public function initiateDonation(Request $request)
    {
        Log::info('=== DONATION INITIATION REQUEST ===', $request->all());

        // Custom validation with better error messages
        $validator = Validator::make($request->all(), [
            'donor_name' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:15|regex:/^254[0-9]{9}$/',
            'amount' => 'required|numeric|min:1|max:100000',
        ], [
            'phone_number.required' => 'Phone number is required',
            'phone_number.regex' => 'Phone number must be in format 2547XXXXXXXX',
            'amount.required' => 'Amount is required',
            'amount.min' => 'Minimum donation amount is KES 1',
            'amount.max' => 'Maximum donation amount is KES 100,000',
        ]);

        if ($validator->fails()) {
            Log::error('Donation validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        $amount = $request->input('amount');
        $phoneNumber = $request->input('phone_number');
        $donorName = $request->input('donor_name');
        $accountNumber = 'DONATION-' . time();

        Log::info('=== INITIATING DONATION STK PUSH ===', [
            'donor_name' => $donorName,
            'phone' => $phoneNumber,
            'amount' => $amount,
            'account_number' => $accountNumber
        ]);

        try {
            $response = Mpesa::stkpush(
                phonenumber: $phoneNumber, 
                amount: $amount,
                account_number: $accountNumber, 
                callbackurl: route('donations.callback'),
                transactionType: Mpesa::PAYBILL
            );

            $result = $response->json();

            Log::info('Donation STK Push Response:', $result);
            
            if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
                // Create donation record
                $donation = MpesaDonation::create([
                    'donor_name' => $donorName,
                    'phone_number' => $phoneNumber,
                    'amount' => $amount,
                    'merchant_request_id' => $result['MerchantRequestID'],
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'merchant_request_id' => $result['MerchantRequestID'],
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'customer_message' => $result['CustomerMessage'] ?? 'STK push initiated successfully',
                    'donation_id' => $donation->id
                ]);
            } else {
                $errorMessage = $result['errorMessage'] ?? 'Failed to initiate donation';
                Log::error('Donation STK Push failed', ['error' => $errorMessage]);
                return response()->json([
                    'success' => false,
                    'error' => $errorMessage
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Donation STK Push exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to initiate donation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkDonationStatus(Request $request)
    {
        $checkoutRequestId = $request->query('checkout_request_id');
        
        if (!$checkoutRequestId) {
            return response()->json([
                'success' => false,
                'error' => 'checkout_request_id is required'
            ], 400);
        }
        
        try {
            $donation = MpesaDonation::where('checkout_request_id', $checkoutRequestId)->first();
            
            if (!$donation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Donation not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status' => $donation->status,
                'result_code' => $donation->result_code,
                'result_desc' => $donation->result_desc,
                'donation_id' => $donation->id,
                'donation_completed' => $donation->status === 'completed',
                'amount' => $donation->amount,
                'donor_name' => $donation->donor_name
            ]);

        } catch (\Exception $e) {
            Log::error('Donation status check error', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $checkoutRequestId
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to check donation status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stkQuery(Request $request)
    {
        $checkoutRequestId = $request->input('checkout_request_id');
        
        if (!$checkoutRequestId) {
            return response()->json([
                'success' => false,
                'error' => 'checkout_request_id is required'
            ], 400);
        }

        Log::info('=== INITIATING DONATION STK QUERY ===', [
            'checkout_request_id' => $checkoutRequestId
        ]);

        try {
            $response = Mpesa::stkquery($checkoutRequestId);
            $result = $response->json();

            Log::info('Donation STK Query Response:', $result);

            $donation = MpesaDonation::where('checkout_request_id', $checkoutRequestId)->first();
            
            if ($donation) {
                if (isset($result['ResultCode'])) {
                    $status = $result['ResultCode'] == '0' ? 'completed' : 'failed';
                    
                    $updateData = [
                        'result_code' => $result['ResultCode'],
                        'result_desc' => $result['ResultDesc'],
                        'status' => $status
                    ];

                    // If payment is successful, update with receipt details
                    if ($result['ResultCode'] == '0' && isset($result['CallbackMetadata']['Item'])) {
                        $items = $result['CallbackMetadata']['Item'];
                        
                        foreach ($items as $item) {
                            if ($item['Name'] == 'MpesaReceiptNumber') {
                                $updateData['mpesa_receipt_number'] = $item['Value'];
                            } elseif ($item['Name'] == 'TransactionDate') {
                                $updateData['transaction_date'] = \Carbon\Carbon::createFromFormat('YmdHis', $item['Value']);
                            }
                        }
                    }

                    $donation->update($updateData);
                }
            }

            return response()->json([
                'success' => true,
                'result' => $result,
                'status' => $donation->status ?? 'unknown',
                'donation_id' => $donation->id ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Donation STK Query exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to query transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handleCallback(Request $request)
    {
        Log::info('=== DONATION CALLBACK RECEIVED ===', [
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

            $donation = MpesaDonation::where('checkout_request_id', $checkoutRequestId)->first();

            if ($donation) {
                $status = $resultCode == 0 ? 'completed' : 'failed';
                
                $updateData = [
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'status' => $status
                ];

                // If payment successful, update with receipt details
                if ($resultCode == 0 && isset($stkCallback['CallbackMetadata']['Item'])) {
                    $items = $stkCallback['CallbackMetadata']['Item'];
                    
                    foreach ($items as $item) {
                        if ($item['Name'] == 'MpesaReceiptNumber') {
                            $updateData['mpesa_receipt_number'] = $item['Value'];
                        } elseif ($item['Name'] == 'TransactionDate') {
                            $updateData['transaction_date'] = \Carbon\Carbon::createFromFormat('YmdHis', $item['Value']);
                        }
                    }
                }

                $donation->update($updateData);

                Log::info('Donation callback processed', [
                    'donation_id' => $donation->id,
                    'status' => $status,
                    'result_code' => $resultCode
                ]);
            }
        }

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ]);
    }
}