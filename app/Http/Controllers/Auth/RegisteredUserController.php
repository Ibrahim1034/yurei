<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:15', 'unique:'.User::class],
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

        event(new Registered($user));

        Auth::login($user);

        // Redirect to payment page
        return redirect()->route('payment.create', ['user' => $user->id]);
    }
}