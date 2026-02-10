<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * AJAX endpoint to check phone number availability
     */
    public function checkPhoneAvailability(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|regex:/^254[0-9]{9}$/'
            ]);

            $phoneNumber = $request->phone_number;

            // Check if phone number already exists
            $existingUser = User::where('phone_number', $phoneNumber)->first();

            if ($existingUser) {
                Log::info('📱 PHONE NUMBER DUPLICATE CHECK - FOUND', [
                    'phone' => $phoneNumber,
                    'existing_user_id' => $existingUser->id,
                    'existing_email' => $existingUser->email
                ]);

                return response()->json([
                    'available' => false,
                    'message' => 'This phone number is already registered. Please use a different number.'
                ]);
            }

            return response()->json([
                'available' => true,
                'message' => 'Phone number is available'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ PHONE AVAILABILITY CHECK ERROR', [
                'error' => $e->getMessage(),
                'phone' => $request->phone_number ?? 'none'
            ]);

            return response()->json([
                'available' => false,
                'message' => 'Error checking phone number. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'max:15', 'unique:' . User::class],
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'in:member,friend'],
        ];

        // Additional validation for members
        if ($request->user_type === 'member') {
            $rules = array_merge($rules, [
                'county' => ['required', 'string', 'max:255'],
                'constituency' => ['required', 'string', 'max:255'],
                'ward' => ['required', 'string', 'max:255'],
                'institution' => ['required', 'string', 'max:255'],
                'graduation_status' => ['required', 'in:studying,graduated'],
            ]);
        } else {
            // For friends, these fields are optional or not required
            $rules = array_merge($rules, [
                'institution' => ['sometimes', 'nullable', 'string', 'max:255'],
                'graduation_status' => ['sometimes', 'nullable', 'in:studying,graduated'],
            ]);
        }

        $validated = $request->validate($rules);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'password' => Hash::make($validated['password']),
            'profile_picture' => $profilePicturePath,
            'user_type' => $validated['user_type'],
            'county' => $validated['county'] ?? null,
            'constituency' => $validated['constituency'] ?? null,
            'ward' => $validated['ward'] ?? null,
            'institution' => $validated['institution'] ?? null,
            'graduation_status' => $validated['graduation_status'] ?? null,
            'is_active' => false, // User needs to complete payment to be active
        ]);

        // Generate membership number
        $user->generateMembershipNumber();

        Log::info('🔵 USER CREATED', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'user_type' => $user->user_type,
            'membership_number' => $user->membership_number
        ]);

        event(new Registered($user));

        // Log the user in and regenerate session for security
        Auth::login($user);
        $request->session()->regenerate();

        // Store user ID in session explicitly to prevent loss
        session(['registration_user_id' => $user->id]);
        session(['registration_complete' => true]);

        Log::info('🟡 USER LOGGED IN AFTER REGISTRATION', [
            'check' => Auth::check(),
            'id' => Auth::id(),
            'user' => Auth::user()->id ?? 'null',
            'session_id' => session()->getId(),
            'session_registration_user_id' => session('registration_user_id')
        ]);

        // Use named route for clean redirect
        Log::info('🔵 REDIRECTING TO PAYMENT', [
            'user_id' => $user->id,
            'route' => route('payment.create', $user->id)
        ]);

        return redirect()->route('payment.create', $user->id);
    }
}
