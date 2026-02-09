<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Check if user is active
        if (!$request->user()->is_active) {
            abort(403, 'Please complete your payment to access your profile.');
        }
        
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Check if user is active
        if (!$request->user()->is_active) {
            abort(403, 'Please complete your payment to update your profile.');
        }
        
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update user's name
     */
    public function updateName(Request $request): RedirectResponse
    {
        if (!$request->user()->is_active) {
            abort(403, 'Please complete your payment to update your profile.');
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->user()->update([
            'name' => $request->name
        ]);

        return Redirect::route('profile.edit')->with('success', 'Name updated successfully!');
    }

    /**
     * Update user's email
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        if (!$request->user()->is_active) {
            abort(403, 'Please complete your payment to update your profile.');
        }

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->user()->update([
            'email' => $request->email,
            'email_verified_at' => null // Require email verification again
        ]);

        return Redirect::route('profile.edit')->with('success', 'Email updated successfully! Please verify your new email address.');
    }

    /**
     * Update user's phone number
     */
    public function updatePhone(Request $request): RedirectResponse
    {
        if (!$request->user()->is_active) {
            abort(403, 'Please complete your payment to update your profile.');
        }

        $validator = Validator::make($request->all(), [
            'phone_number' => ['required', 'string', 'max:15', 'unique:users,phone_number,' . $request->user()->id],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->user()->update([
            'phone_number' => $request->phone_number
        ]);

        return Redirect::route('profile.edit')->with('success', 'Phone number updated successfully!');
    }

    /**
     * Update user's profile picture
     */
    public function updateProfilePicture(Request $request): RedirectResponse
    {
        if (!$request->user()->is_active) {
            abort(403, 'Please complete your payment to update your profile.');
        }

        $validator = Validator::make($request->all(), [
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->user()->updateProfilePicture($request->file('profile_picture'));

        return Redirect::route('profile.edit')->with('success', 'Profile picture updated successfully!');
    }

    /**
     * Update user's password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        if (!$request->user()->is_active) {
            abort(403, 'Please complete your payment to update your profile.');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return Redirect::route('profile.edit')->with('success', 'Password updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}