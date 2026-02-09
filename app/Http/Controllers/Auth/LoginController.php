<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Log;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is active (has paid)
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->route('payment.create', $user)
                    ->with('error', 'Please complete your payment to access your account.');
            }
            
            $request->session()->regenerate();

            // Debug: Check user role
            \Log::info('User logged in', [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active
            ]);

            // Redirect based on role - VERY EXPLICIT
            if ($user->role === 1) {
                \Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Administrator!');
            } else {
                \Log::info('Redirecting to user dashboard');
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Welcome back!');
            }
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}