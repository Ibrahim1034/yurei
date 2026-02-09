<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MembershipCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
   public function index()
    {
        $query = User::with('membershipCard');
        
        // Search functionality
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%")
                ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }
        
        // Filter functionality
        if (request()->has('filter')) {
            switch (request('filter')) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }
        
        $users = $query->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    public function myInvitations()
    {
        $invitations = auth()->user()->eventRegistrations()
            ->whereNotNull('invitation_code')
            ->with('event')
            ->latest()
            ->get();

        return view('user.invitations', compact('invitations'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required|integer|in:0,1',
            'is_active' => 'boolean',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->is_active ?? false,
            'registration_date' => now(),
            'expiration_date' => now()->addYear(),
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $userData['profile_picture'] = $path;
        }

        $user = User::create($userData);

        // Generate membership number
        $user->update([
            'membership_number' => 'YUREI-' . date('Y') . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT)
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('membershipCard', 'payments');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required|integer|in:0,1',
            'is_active' => 'boolean',
            'expiration_date' => 'nullable|date',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'is_active' => $request->is_active ?? $user->is_active,
            'expiration_date' => $request->expiration_date ?? $user->expiration_date,
        ];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $updateData['profile_picture'] = $path;
        }

        $user->update($updateData);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Delete profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Delete membership card if exists
        if ($user->membershipCard) {
            if ($user->membershipCard->card_photo_path && Storage::disk('public')->exists($user->membershipCard->card_photo_path)) {
                Storage::disk('public')->delete($user->membershipCard->card_photo_path);
            }
            $user->membershipCard->delete();
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

   
    /**
         * View user's membership card
         */
        public function viewMembershipCard(User $user)
        {
            // Eager load the membership card relationship
            $user->load('membershipCard');
            
            if (!$user->membershipCard) {
                return redirect()->back()->with('error', 'User does not have a membership card.');
            }

            return view('users.membership-card', compact('user'));
        }
}