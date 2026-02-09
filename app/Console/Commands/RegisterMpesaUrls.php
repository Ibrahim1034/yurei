<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterMpesaUrls extends Command
{
    protected $signature = 'mpesa:register-urls';
    protected $description = 'Register M-Pesa confirmation and validation URLs with Daraja API';

    public function handle()
    {
        $env = config('services.mpesa.env', 'sandbox');

        $consumerKey = $env === 'production'
            ? env('MPESA_PRODUCTION_CONSUMER_KEY')
            : env('MPESA_SANDBOX_CONSUMER_KEY');

        $consumerSecret = $env === 'production'
            ? env('MPESA_PRODUCTION_CONSUMER_SECRET')
            : env('MPESA_SANDBOX_CONSUMER_SECRET');

        $shortcode = $env === 'production'
            ? env('MPESA_PRODUCTION_SHORTCODE')
            : env('MPESA_SANDBOX_SHORTCODE');

        $validationUrl = env('MPESA_VALIDATION_URL');
        $confirmationUrl = env('MPESA_CONFIRMATION_URL');

        $url = $env === 'production'
            ? 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl'
            : 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        // Get access token
        $tokenResponse = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get($env === 'production'
                ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
                : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            );

        if ($tokenResponse->failed()) {
            $this->error('Failed to generate access token.');
            return 1;
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // Register URLs
        $response = Http::withToken($accessToken)
            ->post($url, [
                "ShortCode"      => $shortcode,
                "ResponseType"   => "Completed",
                "ConfirmationURL"=> $confirmationUrl,
                "ValidationURL"  => $validationUrl
            ]);

        if ($response->successful()) {
            $this->info('✅ M-Pesa URLs registered successfully!');
            $this->line(json_encode($response->json(), JSON_PRETTY_PRINT));
        } else {
            $this->error('❌ Failed to register URLs');
            $this->line($response->body());
        }

        return 0;
    }
}
