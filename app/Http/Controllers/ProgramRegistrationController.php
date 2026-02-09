<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProgramRegistrationConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProgramRegistrationController extends Controller
{
    public function showRegistrationForm(Program $program)
    {
        if (!$program->isRegistrationOpen()) {
            return redirect()->route('welcome')
                ->with('error', 'Registration for this program is closed.');
        }

        return view('programs.register', compact('program'));
    }

    public function processRegistration(Request $request, Program $program)
    {
        if (!$program->isRegistrationOpen()) {
            return response()->json([
                'success' => false,
                'error' => 'Registration for this program is closed.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $user = null;
            $isGuest = false;

            if (auth()->check()) {
                $user = auth()->user();
            } else {
                $request->validate([
                    'guest_name' => 'required|string|max:255',
                    'guest_email' => 'required|email|max:255',
                    'guest_phone' => 'required|string|max:20',
                ]);

                $isGuest = true;
                $user = null;
            }

            $existingRegistration = $this->getExistingRegistration($program, $user, $request);

            if ($existingRegistration) {
                $result = $this->handleExistingRegistration($existingRegistration);
                if ($result) {
                    return $result;
                }
            }

            $invitationCode = $this->generateUniqueInvitationCode();

            $registrationData = [
                'program_id' => $program->id,
                'user_id' => $user ? $user->id : null,
                'amount_paid' => $program->is_paid ? $program->registration_fee : 0,
                'payment_status' => $program->is_paid ? 'pending' : 'paid',
                'registration_date' => now(),
                'status' => $program->is_paid ? 'pending' : 'confirmed',
                'is_guest' => $isGuest,
                'confirmation_email_sent' => false,
                'invitation_code' => $invitationCode
            ];

            if ($isGuest) {
                $registrationData['guest_name'] = $request->guest_name;
                $registrationData['guest_email'] = $request->guest_email;
                $registrationData['guest_phone'] = $request->guest_phone;
            }

            $registration = ProgramRegistration::create($registrationData);

            Log::info('Program registration created', [
                'registration_id' => $registration->id,
                'program_id' => $program->id,
                'user_id' => $user ? $user->id : 'guest',
                'is_paid' => $program->is_paid,
                'invitation_code' => $invitationCode
            ]);

            if (!$program->is_paid) {
                $program->increment('current_participants');
                
                try {
                    $emailTo = $user ? $user->email : $request->guest_email;
                    
                    Mail::to($emailTo)
                        ->send(new ProgramRegistrationConfirmation($registration));
                    
                    $registration->update(['confirmation_email_sent' => true]);
                    
                    Log::info('Free program registration confirmation email sent', [
                        'registration_id' => $registration->id,
                        'email_to' => $emailTo
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send free program registration email', [
                        'error' => $e->getMessage(),
                        'registration_id' => $registration->id
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'registration_id' => $registration->id,
                'free_program' => !$program->is_paid,
                'message' => $program->is_paid 
                    ? 'Registration created. Please complete payment.' 
                    : 'Registration confirmed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Program registration failed', [
                'error' => $e->getMessage(),
                'program_id' => $program->id
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateUniqueInvitationCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (ProgramRegistration::where('invitation_code', $code)->exists());

        return $code;
    }

    private function getExistingRegistration(Program $program, $user, Request $request)
    {
        if ($user) {
            return ProgramRegistration::where('program_id', $program->id)
                ->where('user_id', $user->id)
                ->first();
        } else {
            return ProgramRegistration::where('program_id', $program->id)
                ->where('guest_email', $request->guest_email)
                ->first();
        }
    }

    private function handleExistingRegistration(ProgramRegistration $existingRegistration)
    {
        $status = $existingRegistration->status;
        $paymentStatus = $existingRegistration->payment_status;

        if ($status === ProgramRegistration::STATUS_CONFIRMED && $paymentStatus === 'paid') {
            return response()->json([
                'success' => false,
                'error' => 'You have already successfully registered and paid for this program.'
            ], 400);
        }

        if ($status === ProgramRegistration::STATUS_PENDING && $paymentStatus === 'pending') {
            Log::info('Automatically deleting pending program registration', [
                'old_registration_id' => $existingRegistration->id
            ]);
            
            $existingRegistration->delete();
            return null;
        }

        $existingRegistration->delete();
        return null;
    }
}