<?php
// app/Http/Controllers/LeadershipController.php

namespace App\Http\Controllers;

use App\Models\Leadership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeadershipController extends Controller
{
    /**
     * Display a listing of the leaders.
     */
    public function index()
    {
        $leaders = Leadership::ordered()->get();
        return view('leadership.index', compact('leaders'));
    }

    /**
     * Show the form for creating a new leader.
     */
    public function create()
    {
        return view('leadership.create');
    }

    /**
     * Store a newly created leader in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
        ]);

        $leaderData = [
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
            'social_facebook' => $request->social_facebook,
            'social_twitter' => $request->social_twitter,
            'social_linkedin' => $request->social_linkedin,
            'social_instagram' => $request->social_instagram,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('leadership', 'public');
            $leaderData['image_path'] = $path;
        }

        Leadership::create($leaderData);

        return redirect()->route('leadership.index')
            ->with('success', 'Leader added successfully.');
    }

    /**
     * Display the specified leader.
     */
    public function show(Leadership $leadership)
    {
         return view('leadership.show', ['leader' => $leadership]);
    }

    /**
     * Show the form for editing the specified leader.
     */
    public function edit(Leadership $leadership)
    {
         return view('leadership.edit', ['leader' => $leadership]);
    }

    /**
     * Update the specified leader in storage.
     */
    public function update(Request $request, Leadership $leadership)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
        ]);

        $updateData = [
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'order' => $request->order ?? $leadership->order,
            'is_active' => $request->is_active ?? $leadership->is_active,
            'social_facebook' => $request->social_facebook,
            'social_twitter' => $request->social_twitter,
            'social_linkedin' => $request->social_linkedin,
            'social_instagram' => $request->social_instagram,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($leadership->image_path && Storage::disk('public')->exists($leadership->image_path)) {
                Storage::disk('public')->delete($leadership->image_path);
            }
            
            $path = $request->file('image')->store('leadership', 'public');
            $updateData['image_path'] = $path;
        }

        $leadership->update($updateData);

        return redirect()->route('leadership.index')
            ->with('success', 'Leader updated successfully.');
    }

    /**
     * Remove the specified leader from storage.
     */
    public function destroy(Leadership $leadership)
    {
        // Delete image if exists
        if ($leadership->image_path && Storage::disk('public')->exists($leadership->image_path)) {
            Storage::disk('public')->delete($leadership->image_path);
        }

        $leadership->delete();

        return redirect()->route('leadership.index')
            ->with('success', 'Leader deleted successfully.');
    }
}