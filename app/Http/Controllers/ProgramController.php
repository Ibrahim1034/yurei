<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::with('user')->latest()->get();
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_paid' => 'required|boolean',
            'registration_fee' => $request->is_paid ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'max_participants' => 'required|integer|min:0',
            'registration_deadline' => 'nullable|date',
        ]);

        $programData = [
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'venue' => $request->venue,
            'is_paid' => $request->is_paid,
            'registration_fee' => $request->is_paid ? $request->registration_fee : 0,
            'max_participants' => $request->max_participants,
            'registration_deadline' => $request->registration_deadline ?: null,
            'created_by' => auth()->id(),
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('programs', 'public');
            $programData['image'] = $imagePath;
        }

        Program::create($programData);

        return redirect()->route('programs.index')
            ->with('success', 'Program created successfully!');
    }

    public function show(Program $program)
    {
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    /**
     * Display programs list for users (without admin controls)
     */
    public function userList()
    {
        $programs = Program::with('user')
            ->where('start_date', '>=', now())
            ->orWhere(function($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
            ->orderBy('start_date')
            ->get();
        
        return view('programs.user-list', compact('programs'));
    }

    /**
     * Display all programs with attendants overview
     */
    public function attendants()
    {
        $programs = Program::withCount(['registrations' => function($query) {
            $query->where(function($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('status', 'attended');
            });
        }])
        ->whereHas('registrations', function($query) {
            $query->where(function($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('status', 'attended');
            });
        })
        ->latest()
        ->get();

        return view('programs.attendants', compact('programs'));
    }

    /**
     * Show attendants for a specific program
     */
    public function showAttendants(Program $program)
    {
        $confirmedRegistrations = $program->getConfirmedRegistrations();
        
        return view('programs.program-attendants', compact('program', 'confirmedRegistrations'));
    }

    /**
     * Mark attendance for program attendants
     */
    public function markAttendance(Request $request, Program $program)
    {
        if (!$program->canMarkAttendance()) {
            return redirect()->back()
                ->with('error', 'Attendance marking is not available for this program at this time.');
        }

        $request->validate([
            'attendants' => 'required|array',
            'attendants.*' => 'exists:program_registrations,id'
        ]);

        try {
            // First, reset all to confirmed
            ProgramRegistration::where('program_id', $program->id)
                ->whereIn('status', ['confirmed', 'attended'])
                ->update(['status' => 'confirmed']);

            // Then mark selected as attended
            ProgramRegistration::whereIn('id', $request->attendants)
                ->update(['status' => 'attended']);

            $attendedCount = count($request->attendants);
            
            return redirect()->back()
                ->with('success', "Attendance marked successfully for {$attendedCount} attendants.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to mark attendance: ' . $e->getMessage());
        }
    }

    /**
     * Export attendants PDF
     */
    public function exportAttendants(Program $program)
    {
        if (!$program->canMarkAttendance() && !$program->isCompleted()) {
            return redirect()->back()
                ->with('error', 'Attendance report is only available during the program or after completion.');
        }

        $confirmedRegistrations = $program->getConfirmedRegistrations();
        $attendedCount = $program->getAttendedCountAttribute();
        
        $pdf = Pdf::loadView('exports.program-attendants', [
            'program' => $program,
            'registrations' => $confirmedRegistrations,
            'attendedCount' => $attendedCount,
            'exportDate' => now()
        ]);

        return $pdf->download("program-attendants-{$program->id}-" . now()->format('Y-m-d') . '.pdf');
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'venue' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_paid' => 'required|boolean',
            'registration_fee' => $request->is_paid ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'max_participants' => 'required|integer|min:0',
            'registration_deadline' => 'nullable|date',
        ]);

        $programData = [
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'venue' => $request->venue,
            'is_paid' => $request->is_paid,
            'registration_fee' => $request->is_paid ? $request->registration_fee : 0,
            'max_participants' => $request->max_participants,
            'registration_deadline' => $request->registration_deadline ?: null,
        ];

        if ($request->hasFile('image')) {
            if ($program->image && Storage::disk('public')->exists($program->image)) {
                Storage::disk('public')->delete($program->image);
            }
            
            $imagePath = $request->file('image')->store('programs', 'public');
            $programData['image'] = $imagePath;
        }

        $program->update($programData);

        return redirect()->route('programs.index')
            ->with('success', 'Program updated successfully!');
    }

    public function destroy(Program $program)
    {
        if ($program->image && Storage::disk('public')->exists($program->image)) {
            Storage::disk('public')->delete($program->image);
        }

        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', 'Program deleted successfully!');
    }
}