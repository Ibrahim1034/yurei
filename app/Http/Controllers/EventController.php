<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::with('user')->latest()->get();
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_paid' => 'required|boolean',
            'registration_fee' => $request->is_paid ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'max_participants' => 'required|integer|min:0',
            'registration_deadline' => 'nullable|date',
        ]);

        $eventData = [
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'venue' => $request->venue,
            'is_paid' => $request->is_paid,
            'registration_fee' => $request->is_paid ? $request->registration_fee : 0,
            'max_participants' => $request->max_participants,
            'registration_deadline' => $request->registration_deadline ?: null,
            'created_by' => auth()->id(),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $eventData['image'] = $imagePath;
        }

        Event::create($eventData);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_paid' => 'required|boolean',
            'registration_fee' => $request->is_paid ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'max_participants' => 'required|integer|min:0',
            'registration_deadline' => 'nullable|date',
        ]);

        $eventData = [
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'venue' => $request->venue,
            'is_paid' => $request->is_paid,
            'registration_fee' => $request->is_paid ? $request->registration_fee : 0,
            'max_participants' => $request->max_participants,
            'registration_deadline' => $request->registration_deadline ?: null,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            
            $imagePath = $request->file('image')->store('events', 'public');
            $eventData['image'] = $imagePath;
        }

        $event->update($eventData);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Delete image if exists
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}