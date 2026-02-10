<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MpesaSTK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function create(User $user)
    {
        // FAIL-SAFE: Ensure user has a valid session or is logged in
        if (!session()->has('registration_user_id') && !Auth::check()) {
            Log::warning('No session or auth for payment page', ['user_id' => $user->id]);
            return redirect()->route('register')->with('error', 'Please start the registration process.');
        }

        Log::info('🎯 PAYMENT CREATE ACCESSED', [
            'requested_user_id' => $user->id,
            'auth_user_id' => Auth::id(),
            'auth_check' => Auth::check(),
            'session_user_id' => session()->get('user_id'),
            'session_registration_user_id' => session()->get('registration_user_id'),
            'user_is_active' => $user->is_active,
            'url' => request()->fullUrl()
        ]);

        // FIXED LOGIC: Allow access if:
        // 1. User is authenticated and it's their own payment page
        // 2. OR if this is a fresh registration (session has registration_user_id)
        // 3. OR if session has registration_complete flag

        $sessionRegistrationUserId = session()->get('registration_user_id');
        $isFreshRegistration = ($sessionRegistrationUserId == $user->id);
        $isOwnAccount = (Auth::check() && Auth::id() == $user->id);

        if (!$isOwnAccount && !$isFreshRegistration) {
            Log::warning('❌ UNAUTHORIZED PAYMENT ACCESS', [
                'auth_id' => Auth::id(),
                'requested_id' => $user->id,
                'session_registration_user_id' => $sessionRegistrationUserId,
                'is_fresh_registration' => $isFreshRegistration
            ]);

            // If user is authenticated but trying to access someone else's payment
            if (Auth::check()) {
                return redirect()->route('dashboard')->with('error', 'You can only access your own payment page.');
            }

            return redirect()->route('login')->with('error', 'Please log in to complete your registration.');
        }

        // If we got here via fresh registration but user is not authenticated, log them in
        if ($isFreshRegistration && !Auth::check()) {
            Auth::login($user);
            Log::info('🔄 AUTO-LOGGED IN USER FOR PAYMENT', ['user_id' => $user->id]);

            // Clear the registration flag now that they're properly logged in
            session()->forget('registration_user_id');
            session()->forget('registration_complete');
        }

        if ($user->is_active) {
            return redirect()->route('dashboard')->with('status', 'Your account is already active.');
        }

        // Determine amount based on user type
        $amount = $user->user_type === 'member' ? 1 : 2; // 1 KES for members, 2 KES for friends

        return view('payments.create', compact('user', 'amount'));
    }

    /**
     * Process payment via Kumu's STK Push
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'phone_number' => ['required', 'string', 'regex:/^254[0-9]{9}$/'],
            'amount' => ['required', 'numeric', 'min:1', 'max:70000'],
        ]);

        Log::info('Processing payment via Kumu STK Push:', [
            'user_id' => $user->id,
            'phone' => $request->phone_number,
            'amount' => $request->amount,
            'user_type' => $user->user_type
        ]);

        // We'll use AJAX to call Kumu's endpoint, so just return the view
        // The JavaScript will handle the STK Push initiation

        return redirect()->back();
    }

    /**
     * Check payment status (AJAX endpoint) - Using Kumu's STK table
     */
    public function checkStatus(Request $request)
    {
        // Get the latest transaction for the logged-in user
        $stkRecord = MpesaSTK::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$stkRecord) {
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'result_desc' => 'Transaction not found.'
            ], 404);
        }

        if ($stkRecord->status === 'completed') {
            // Activate the user account
            $user = User::find(auth()->id());
            if ($user && !$user->is_active) {
                $user->update([
                    'is_active' => true,
                    'expiration_date' => now()->addYear(),
                ]);

                Log::info('✅ USER ACTIVATED AFTER SUCCESSFUL PAYMENT', [
                    'user_id' => $user->id,
                    'stk_id' => $stkRecord->id,
                    'amount' => $stkRecord->amount
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => 'completed',
                'redirect' => route('login'),
                'message' => 'Thank you for your payment! Please log in to continue.'
            ]);
        }

        return response()->json([
            'success' => false,
            'status' => $stkRecord->status,
            'result_desc' => $stkRecord->result_desc ?? 'Payment not completed yet.'
        ]);
    }

    public function success(Request $request)
    {
        $checkoutRequestId = $request->query('checkout_request_id');

        if (!$checkoutRequestId) {
            return redirect()->route('login')->with('error', 'Invalid payment session.');
        }

        $stkRecord = MpesaSTK::where('checkout_request_id', $checkoutRequestId)->first();

        if (!$stkRecord) {
            return redirect()->route('login')->with('error', 'Transaction not found.');
        }

        // Activate user if payment was successful
        if ($stkRecord->status === 'completed') {
            $user = User::find($stkRecord->user_id);
            if ($user && !$user->is_active) {
                $user->update([
                    'is_active' => true,
                    'expiration_date' => now()->addYear(),
                ]);

                Log::info('✅ USER ACTIVATED VIA SUCCESS PAGE', [
                    'user_id' => $user->id,
                    'stk_id' => $stkRecord->id
                ]);
            }
        }

        // ✅ Transaction exists → redirect to login with success message
        return redirect()->route('login')->with('status', 'Thank you for your payment! Please log in to continue.');
    }

    /**
     * M-Pesa validation endpoint (required by Safaricom)
     */
    public function validateTransaction(Request $request)
    {
        Log::info('M-Pesa Validation Request:', $request->all());

        // Always respond with resultCode 0 to accept the transaction
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted'
        ]);
    }

    /**
     * M-Pesa confirmation endpoint (required by Safaricom)
     */
    public function confirmTransaction(Request $request)
    {
        Log::info('M-Pesa Confirmation Request:', $request->all());

        // Process C2B payment confirmation if needed
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Confirmation received successfully'
        ]);
    }

    /**
     * Initialize payment (for compatibility with your routes)
     */
    public function initialize(Request $request)
    {
        $user = Auth::user();
        // UPDATED: 1 KES for members, 2 KES for friends
        $amount = $user->user_type === 'member' ? 1 : 2;

        return view('payments.create', compact('user', 'amount'));
    }

    /**
     * Payment callback (for compatibility with your routes)
     */
    public function callback(Request $request)
    {
        $checkoutRequestId = $request->query('checkout_request_id');

        if (!$checkoutRequestId) {
            return redirect()->route('login')->with('error', 'Invalid payment session.');
        }

        $stkRecord = MpesaSTK::where('checkout_request_id', $checkoutRequestId)->first();

        if (!$stkRecord) {
            return redirect()->route('payment.create', Auth::user())->with('error', 'Transaction not found.');
        }

        if ($stkRecord->status === 'completed') {
            $user = User::find($stkRecord->user_id);
            if ($user && !$user->is_active) {
                $user->update([
                    'is_active' => true,
                    'expiration_date' => now()->addYear(),
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Payment completed successfully! Your account is now active.');
        }

        return redirect()->route('payment.create', Auth::user())->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Retry payment for failed/cancelled transactions
     */
    public function retryPayment(User $user)
    {
        if (!Auth::check() || Auth::id() !== $user->id) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        if ($user->is_active) {
            return redirect()->route('dashboard')->with('status', 'Your account is already active.');
        }

        $amount = $user->user_type === 'member' ? 1 : 2;

        Log::info('🔄 RETRYING PAYMENT', [
            'user_id' => $user->id,
            'amount' => $amount,
            'user_type' => $user->user_type
        ]);

        return view('payments.create', compact('user', 'amount'));
    }
}
