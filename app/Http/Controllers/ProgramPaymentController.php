<?php

namespace App\Http\Controllers;

use App\Models\ProgramRegistration;
use App\Models\ProgramPayment;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProgramRegistrationConfirmation;

class ProgramPaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:program_registrations,id',
            'phone_number' => 'required|string'
        ]);

        $registration = ProgramRegistration::with('program')->findOrFail($request->registration_id);
        
        if ($registration->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'error' => 'Payment already completed for this registration.'
            ], 400);
        }

        $amount = $registration->program->registration_fee;
        $phoneNumber = $request->phone_number;
        $accountNumber = 'PROGRAM-' . $registration->id;

        $callbackUrl = route('mpesa.confirm');

        Log::info('=== INITIATING PROGRAM PAYMENT STK PUSH ===', [
            'registration_id' => $registration->id,
            'program_id' => $registration->program_id,
            'phone' => $phoneNumber,
            'amount' => $amount,
            'account_number' => $accountNumber
        ]);

        try {
            $response = Mpesa::stkpush(
                phonenumber: $phoneNumber, 
                amount: $amount,
                account_number: $accountNumber, 
                callbackurl: $callbackUrl,
                transactionType: Mpesa::PAYBILL
            );

            $result = $response->json();
            
            if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
                $programPayment = ProgramPayment::create([
                    'program_registration_id' => $registration->id,
                    'amount' => $amount,
                    'phone_number' => $phoneNumber,
                    'merchant_request_id' => $result['MerchantRequestID'],
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'merchant_request_id' => $result['MerchantRequestID'],
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'customer_message' => $result['CustomerMessage'] ?? 'STK push initiated successfully',
                    'payment_id' => $programPayment->id
                ]);
            } else {
                $errorMessage = $result['errorMessage'] ?? 'Failed to initiate payment';
                Log::error('Program Payment STK Push failed', ['error' => $errorMessage]);
                return response()->json([
                    'success' => false,
                    'error' => $errorMessage
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Program Payment STK Push exception', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to initiate payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        $checkoutRequestId = $request->query('checkout_request_id');
        
        if (!$checkoutRequestId) {
            return response()->json([
                'success' => false,
                'error' => 'checkout_request_id is required'
            ], 400);
        }
        
        try {
            $payment = ProgramPayment::where('checkout_request_id', $checkoutRequestId)->first();
            
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Payment not found'
                ], 404);
            }

            $registration = ProgramRegistration::find($payment->program_registration_id);
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'error' => 'Registration not found for this payment'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status' => $payment->status,
                'result_code' => $payment->result_code,
                'result_desc' => $payment->result_desc,
                'registration_id' => $payment->program_registration_id,
                'payment_completed' => $payment->status === 'completed',
                'registration_confirmed' => $registration->payment_status === 'paid',
                'invitation_code' => $registration->invitation_code
            ]);

        } catch (\Exception $e) {
            Log::error('Program payment status check error', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $checkoutRequestId
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to check payment status: ' . $e->getMessage()
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

        Log::info('=== INITIATING PROGRAM STK QUERY ===', [
            'checkout_request_id' => $checkoutRequestId
        ]);

        try {
            $response = Mpesa::stkquery($checkoutRequestId);
            $result = $response->json();

            Log::info('Program STK Query Response:', $result);

            $payment = ProgramPayment::where('checkout_request_id', $checkoutRequestId)->first();
            
            if ($payment) {
                if (isset($result['ResultCode'])) {
                    $status = $result['ResultCode'] == '0' ? 'completed' : 'failed';
                    
                    $payment->update([
                        'result_code' => $result['ResultCode'],
                        'result_desc' => $result['ResultDesc'],
                        'status' => $status
                    ]);

                    if ($result['ResultCode'] == '0') {
                        $registration = ProgramRegistration::find($payment->program_registration_id);
                        
                        if ($registration) {
                            if (!$registration->invitation_code) {
                                $invitationCode = ProgramRegistration::generateInvitationCode();
                                $registration->update([
                                    'invitation_code' => $invitationCode
                                ]);
                                Log::info('Generated invitation code for paid program registration', [
                                    'registration_id' => $registration->id,
                                    'invitation_code' => $invitationCode
                                ]);
                            }

                            $registration->update([
                                'payment_status' => 'paid',
                                'status' => 'confirmed'
                            ]);

                            $registration->program->increment('current_participants');

                            try {
                                $emailTo = $registration->is_guest ? $registration->guest_email : $registration->user->email;
                                Mail::to($emailTo)
                                    ->send(new ProgramRegistrationConfirmation($registration));
                                
                                $registration->update(['confirmation_email_sent' => true]);
                                
                                Log::info('Program registration confirmation email sent via STK query', [
                                    'registration_id' => $registration->id,
                                    'email_sent_to' => $emailTo
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to send program registration email via STK query', [
                                    'error' => $e->getMessage(),
                                    'registration_id' => $registration->id
                                ]);
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'result' => $result,
                'status' => $payment->status ?? 'unknown',
                'registration_id' => $payment->program_registration_id ?? null,
                'invitation_code' => $registration->invitation_code ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Program STK Query exception', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to query transaction: ' . $e->getMessage()
            ], 500);
        }
    }
}