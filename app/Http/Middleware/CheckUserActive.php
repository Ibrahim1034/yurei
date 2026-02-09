<?php
// app/Http/Middleware/CheckUserActive.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_active) {
            // Redirect to payment page if user is not active
            return redirect()->route('payment.create', Auth::user())
                ->with('error', 'Please complete your payment to access the dashboard.');
        }

        return $next($request);
    }
}