<?php
// app/Http/Controllers/EventPaymentController.php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\EventPayment;
use App\Models\User;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRegistrationConfirmation;
use Illuminate\Support\Str;

class EventPaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:event_registrations,id',
            'phone_number' => 'required|string'
        ]);

        $registration = EventRegistration::with('event')->findOrFail($request->registration_id);
        
        // Check if already paid
        if ($registration->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'error' => 'Payment already completed for this registration.'
            ], 400);
        }

        $amount = $registration->event->registration_fee;
        $phoneNumber = $request->phone_number;
        $accountNumber = 'EVENT-' . $registration->id;

        // FIX: Use the same callback URL structure as working system
        $callbackUrl = route('mpesa.confirm'); // This points to /v1/confirm

        Log::info('=== INITIATING EVENT PAYMENT STK PUSH ===', [
            'registration_id' => $registration->id,
            'event_id' => $registration->event_id,
            'phone' => $phoneNumber,
            'amount' => $amount,
            'account_number' => $accountNumber,
            'callback_url' => $callbackUrl
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

            Log::info('Event Payment STK Push Response:', $result);
            
            if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
                // Create event payment record
                $eventPayment = EventPayment::create([
                    'event_registration_id' => $registration->id,
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
                    'payment_id' => $eventPayment->id
                ]);
            } else {
                $errorMessage = $result['errorMessage'] ?? 'Failed to initiate payment';
                Log::error('Event Payment STK Push failed', ['error' => $errorMessage]);
                return response()->json([
                    'success' => false,
                    'error' => $errorMessage
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Event Payment STK Push exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to initiate payment: ' . $e->getMessage()
            ], 500);
        }
    }

    // FIX: Use the same status checking endpoint as working system
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
            // Use the same status checking logic as working system
            $payment = EventPayment::where('checkout_request_id', $checkoutRequestId)->first();
            
            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Payment not found'
                ], 404);
            }

            // Find the registration
            $registration = EventRegistration::find($payment->event_registration_id);
            
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
                'registration_id' => $payment->event_registration_id,
                'payment_completed' => $payment->status === 'completed',
                'registration_confirmed' => $registration->payment_status === 'paid',
                'invitation_code' => $registration->invitation_code // Add invitation code to response
            ]);

        } catch (\Exception $e) {
            Log::error('Event payment status check error', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $checkoutRequestId
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to check payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    // FIX: Use the same STK query endpoint as working system
    public function stkQuery(Request $request)
    {
        $checkoutRequestId = $request->input('checkout_request_id');
        
        if (!$checkoutRequestId) {
            return response()->json([
                'success' => false,
                'error' => 'checkout_request_id is required'
            ], 400);
        }

        Log::info('=== INITIATING EVENT STK QUERY ===', [
            'checkout_request_id' => $checkoutRequestId
        ]);

        try {
            // Use the same STK query as working system
            $response = Mpesa::stkquery($checkoutRequestId);
            $result = $response->json();

            Log::info('Event STK Query Response:', $result);

            // Find the payment record
            $payment = EventPayment::where('checkout_request_id', $checkoutRequestId)->first();
            
            if ($payment) {
                // Update payment record with query result
                if (isset($result['ResultCode'])) {
                    $status = $result['ResultCode'] == '0' ? 'completed' : 'failed';
                    
                    $payment->update([
                        'result_code' => $result['ResultCode'],
                        'result_desc' => $result['ResultDesc'],
                        'status' => $status
                    ]);

                    // If payment is successful, update registration and send email
                    if ($result['ResultCode'] == '0') {
                        $registration = EventRegistration::find($payment->event_registration_id);
                        
                        if ($registration) {
                            // Generate invitation code if not already exists
                            if (!$registration->invitation_code) {
                                $invitationCode = EventRegistration::generateInvitationCode();
                                $registration->update([
                                    'invitation_code' => $invitationCode
                                ]);
                                Log::info('Generated invitation code for paid event registration', [
                                    'registration_id' => $registration->id,
                                    'invitation_code' => $invitationCode
                                ]);
                            }

                            $registration->update([
                                'payment_status' => 'paid',
                                'status' => 'confirmed'
                            ]);

                            // Increment event participants
                            $registration->event->increment('current_participants');

                            // Send confirmation email - UPDATED FOR GUEST SUPPORT
                            try {
                                $emailTo = $registration->is_guest ? $registration->guest_email : $registration->user->email;
                                Mail::to($emailTo)
                                    ->send(new EventRegistrationConfirmation($registration));
                                
                                $registration->update(['confirmation_email_sent' => true]);
                                
                                Log::info('Event registration confirmation email sent via STK query', [
                                    'registration_id' => $registration->id,
                                    'email_sent_to' => $emailTo,
                                    'is_guest' => $registration->is_guest,
                                    'invitation_code' => $registration->invitation_code
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to send event registration email via STK query', [
                                    'error' => $e->getMessage(),
                                    'registration_id' => $registration->id,
                                    'email_attempted' => $emailTo
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
                'registration_id' => $payment->event_registration_id ?? null,
                'invitation_code' => $registration->invitation_code ?? null // Add invitation code to response
            ]);

        } catch (\Exception $e) {
            Log::error('Event STK Query exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to query transaction: ' . $e->getMessage()
            ], 500);
        }
    }
}