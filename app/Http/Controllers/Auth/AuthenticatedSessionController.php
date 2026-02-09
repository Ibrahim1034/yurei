<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

  public function store(Request $request): RedirectResponse
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

        // Redirect based on role
        if ($user->role === 1) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, Administrator!');
        } else {
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back!');
        }
    }

    throw ValidationException::withMessages([
        'email' => __('auth.failed'),
    ]);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}