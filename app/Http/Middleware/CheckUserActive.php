<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if a user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user is NOT active
            if (!$user->is_active) {
                $currentRoute = $request->route();

                // EXCEPTION: Allow access to payment pages even if not active.
                // This prevents the infinite redirect loop (Payment -> Redirect to Payment).
                // We also allow 'payment.retry' so they can fix failed transactions.
                if ($currentRoute && ($currentRoute->named('payment.create') || $currentRoute->named('payment.retry'))) {
                    Log::info('✅ Allowing non-active user to access payment page', [
                        'user_id' => $user->id,
                        'route' => $currentRoute->getName(),
                        'url' => $request->fullUrl()
                    ]);
                    return $next($request);
                }

                // DEFAULT BEHAVIOR: Redirect inactive users trying to access other protected pages (like Dashboard)
                Log::info('🔄 Redirecting non-active user to payment page', [
                    'user_id' => $user->id,
                    'intended_url' => $request->fullUrl(),
                    'route_name' => $currentRoute ? $currentRoute->getName() : 'unknown'
                ]);

                return redirect()->route('payment.create', $user)
                    ->with('error', 'Please complete your payment to access the dashboard.');
            }
        }

        return $next($request);
    }
}
