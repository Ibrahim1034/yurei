<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin (role = 1)
        if (Auth::check() && Auth::user()->role === 1) {
            return $next($request);
        }

        // If not admin, redirect to dashboard with error
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    }
}