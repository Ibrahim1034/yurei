<?php

namespace App\Mail;

use App\Models\ProgramRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgramRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public function __construct(ProgramRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function build()
    {
        return $this->subject('Program Registration Confirmation - ' . $this->registration->program->title)
                    ->view('emails.program-registration-confirmation')
                    ->with([
                        'registration' => $this->registration,
                        'program' => $this->registration->program,
                        'user' => $this->registration->user,
                        'is_guest' => $this->registration->is_guest,
                        'guest_name' => $this->registration->guest_name,
                        'guest_email' => $this->registration->guest_email,
                        'invitation_code' => $this->registration->invitation_code
                    ]);
    }
}