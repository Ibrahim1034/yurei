<?php
// app/Services/IntaSendService.php

namespace App\Services;

use IntaSend\IntaSendPHP\Collection;
use Illuminate\Support\Facades\Log;

class IntaSendService
{
    protected $credentials;
     protected $testMode;

    public function __construct()
    {
        $this->testMode = true; // Set test mode
        
        $this->credentials = [
            'token' => 'ISSecretKey_test_7ff5436d-db71-4edc-8aa5-f70146e08cf8',
            'publishable_key' => 'ISPubKey_test_6d1456c6-1a02-4450-acd7-cff83672366c',
            'test' => $this->testMode
        ];

        Log::debug('IntaSend Service Initialized', [
            'test_mode' => $this->testMode,
            'has_token' => !empty($this->credentials['token']),
            'has_publishable_key' => !empty($this->credentials['publishable_key'])
        ]);
    }

  
     public function initiatePaybillPayment($amount, $phoneNumber, $accountReference, $paymentPurpose = 'Membership Fee')
    {
        try {
            Log::debug('Initiating IntaSend payment', [
                'amount' => $amount,
                'phone' => $phoneNumber,
                'account_reference' => $accountReference,
                'test_mode' => $this->testMode
            ]);

            $collection = new Collection();
            $collection->init($this->credentials);

            // This is the key change - your friend's code calls mpesa_stk_push directly
            $response = $collection->mpesa_stk_push($amount, $phoneNumber, $accountReference);
            
            Log::debug('IntaSend STK Push Response:', (array) $response);

            // Extract the necessary fields from the response
            return [
                'success' => true,
                'invoice_id' => $response->invoice->invoice_id ?? null,
                'merchant_request_id' => $response->merchant_request_id ?? null,
                'checkout_request_id' => $response->checkout_request_id ?? null,
                'response_description' => $response->response_description ?? 'Request sent successfully',
                'customer_message' => $response->customer_message ?? 'Please check your phone to complete payment'
            ];

        } catch (\Exception $e) {
            Log::error('IntaSend STK Push Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    
    public function checkTransactionStatus($invoiceId)
    {
        try {
            Log::debug('Checking transaction status', ['invoice_id' => $invoiceId]);

            $collection = new Collection();
            $collection->init($this->credentials);

            $response = $collection->status($invoiceId);
            
            Log::debug('Transaction status response:', (array) $response);

            return [
                'success' => true,
                'status' => $response->invoice->state ?? 'unknown',
                'invoice_id' => $response->invoice->invoice_id ?? null,
                'response' => $response
            ];

        } catch (\Exception $e) {
            Log::error('Status check error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    public function handleWebhook($payload)
    {
        try {
            Log::info('Processing IntaSend webhook', $payload);

            // IntaSend webhook format for STK push
            if (isset($payload['invoice'])) {
                $invoice = $payload['invoice'];
                $invoiceId = $invoice['invoice_id'] ?? null;
                $state = $invoice['state'] ?? null;
                
                if ($state === 'COMPLETE') {
                    return [
                        'success' => true,
                        'invoice_id' => $invoiceId,
                        'state' => $state,
                        'metadata' => $invoice
                    ];
                } else if ($state === 'FAILED' || $state === 'WITHDRAWN') {
                    return [
                        'success' => false,
                        'invoice_id' => $invoiceId,
                        'state' => $state,
                        'reason' => $invoice['failed_reason'] ?? 'Payment failed'
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Invalid webhook payload format'
            ];

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}