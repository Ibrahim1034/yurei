<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventInvitationEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    /**
     * Display all events with invitations
     */
    public function index()
    {
        // Get events that have registrations with invitation codes
        $events = Event::whereHas('registrations', function($query) {
            $query->whereNotNull('invitation_code');
        })
        ->with(['registrations' => function($query) {
            $query->whereNotNull('invitation_code')
                  ->with('user');
        }])
        ->latest()
        ->get();

        return view('admin.invitations.index', compact('events'));
    }

    /**
     * Show event registrations with invitation codes
     */
    public function showEventRegistrations(Event $event)
    {
        $registrations = EventRegistration::where('event_id', $event->id)
            ->whereNotNull('invitation_code')
            ->with(['user', 'event'])
            ->get();

        return view('admin.invitations.event-registrations', compact('event', 'registrations'));
    }

    /**
     * Show form to assign invitations
     */
    public function create()
    {
        $events = Event::where('event_date', '>=', now())->get();
        $users = User::where('is_active', true)->get();
        
        return view('admin.invitations.assign', compact('events', 'users'));
    }

    /**
     * Send invitation emails to both members and external emails
     */
    public function sendInvitations(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'external_emails' => 'nullable|string',
            'custom_message' => 'nullable|string|max:500'
        ]);

        $event = Event::findOrFail($request->event_id);
        $sentCount = 0;
        $failedCount = 0;

        // Send to selected members
        if ($request->has('user_ids') && !empty($request->user_ids)) {
            $users = User::whereIn('id', $request->user_ids)->get();
            
            foreach ($users as $user) {
                try {
                    Mail::to($user->email)
                        ->send(new EventInvitationEmail($event, $user, $request->custom_message));
                    
                    $sentCount++;
                    
                    Log::info('Event invitation sent to member', [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'user_email' => $user->email
                    ]);
                } catch (\Exception $e) {
                    $failedCount++;
                    
                    Log::error('Failed to send event invitation to member', [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        // Send to external emails
        if ($request->has('external_emails') && !empty($request->external_emails)) {
            $externalEmails = $this->parseExternalEmails($request->external_emails);
            
            foreach ($externalEmails as $email) {
                try {
                    // Validate email format
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        Log::warning('Invalid email format skipped', ['email' => $email]);
                        continue;
                    }

                    // Create a temporary user object for external invitees
                    $externalUser = (object) [
                        'name' => 'Guest', // Will be filled during registration
                        'email' => $email,
                        'is_external' => true
                    ];

                    Mail::to($email)
                        ->send(new EventInvitationEmail($event, $externalUser, $request->custom_message));
                    
                    $sentCount++;
                    
                    Log::info('Event invitation sent to external email', [
                        'event_id' => $event->id,
                        'external_email' => $email
                    ]);
                } catch (\Exception $e) {
                    $failedCount++;
                    
                    Log::error('Failed to send event invitation to external email', [
                        'event_id' => $event->id,
                        'external_email' => $email,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        if ($sentCount === 0 && $failedCount === 0) {
            return redirect()->back()
                ->with('error', 'Please select at least one member or enter external emails to send invitations.')
                ->withInput();
        }

        $message = "Invitations sent successfully! {$sentCount} emails sent.";
        if ($failedCount > 0) {
            $message .= " {$failedCount} emails failed to send.";
        }

        return redirect()->route('invitations.index')
            ->with('success', $message);
    }

    /**
     * Parse external emails from text input
     */
    private function parseExternalEmails($emailsText)
    {
        // Split by commas, semicolons, or new lines
        $emails = preg_split('/[\s,;]+/', $emailsText);
        
        // Filter out empty values and trim each email
        return array_filter(array_map('trim', $emails), function($email) {
            return !empty($email);
        });
    }

    /**
     * Mark attendance for a registration
     */
    public function markAttendance(Request $request, EventRegistration $registration)
    {
        // Check if it's within 5 hours before the event
        $eventStart = $registration->event->event_date;
        $fiveHoursBefore = $eventStart->copy()->subHours(5);
        
        if (now()->lt($fiveHoursBefore)) {
            return response()->json([
                'success' => false,
                'error' => 'Attendance can only be marked starting 5 hours before the event.'
            ], 400);
        }

        $registration->update([
            'status' => EventRegistration::STATUS_ATTENDED
        ]);

        Log::info('Attendance marked', [
            'registration_id' => $registration->id,
            'event_id' => $registration->event_id,
            'user_email' => $registration->is_guest ? $registration->guest_email : ($registration->user ? $registration->user->email : 'N/A')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully.'
        ]);
    }

    /**
     * Export attendance report
     */
    public function exportAttendanceReport(Event $event)
    {
        $registrations = EventRegistration::where('event_id', $event->id)
            ->whereNotNull('invitation_code')
            ->with(['user'])
            ->get();

        $fileName = "attendance-report-{$event->id}-" . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function() use ($registrations, $event) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Event: ' . $event->title,
                'Date: ' . $event->event_date->format('Y-m-d H:i'),
                'Venue: ' . $event->venue,
                'Generated: ' . now()->format('Y-m-d H:i')
            ]);
            fputcsv($file, []); // Empty row
            
            // Column headers
            fputcsv($file, [
                'Registration ID',
                'Name',
                'Email',
                'Phone',
                'User Type',
                'Invitation Code',
                'Registration Date',
                'Payment Status',
                'Attendance Status'
            ]);

            foreach ($registrations as $registration) {
                fputcsv($file, [
                    'EVENT-' . $registration->id,
                    $registration->registrant_name,
                    $registration->registrant_email,
                    $registration->registrant_phone,
                    $registration->is_guest ? 'External Guest' : 'Member',
                    $registration->invitation_code,
                    $registration->registration_date->format('Y-m-d H:i'),
                    $registration->payment_status,
                    $registration->status === EventRegistration::STATUS_ATTENDED ? 'Attended' : 'Not Attended'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get real-time attendance data
     */
    public function getAttendanceData(Event $event)
    {
        $registrations = EventRegistration::where('event_id', $event->id)
            ->whereNotNull('invitation_code')
            ->with(['user'])
            ->get();

        $totalRegistrations = $registrations->count();
        $attendedCount = $registrations->where('status', EventRegistration::STATUS_ATTENDED)->count();
        $notAttendedCount = $totalRegistrations - $attendedCount;

        return response()->json([
            'success' => true,
            'data' => [
                'total_registrations' => $totalRegistrations,
                'attended_count' => $attendedCount,
                'not_attended_count' => $notAttendedCount,
                'registrations' => $registrations->map(function($registration) {
                    return [
                        'id' => $registration->id,
                        'name' => $registration->registrant_name,
                        'email' => $registration->registrant_email,
                        'user_type' => $registration->is_guest ? 'External Guest' : 'Member',
                        'invitation_code' => $registration->invitation_code,
                        'status' => $registration->status,
                        'attended' => $registration->status === EventRegistration::STATUS_ATTENDED,
                        'registration_date' => $registration->registration_date->format('M d, Y H:i')
                    ];
                })
            ]
        ]);
    }
}