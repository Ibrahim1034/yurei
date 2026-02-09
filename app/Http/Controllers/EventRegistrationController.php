<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRegistrationConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EventRegistrationController extends Controller
{
    /**
     * Show the registration form for an event
     */
    public function showRegistrationForm(Event $event)
    {
        // Check if registration is open
        if (!$event->isRegistrationOpen()) {
            return redirect()->route('welcome')
                ->with('error', 'Registration for this event is closed.');
        }

        return view('events.register', compact('event'));
    }

    /**
     * Process event registration
     */
    public function processRegistration(Request $request, Event $event)
    {
        // Check if registration is open
        if (!$event->isRegistrationOpen()) {
            return response()->json([
                'success' => false,
                'error' => 'Registration for this event is closed.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $user = null;
            $isGuest = false;

            // Handle user authentication/creation
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                // For guest registration, validate guest data
                $request->validate([
                    'guest_name' => 'required|string|max:255',
                    'guest_email' => 'required|email|max:255',
                    'guest_phone' => 'required|string|max:20',
                ]);

                $isGuest = true;
                $user = null;
            }

            // Check if user/guest is already registered for this event with different statuses
            $existingRegistration = $this->getExistingRegistration($event, $user, $request);

            if ($existingRegistration) {
                $result = $this->handleExistingRegistration($existingRegistration);
                if ($result) {
                    return $result;
                }
                // If result is null, continue with new registration (old one was deleted)
            }

            // Generate invitation code first
            $invitationCode = $this->generateUniqueInvitationCode();

            // Create registration record
            $registrationData = [
                'event_id' => $event->id,
                'user_id' => $user ? $user->id : null,
                'amount_paid' => $event->is_paid ? $event->registration_fee : 0,
                'payment_status' => $event->is_paid ? 'pending' : 'paid',
                'registration_date' => now(),
                'status' => $event->is_paid ? 'pending' : 'confirmed',
                'is_guest' => $isGuest,
                'confirmation_email_sent' => false,
                'invitation_code' => $invitationCode // Use the generated code
            ];

            // Add guest information if it's a guest registration
            if ($isGuest) {
                $registrationData['guest_name'] = $request->guest_name;
                $registrationData['guest_email'] = $request->guest_email;
                $registrationData['guest_phone'] = $request->guest_phone;
            }

            $registration = EventRegistration::create($registrationData);

            Log::info('Event registration created', [
                'registration_id' => $registration->id,
                'event_id' => $event->id,
                'user_id' => $user ? $user->id : 'guest',
                'is_paid' => $event->is_paid,
                'user_email' => $user ? $user->email : $request->guest_email,
                'invitation_code' => $invitationCode // Log the code
            ]);

            // Update event participant count for free events and send email
            if (!$event->is_paid) {
                $event->increment('current_participants');
                
                // Send confirmation email for free events
                try {
                    $emailTo = $user ? $user->email : $request->guest_email;
                    
                    Log::info('Attempting to send event registration email', [
                        'registration_id' => $registration->id,
                        'email_to' => $emailTo,
                        'is_guest' => $isGuest,
                        'event_title' => $event->title,
                        'invitation_code' => $invitationCode // Log the code
                    ]);
                    
                    // Refresh the registration to ensure we have the latest data
                    $registration->refresh();
                    
                    Mail::to($emailTo)
                        ->send(new EventRegistrationConfirmation($registration));
                    
                    $registration->update(['confirmation_email_sent' => true]);
                    
                    Log::info('Free event registration confirmation email sent successfully', [
                        'registration_id' => $registration->id,
                        'user_email' => $emailTo,
                        'event_title' => $event->title,
                        'invitation_code' => $invitationCode // Log the code
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send free event registration email', [
                        'error' => $e->getMessage(),
                        'registration_id' => $registration->id,
                        'user_email' => $user ? $user->email : $request->guest_email,
                        'trace' => $e->getTraceAsString(),
                        'invitation_code' => $invitationCode // Log the code even on error
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'registration_id' => $registration->id,
                'free_event' => !$event->is_paid,
                'message' => $event->is_paid 
                    ? 'Registration created. Please complete payment.' 
                    : 'Registration confirmed successfully! Check your email for confirmation.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Event registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'event_id' => $event->id,
                'guest_email' => $request->guest_email ?? 'N/A'
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a unique invitation code
     */
    private function generateUniqueInvitationCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (EventRegistration::where('invitation_code', $code)->exists());

        return $code;
    }

    /**
     * Get existing registration based on user/guest and event
     */
    private function getExistingRegistration(Event $event, $user, Request $request)
    {
        if ($user) {
            // For authenticated users, check by user_id
            return EventRegistration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();
        } else {
            // For guests, check by email and event
            return EventRegistration::where('event_id', $event->id)
                ->where('guest_email', $request->guest_email)
                ->first();
        }
    }

    /**
     * Handle existing registration based on status - AUTOMATICALLY DELETE PENDING ONES
     */
    private function handleExistingRegistration(EventRegistration $existingRegistration)
    {
        $status = $existingRegistration->status;
        $paymentStatus = $existingRegistration->payment_status;

        Log::info('Found existing registration', [
            'registration_id' => $existingRegistration->id,
            'status' => $status,
            'payment_status' => $paymentStatus
        ]);

        // If status is confirmed and payment is paid, block registration
        if ($status === EventRegistration::STATUS_CONFIRMED && $paymentStatus === 'paid') {
            return response()->json([
                'success' => false,
                'error' => 'You have already successfully registered and paid for this event.'
            ], 400);
        }

        // If status is pending and payment is pending, AUTOMATICALLY DELETE and allow new registration
        if ($status === EventRegistration::STATUS_PENDING && $paymentStatus === 'pending') {
            Log::info('Automatically deleting pending registration for new attempt', [
                'old_registration_id' => $existingRegistration->id,
                'user_email' => $existingRegistration->is_guest ? $existingRegistration->guest_email : ($existingRegistration->user ? $existingRegistration->user->email : 'N/A')
            ]);
            
            $existingRegistration->delete();
            
            Log::info('Pending registration deleted automatically', [
                'old_registration_id' => $existingRegistration->id
            ]);
            
            // Return null to allow new registration to proceed
            return null;
        }

        // For other statuses (cancelled, no_show, attended), delete old registration and allow new one
        Log::info('Deleting old registration with non-blocking status for new registration', [
            'old_registration_id' => $existingRegistration->id,
            'status' => $status,
            'payment_status' => $paymentStatus
        ]);
        
        $existingRegistration->delete();
        
        Log::info('Old registration deleted successfully', [
            'old_registration_id' => $existingRegistration->id
        ]);

        // Return null to allow new registration to proceed
        return null;
    }

    /**
     * Show registration success page
     */
    public function showSuccess(EventRegistration $registration)
    {
        return view('events.registration-success', compact('registration'));
    }
}