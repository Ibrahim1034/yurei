<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'webhook/intasend',
        'api/webhook/intasend',
        // M-Pesa routes
        'webhook/mpesa/confirm',    // M-Pesa callback (POST from Safaricom)
        'v1/mpesa/stk/push',        // STK Push initiation (POST from your frontend)
        'v1/mpesa/stk/query',       // STK Query (POST from your frontend)
        // Payment webhooks
        'webhook/payment/validation',
        'webhook/payment/confirmation',
    ];
}
